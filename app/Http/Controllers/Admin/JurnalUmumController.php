<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use App\Models\JurnalPenyesuaian;
use App\Models\AturanAutomation;
use App\Models\LabelTransaksi;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        // QUERY UNTUK PAGINATION (DENGAN FILTER)
        $query = JurnalUmum::with('akun');

        // QUERY UNTUK TOTAL SELURUH (DENGAN FILTER YANG SAMA)
        $queryForTotal = JurnalUmum::query();

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
            $queryForTotal->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
                $queryForTotal->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
                $queryForTotal->where('usaha_id', 0);
            }
        }

        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->where('tanggal_transaksi', '>=', $request->tanggal_mulai);
            $queryForTotal->where('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai) {
            $query->where('tanggal_transaksi', '<=', $request->tanggal_selesai);
            $queryForTotal->where('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->has('akun_id') && $request->akun_id) {
            $query->where('akun_id', $request->akun_id);
            $queryForTotal->where('akun_id', $request->akun_id);
        }

        if ($request->has('deskripsi') && $request->deskripsi) {
            $query->where('deskripsi', 'like', '%' . $request->deskripsi . '%');
            $queryForTotal->where('deskripsi', 'like', '%' . $request->deskripsi . '%');
        }

        // DEFAULT SORTING (Terbaru ke Terlama)
        $sortOrder = $request->get('sort_order', 'desc'); // 'asc' untuk terlama ke terbaru, 'desc' untuk terbaru ke terlama
        $sortBy = $request->get('sort_by', 'tanggal_transaksi'); // default sort by tanggal

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);
        if ($sortBy === 'tanggal_transaksi') {
            $query->orderBy('id', $sortOrder); // secondary sort by id untuk tanggal yang sama
        }

        // PAGINATION DENGAN APPEND PARAMETER FILTER
        $jurnalUmum = $query->paginate(50)->appends($request->except('page'));

        // TOTAL UNTUK HALAMAN SAAT INI
        $totalDebitPerHalaman = $jurnalUmum->sum('debit');
        $totalKreditPerHalaman = $jurnalUmum->sum('kredit');

        // TOTAL KESELURUHAN DENGAN FILTER
        $totalKeseluruhan = $queryForTotal->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')->first();
        $totalDebitKeseluruhan = $totalKeseluruhan->total_debit ?? 0;
        $totalKreditKeseluruhan = $totalKeseluruhan->total_kredit ?? 0;

        if ($usahaSelected) {
            $akuns = Akun::where('usaha_id', $usahaSelected)->get();
        } else {
            if ($usahas->count() > 0) {
                $akuns = Akun::whereIn('usaha_id', $usahas->pluck('id'))->get();
            } else {
                $akuns = collect();
            }
        }

        return view('admin.jurnal-umum.index', compact(
            'jurnalUmum',
            'totalDebitPerHalaman',
            'totalKreditPerHalaman',
            'totalDebitKeseluruhan',
            'totalKreditKeseluruhan',
            'akuns',
            'usahas',
            'usahaSelected',
            'sortOrder',
            'sortBy'
        ));
    }

    public function indexPenyesuaian(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

        $query = JurnalUmum::with('akun')->where('is_penyesuaian', true);

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            $query->whereIn('usaha_id', $usahas->pluck('id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        $jurnals = $query->orderBy('tanggal_transaksi', 'desc')->paginate(20);

        return view('admin.jurnal-penyesuaian.index', compact('jurnals', 'usahas', 'usahaSelected'));
    }

    public function createPenyesuaian(Request $request)
{
    $currentUser = Auth::user();
    $usahas = $currentUser->usahas()->get();
    $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

    $labelTransaksis = $usahaSelected
        ? AturanAutomation::with(['label', 'akunDebit', 'akunKredit'])
            ->where('usaha_id', $usahaSelected)
            ->get()
        : collect();

    return view('admin.jurnal-penyesuaian.create', compact('usahas', 'usahaSelected', 'labelTransaksis'));
}

public function storePenyesuaian(Request $request)
{
    $validated = $request->validate([
        'usaha_id'            => 'required|exists:usahas,id',
        'tanggal_transaksi'   => 'required|date',
        'keterangan'          => 'nullable|string|max:255',
        'baris'               => 'required|array|min:1',
        'baris.*.label_id'    => 'required|exists:label_transaksis,id',
        'baris.*.jumlah'      => 'required|numeric|min:0.01',
    ]);

    $currentUser = Auth::user();
    if (!$currentUser->usahas()->where('usahas.id', $validated['usaha_id'])->exists()) {
        return back()->with('error', 'Anda tidak memiliki akses ke usaha ini.')->withInput();
    }

    // Ambil semua aturan automation untuk label-label yang dipakai, sekaligus validasi ketersediaannya
    $labelIds = collect($validated['baris'])->pluck('label_id')->unique();
    $aturans = AturanAutomation::where('usaha_id', $validated['usaha_id'])
        ->whereIn('label_id', $labelIds)
        ->get()
        ->keyBy('label_id');

    foreach ($labelIds as $labelId) {
        if (!$aturans->has($labelId)) {
            $label = LabelTransaksi::find($labelId);
            return back()
                ->withErrors(['baris' => 'Label "' . ($label->nama_label ?? $labelId) . '" belum punya Aturan Automation. Silakan setup dulu di menu Aturan Automation.'])
                ->withInput();
        }
    }

    DB::transaction(function () use ($validated, $currentUser, $aturans) {
        $urutan = JurnalPenyesuaian::where('usaha_id', $validated['usaha_id'])
            ->whereDate('created_at', now())
            ->count() + 1;

        $noBukti = 'JP-' . now()->format('Ymd') . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        $header = JurnalPenyesuaian::create([
            'usaha_id'          => $validated['usaha_id'],
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'no_bukti'          => $noBukti,
            'keterangan'        => $validated['keterangan'],
            'created_by'        => $currentUser->id,
        ]);

        foreach ($validated['baris'] as $b) {
            $aturan = $aturans->get($b['label_id']);
            $deskripsi = ($validated['keterangan'] ?? $noBukti) . ' - ' . ($aturan->label->nama_label ?? '');

            // Baris Debit
            JurnalUmum::create([
                'usaha_id'                 => $validated['usaha_id'],
                'akun_id'                  => $aturan->akun_debit_id,
                'tanggal_transaksi'        => $validated['tanggal_transaksi'],
                'deskripsi'                => $deskripsi,
                'debit'                    => $b['jumlah'],
                'kredit'                   => 0,
                'referensi_transaksi_id'   => $header->id,
                'referensi_transaksi_tipe' => JurnalPenyesuaian::class,
                'is_penyesuaian'           => true,
            ]);

            // Baris Kredit
            JurnalUmum::create([
                'usaha_id'                 => $validated['usaha_id'],
                'akun_id'                  => $aturan->akun_kredit_id,
                'tanggal_transaksi'        => $validated['tanggal_transaksi'],
                'deskripsi'                => $deskripsi,
                'debit'                    => 0,
                'kredit'                   => $b['jumlah'],
                'referensi_transaksi_id'   => $header->id,
                'referensi_transaksi_tipe' => JurnalPenyesuaian::class,
                'is_penyesuaian'           => true,
            ]);
        }
    });

    return redirect()->route('admin.jurnal-penyesuaian', ['usaha_id' => $validated['usaha_id']])
        ->with('success', 'Jurnal penyesuaian berhasil disimpan.');
}

public function destroyPenyesuaian($id)
{
    $header = JurnalPenyesuaian::findOrFail($id);
    $currentUser = Auth::user();

    if (!$currentUser->usahas()->where('usahas.id', $header->usaha_id)->exists()) {
        return back()->with('error', 'Anda tidak memiliki akses ke data ini.');
    }

    DB::transaction(function () use ($header) {
        JurnalUmum::where('referensi_transaksi_id', $header->id)
            ->where('referensi_transaksi_tipe', JurnalPenyesuaian::class)
            ->delete();

        $header->delete();
    });

    return back()->with('success', 'Jurnal penyesuaian dan seluruh barisnya berhasil dihapus.');
}

    public function updateToAdjustment(Request $request, $id)
    {
        $request->validate([
            'akun_id' => 'required',
            'tanggal_transaksi' => 'required|date',
            'debit' => 'required|numeric',
            'kredit' => 'required|numeric',
        ]);

        $jurnal = JurnalUmum::findOrFail($id);

        DB::transaction(function () use ($request, $jurnal) {
            $jurnal->update([
                'akun_id' => $request->akun_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'deskripsi' => $request->deskripsi,
                'debit' => $request->debit,
                'kredit' => $request->kredit,
                'is_penyesuaian' => true,
            ]);
        });

        return back()->with('success', 'Jurnal berhasil diubah menjadi Jurnal Penyesuaian.');
    }

    public function importForm(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);
        $akuns = $usahaSelected ? Akun::where('usaha_id', $usahaSelected)->orderBy('kode')->get() : collect();

        return view('admin.jurnal-umum.import', compact('usahas', 'usahaSelected', 'akuns'));
    }

    public function importPreview(Request $request)
    {
        $request->validate([
            'file'     => 'required|file|mimes:xlsx,xls',
            'usaha_id' => 'required|exists:usahas,id',
            'bulan'    => 'required|integer|min:1|max:12',
            'tahun'    => 'required|integer|min:2000|max:2100',
        ]);

        $currentUser = Auth::user();
        $usahaSelected = $request->usaha_id;
        $usahas = $currentUser->usahas()->get();
        $akuns = Akun::where('usaha_id', $usahaSelected)->orderBy('kode')->get();

        $path = $request->file('file')->store('imports');
        $fullPath = Storage::path($path);

        $parsed = $this->parseExcelJurnal($fullPath, (int) $request->tahun);

        $bulanDipilih = (int) $request->bulan;
        $tahunDipilih = (int) $request->tahun;

        $parsed = array_values(array_filter($parsed, function ($entri) use ($bulanDipilih, $tahunDipilih) {
            $tanggal = Carbon::parse($entri['tanggal']);
            return $tanggal->month === $bulanDipilih && $tanggal->year === $tahunDipilih;
        }));

        Session::put('import_file_path', $path);
        Session::put('import_usaha_id', $usahaSelected);
        Session::put('import_parsed', $parsed);

        $namaAkunUnik = collect($parsed)
            ->flatMap(fn($e) => [$e['nama_debit'], $e['nama_kredit']])
            ->filter()
            ->map(fn($n) => trim($n))
            ->unique()
            ->sort()
            ->values();

        return view('admin.jurnal-umum.import-mapping', compact(
            'parsed',
            'namaAkunUnik',
            'akuns',
            'usahas',
            'usahaSelected'
        ));
    }

    public function importExecute(Request $request)
    {
        $usahaId = Session::get('import_usaha_id');
        $parsed  = Session::get('import_parsed');

        if (!$usahaId || !$parsed) {
            return redirect()->route('admin.jurnal-umum.import.form')->with('error', 'Sesi import kedaluwarsa. Silakan upload ulang.');
        }

        $mapping = $request->input('mapping', []);

        $errors = [];
        foreach ($mapping as $namaAkun => $akunId) {
            if (!$akunId) {
                $errors[] = "Akun \"$namaAkun\" belum dipetakan.";
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return redirect()->route('admin.jurnal-umum.import.form')->with('error', 'Akses ditolak.');
        }

        DB::transaction(function () use ($parsed, $mapping, $usahaId) {
            foreach ($parsed as $entri) {
                $namaDebit  = trim($entri['nama_debit'] ?? '');
                $namaKredit = trim($entri['nama_kredit'] ?? '');
                $akunDebitId  = $mapping[$namaDebit] ?? null;
                $akunKreditId = $mapping[$namaKredit] ?? null;

                if (!$akunDebitId || !$akunKreditId || !$entri['jumlah']) continue;

                JurnalUmum::create([
                    'usaha_id'          => $usahaId,
                    'akun_id'           => $akunDebitId,
                    'tanggal_transaksi' => $entri['tanggal'],
                    'deskripsi'         => $entri['keterangan'],
                    'debit'             => $entri['jumlah'],
                    'kredit'            => 0,
                ]);

                JurnalUmum::create([
                    'usaha_id'          => $usahaId,
                    'akun_id'           => $akunKreditId,
                    'tanggal_transaksi' => $entri['tanggal'],
                    'deskripsi'         => $entri['keterangan'],
                    'debit'             => 0,
                    'kredit'            => $entri['jumlah'],
                ]);
            }
        });

        Session::forget(['import_file_path', 'import_usaha_id', 'import_parsed']);

        return redirect()->route('admin.laporan.jurnal_umum', ['usaha_id' => $usahaId])
            ->with('success', 'Import jurnal berhasil. ' . count($parsed) . ' entri diimpor.');
    }

    private function parseExcelJurnal(string $filePath, ?int $tahun = null): array
    {
        $wb = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $ws = $wb->getActiveSheet();
        $rows = $ws->toArray(null, true, false, false);

        $entries = [];
        $currentBulan = null;
        $currentTahun = $tahun ?? (int) date('Y');

        $bulanMap = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $headerPassed = false;

        for ($i = 0; $i < count($rows); $i++) {
            $row = $rows[$i];
            $col0 = trim((string)($row[0] ?? ''));
            $col2 = trim((string)($row[2] ?? ''));
            $col3 = trim((string)($row[3] ?? ''));
            $colF = $row[5] ?? null;
            $colG = $row[6] ?? null;

            if (!$headerPassed) {
                if (strtolower($col0) === 'tanggal') {
                    $headerPassed = true;
                }
                continue;
            }

            $colLower = strtolower($col0);
            if (isset($bulanMap[$colLower])) {
                $currentBulan = $bulanMap[$colLower];
                $tanggal = sprintf('%04d-%02d-01', $currentTahun, $currentBulan);

                $namaDebit = $col2 ?: null;
                $jumlah = is_numeric($colF) ? (float)$colF : null;

                if ($namaDebit && $jumlah) {
                    $nextRow = $rows[$i + 1] ?? [];
                    $namaKredit = trim((string)($nextRow[3] ?? ''));

                    if ($namaDebit || $namaKredit) {
                        $entries[] = [
                            'tanggal'     => $tanggal,
                            'keterangan'  => $namaDebit,
                            'nama_debit'  => $namaDebit,
                            'nama_kredit' => $namaKredit ?: null,
                            'jumlah'      => $jumlah,
                        ];
                        $i++;
                    }
                }
                continue;
            }

            $namaDebit = $col2 ?: null;
            $jumlah = is_numeric($colF) ? (float)$colF : null;

            if (!$namaDebit && !$jumlah) continue;

            if ($currentBulan) {
                $tanggal = sprintf('%04d-%02d-01', $currentTahun, $currentBulan);
            } else {
                $tanggal = date('Y-m-d');
            }

            $nextRow = $rows[$i + 1] ?? [];
            $namaKredit = trim((string)($nextRow[3] ?? ''));

            if (($namaDebit || $jumlah) && $namaKredit) {
                $entries[] = [
                    'tanggal'     => $tanggal,
                    'keterangan'  => $namaDebit ?: 'Import',
                    'nama_debit'  => $namaDebit,
                    'nama_kredit' => $namaKredit,
                    'jumlah'      => $jumlah,
                ];
                $i++;
            }
        }

        return $entries;
    }
}
