<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Akun;
use App\Models\LabelTransaksi;
use App\Models\Usaha;
use App\Services\TransaksiKasBankService;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class QuickExpenseController extends Controller
{
    public function __construct(
        private TransaksiKasBankService $kasBankService,
        private GoogleDriveService $gdriveService
    ) {}

    /**
     * Halaman Utama Input Pengeluaran Cepat (Quick Expense)
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
        }

        // Akun Kas & Bank (Sumber Pengeluaran)
        $akunKasBank = collect();
        // Akun Beban / Pengeluaran (Tujuan Pengeluaran)
        $akunBeban = collect();
        // Label Transaksi Pengeluaran
        $labels = collect();
        // Riwayat Pengeluaran Cepat Hari Ini / Terakhir
        $recentExpenses = collect();

        if ($selectedUsahaId) {
            // Akun Kas/Bank
            $akunKasBank = Akun::where('usaha_id', $selectedUsahaId)
                ->where(function ($q) {
                    $q->where('is_kas_bank', 1)
                      ->orWhere('sub_klasifikasi', 'like', '%KAS%')
                      ->orWhere('sub_klasifikasi', 'like', '%BANK%')
                      ->orWhere('name', 'like', '%Kas%')
                      ->orWhere('name', 'like', '%Bank%')
                      ->orWhere('klasifikasi', 'ASET');
                })
                ->orderBy('kode', 'asc')
                ->get();

            // Jika kosong, ambil semua akun usaha
            if ($akunKasBank->isEmpty()) {
                $akunKasBank = Akun::where('usaha_id', $selectedUsahaId)->get();
            }

            // Akun Beban/Biaya (Beban Operasional, Beban Pokok, Beban Lain-lain)
            $akunBeban = Akun::where('usaha_id', $selectedUsahaId)
                ->where(function ($q) {
                    $q->where('klasifikasi', 'like', '%BEBAN%')
                      ->orWhere('klasifikasi', 'like', '%BIAYA%')
                      ->orWhere('kode', 'like', '5%')
                      ->orWhere('kode', 'like', '6%')
                      ->orWhere('kode', 'like', '7%')
                      ->orWhere('kategori_lr', 'like', '%BEBAN%');
                })
                ->orderBy('kode', 'asc')
                ->get();

            // Jika akun beban masih kosong, ambil selain akun kas bank
            if ($akunBeban->isEmpty()) {
                $akunBeban = Akun::where('usaha_id', $selectedUsahaId)
                    ->where('klasifikasi', '!=', 'ASET')
                    ->orderBy('kode', 'asc')
                    ->get();
            }

            // Labels Pengeluaran
            $labels = LabelTransaksi::where('usaha_id', $selectedUsahaId)
                ->where('tipe_utama', 'PENGELUARAN')
                ->get();

            // Recent Expenses (Terbaru)
            $recentExpenses = Transaksi::where('usaha_id', $selectedUsahaId)
                ->whereHas('label', function ($q) {
                    $q->where('tipe_utama', 'PENGELUARAN');
                })
                ->with(['label', 'akunPayment', 'akunLawan'])
                ->latest()
                ->take(10)
                ->get();
        }

        // Preset Chips Cepat (6 Kategori Pengeluaran Terpopuler)
        $presets = $this->buildQuickExpensePresets($akunBeban);

        // Status Google Drive
        $isGdriveReady = $this->gdriveService->isConfigured();
        $isOAuthConnected = $this->gdriveService->isOAuthConnected();
        $gdriveFolderId = $this->gdriveService->getFolderId();
        $gdriveClientId = $this->gdriveService->getClientId();
        $gdriveClientSecret = $this->gdriveService->getClientSecret();
        $gdriveAuthMethod = $this->gdriveService->getAuthMethod();

        return view('admin.quick_expense.index', compact(
            'usahas',
            'selectedUsahaId',
            'akunKasBank',
            'akunBeban',
            'labels',
            'presets',
            'recentExpenses',
            'isGdriveReady',
            'isOAuthConnected',
            'gdriveFolderId',
            'gdriveClientId',
            'gdriveClientSecret',
            'gdriveAuthMethod'
        ));
    }

    /**
     * Simpan Pengeluaran Cepat + Upload Bukti + Auto Jurnal + Sync Google Drive
     */
    public function store(Request $request)
    {
        $request->validate([
            'usaha_id' => 'required|exists:usahas,id',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_payment_id' => 'required|exists:akuns,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp,heic|max:15360',
            'mobile_token' => 'nullable|string',
        ]);

        $selectedUsahaId = $request->usaha_id;
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return back()->with('error', 'Anda tidak memiliki hak akses pada usaha ini.');
        }

        $usaha = Usaha::findOrFail($selectedUsahaId);
        $akunBeban = Akun::findOrFail($request->akun_beban_id);
        $akunKas = Akun::findOrFail($request->akun_payment_id);

        // Label Transaksi Pengeluaran
        $labelId = $request->label_id;
        if (!$labelId) {
            $defaultLabel = LabelTransaksi::where('usaha_id', $selectedUsahaId)
                ->where('tipe_utama', 'PENGELUARAN')
                ->first();

            if (!$defaultLabel) {
                $defaultLabel = LabelTransaksi::create([
                    'usaha_id' => $selectedUsahaId,
                    'nama_label' => 'Pengeluaran Cepat',
                    'tipe_utama' => 'PENGELUARAN',
                    'deskripsi' => 'Pengeluaran harian cepat melalui portal Quick Expense',
                ]);
            }
            $labelId = $defaultLabel->id;
        }

        // Generate Kode Transaksi Unik
        $todayStr = date('Ymd', strtotime($request->tanggal));
        $countToday = Transaksi::where('usaha_id', $selectedUsahaId)
            ->whereDate('tanggal', $request->tanggal)
            ->count() + 1;
        $kodeTransaksi = 'EXP-' . $todayStr . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        // Handle File Bukti Transfer
        $buktiPath = null;
        $gdriveFileId = null;
        $gdriveViewLink = null;
        $gdriveStatus = 'LOCAL';

        // 1. Cek apakah ada upload langsung dari browser
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $extension = $file->getClientOriginalExtension();
            $safeFileName = "Bukti_{$kodeTransaksi}_" . time() . ".{$extension}";
            $folderPath = "bukti_transfer/" . date('Y/m', strtotime($request->tanggal));
            $buktiPath = $file->storeAs($folderPath, $safeFileName, 'public');
        }
        // 2. Cek apakah diupload via QR Mobile
        elseif ($request->filled('mobile_token')) {
            $cacheKey = "quick_upload_{$request->mobile_token}";
            $mobileData = Cache::get($cacheKey);

            if ($mobileData && isset($mobileData['temp_path']) && Storage::disk('public')->exists($mobileData['temp_path'])) {
                $tempPath = $mobileData['temp_path'];
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $safeFileName = "Bukti_{$kodeTransaksi}_" . time() . ".{$extension}";
                $folderPath = "bukti_transfer/" . date('Y/m', strtotime($request->tanggal));
                $newPath = "{$folderPath}/{$safeFileName}";

                // Pindahkan dari temp ke direktori permanen
                Storage::disk('public')->copy($tempPath, $newPath);
                $buktiPath = $newPath;

                // Bersihkan cache
                Cache::forget($cacheKey);
            }
        }

        // 3. Sinkronisasi ke Google Drive jika file ada dan Google Drive dikonfigurasi
        $gdriveMessage = '';
        if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
            $absolutePath = Storage::disk('public')->path($buktiPath);
            $fileNameOnDrive = basename($buktiPath);

            if ($this->gdriveService->isConfigured()) {
                $coaIdentifier = ($akunBeban->kode ? $akunBeban->kode . ' - ' : '') . $akunBeban->name;
                $yearMonth = date('Y-m', strtotime($request->tanggal));

                // Siapkan direktori terstruktur di Google Drive:
                // [Root] -> Usaha [Nama] -> Pengeluaran -> [Kode - Nama COA] -> [YYYY-MM]
                $targetFolderId = $this->gdriveService->ensureDirectoryHierarchy(
                    $usaha->nama,
                    'Pengeluaran',
                    $coaIdentifier,
                    $yearMonth
                );

                if ($targetFolderId) {
                    $uploadResult = $this->gdriveService->uploadFile($absolutePath, $fileNameOnDrive, $targetFolderId);
                    if ($uploadResult['success']) {
                        $gdriveFileId = $uploadResult['file_id'];
                        $gdriveViewLink = $uploadResult['view_link'];
                        $gdriveStatus = 'SYNCED';
                        $gdriveMessage = ' Dokumen berhasil disinkronkan ke Google Drive.';
                    } else {
                        $gdriveStatus = 'FAILED';
                        $gdriveMessage = ' (Upload Google Drive tertunda: ' . ($uploadResult['message'] ?? 'Error') . ')';
                    }
                }
            } else {
                $gdriveStatus = 'LOCAL';
                $gdriveMessage = ' File disimpan di storage lokal (Google Drive belum dihubungkan).';
            }
        }

        // 4. Catat Transaksi di Database
        $transaksi = DB::transaction(function () use ($request, $selectedUsahaId, $labelId, $kodeTransaksi, $buktiPath, $gdriveFileId, $gdriveViewLink, $gdriveStatus) {
            $trx = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'usaha_id' => $selectedUsahaId,
                'label_id' => $labelId,
                'pelanggan_id' => null,
                'supplier_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'akun_lawan_id' => $request->akun_beban_id,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan ?: 'Pengeluaran cepat',
                'status' => 'PENDING',
                'bukti_transfer' => $buktiPath,
                'gdrive_file_id' => $gdriveFileId,
                'gdrive_view_link' => $gdriveViewLink,
                'gdrive_status' => $gdriveStatus,
            ]);

            // Eksekusi otomatisasi Jurnal Umum & Saldo
            $this->kasBankService->prosesTransaksi($trx);

            return $trx;
        });

        $message = "Pengeluaran {$transaksi->kode_transaksi} sebesar Rp " . number_format($transaksi->jumlah, 0, ',', '.') . " berhasil dicatat dan masuk Jurnal Umum!{$gdriveMessage}";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'transaksi' => $transaksi->load(['label', 'akunPayment', 'akunLawan']),
            ]);
        }

        return redirect()->route('admin.quick-expense.index', ['usaha_id' => $selectedUsahaId])
            ->with('success', $message);
    }

    /**
     * Generate Token Sementara untuk Pairing Kamera HP via QR Code
     */
    public function generateToken()
    {
        $token = 'EXP-' . Str::upper(Str::random(8));
        Cache::put("quick_upload_{$token}", [
            'status' => 'waiting',
            'created_at' => now(),
        ], now()->addMinutes(15));

        $mobileUrl = route('quick-expense.mobile', ['token' => $token]);

        return response()->json([
            'token' => $token,
            'mobile_url' => $mobileUrl,
        ]);
    }

    /**
     * Polling Status Upload dari Mobile (Dijalankan secara berkala oleh Browser Komputer)
     */
    public function checkToken($token)
    {
        $cacheKey = "quick_upload_{$token}";
        $data = Cache::get($cacheKey);

        if (!$data) {
            return response()->json(['status' => 'expired']);
        }

        return response()->json($data);
    }

    /**
     * Halaman Mobile untuk Pengambilan Foto Bukti Transfer dari Smartphone
     */
    public function mobileForm($token)
    {
        $cacheKey = "quick_upload_{$token}";
        if (!Cache::has($cacheKey)) {
            return view('admin.quick_expense.mobile_expired');
        }

        return view('admin.quick_expense.mobile_form', compact('token'));
    }

    /**
     * Terima Upload File dari Smartphone
     */
    public function mobileUpload(Request $request, $token)
    {
        $request->validate([
            'foto' => 'required|file|mimes:jpg,jpeg,png,webp,heic,pdf|max:15360',
        ]);

        $cacheKey = "quick_upload_{$token}";
        if (!Cache::has($cacheKey)) {
            return response()->json(['success' => false, 'message' => 'Sesi scan QR telah kedaluwarsa. Silakan muat ulang di laptop Anda.'], 410);
        }

        $file = $request->file('foto');
        $ext = $file->getClientOriginalExtension();
        $tempFileName = "temp_{$token}_" . time() . ".{$ext}";
        $tempPath = $file->storeAs('temp_bukti', $tempFileName, 'public');

        Cache::put($cacheKey, [
            'status' => 'uploaded',
            'temp_path' => $tempPath,
            'preview_url' => asset('storage/' . $tempPath),
            'file_name' => $file->getClientOriginalName(),
            'uploaded_at' => now()->toIso8601String(),
        ], now()->addMinutes(15));

        return response()->json([
            'success' => true,
            'message' => 'Foto bukti transfer berhasil dikirim ke layar komputer Anda!',
            'preview_url' => asset('storage/' . $tempPath),
        ]);
    }

    /**
     * Redirect ke Google untuk Login & Otorisasi OAuth 2.0 (Drive Saya 5 TB)
     */
    public function googleRedirect()
    {
        $authUrl = $this->gdriveService->getOAuthAuthUrl();
        if (!$authUrl) {
            return back()->with('error', 'Client ID Google OAuth belum diisi. Masukkan Client ID & Client Secret di pengaturan terlebih dahulu.');
        }

        return redirect()->away($authUrl);
    }

    /**
     * Callback dari Google setelah User Menyetujui Otorisasi
     */
    public function googleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('admin.quick-expense.index')
                ->with('error', 'Otentikasi Google dibatalkan atau ditolak: ' . $request->error);
        }

        if (!$request->has('code')) {
            return redirect()->route('admin.quick-expense.index')
                ->with('error', 'Kode otentikasi Google tidak ditemukan.');
        }

        $res = $this->gdriveService->handleOAuthCallback($request->code);

        if ($res['status']) {
            return redirect()->route('admin.quick-expense.index')
                ->with('success', '✓ Akun Google Drive (5 TB) berhasil dihubungkan! Kuota personal aktif untuk upload.');
        } else {
            return redirect()->route('admin.quick-expense.index')
                ->with('error', 'Gagal menghubungkan Google Drive: ' . $res['message']);
        }
    }

    /**
     * Simpan Pengaturan Google Drive & Uji Koneksi
     */
    public function saveGdriveSettings(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|string',
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'credentials_file' => 'nullable|file|mimes:json|max:2048',
        ]);

        $folderId = trim($request->folder_id);
        $envData = ['GOOGLE_DRIVE_FOLDER_ID' => $folderId];

        if ($request->filled('client_id')) {
            $envData['GOOGLE_DRIVE_CLIENT_ID'] = trim($request->client_id);
        }
        if ($request->filled('client_secret')) {
            $envData['GOOGLE_DRIVE_CLIENT_SECRET'] = trim($request->client_secret);
        }

        // Update .env atau config
        $this->updateEnv($envData);

        if ($request->hasFile('credentials_file')) {
            $destPath = storage_path('app/google-drive-credentials.json');
            $request->file('credentials_file')->move(dirname($destPath), basename($destPath));
        }

        // Test koneksi setelah update
        $gdrive = new GoogleDriveService();
        $testResult = $gdrive->testConnection();

        if ($testResult['status']) {
            return back()->with('success', $testResult['message']);
        } else {
            return back()->with('error', $testResult['message']);
        }
    }

    /**
     * Membentuk Chips Preset Pengeluaran Populer dari Daftar COA
     */
    private function buildQuickExpensePresets($akunBeban)
    {
        $presetKeywords = [
            ['label' => 'BBM / Transport', 'keywords' => ['transport', 'bensin', 'bbm', 'kendaraan', 'parkir', 'tol'], 'color' => 'amber'],
            ['label' => 'Konsumsi & Makan', 'keywords' => ['konsumsi', 'makan', 'minum', 'snack', 'dapur'], 'color' => 'emerald'],
            ['label' => 'ATK & Perlengkapan', 'keywords' => ['atk', 'alat tulis', 'kertas', 'perlengkapan', 'cetak'], 'color' => 'sky'],
            ['label' => 'Listrik & Internet', 'keywords' => ['listrik', 'pln', 'internet', 'wifi', 'pulsa', 'telepon', 'air'], 'color' => 'indigo'],
            ['label' => 'Beban Operasional', 'keywords' => ['operasional', 'ops', 'umum', 'lain'], 'color' => 'purple'],
            ['label' => 'Service & Maintenance', 'keywords' => ['pemeliharaan', 'perbaikan', 'service', 'renovasi', 'bengkel'], 'color' => 'rose'],
        ];

        $matchedPresets = [];

        foreach ($presetKeywords as $item) {
            $matchedAkun = null;
            foreach ($akunBeban as $akun) {
                $accountNameLower = strtolower($akun->name);
                foreach ($item['keywords'] as $kw) {
                    if (str_contains($accountNameLower, $kw)) {
                        $matchedAkun = $akun;
                        break 2;
                    }
                }
            }

            // Jika tidak ditemukan keyword spesifik, gunakan akun pertama yang belum dipakai
            if (!$matchedAkun && $akunBeban->isNotEmpty()) {
                $matchedAkun = $akunBeban->first();
            }

            if ($matchedAkun) {
                $matchedPresets[] = [
                    'label' => $item['label'],
                    'akun_id' => $matchedAkun->id,
                    'kode' => $matchedAkun->kode ?: 'COA',
                    'akun_name' => $matchedAkun->name,
                    'color' => $item['color'],
                ];
            }
        }

        return $matchedPresets;
    }

    private function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            return;
        }

        $content = File::get($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}=\"{$value}\"", $content);
            } else {
                $content .= "\n{$key}=\"{$value}\"";
            }
        }

        File::put($envPath, $content);
    }
}
