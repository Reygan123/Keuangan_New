<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\Usaha;
use App\Services\Akuntansi\LaporanKeuanganService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected LaporanKeuanganService $laporanService
    ) {}

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = (int)$request->get('usaha_id');

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = (int)$usahas->first()->id;
        }

        $currentUsaha = $usahas->firstWhere('id', $usahaSelected);

        // Ambil daftar tahun unik yang memiliki transaksi untuk usaha terpilih
        $availableYears = [];
        if ($usahaSelected) {
            $availableYears = JurnalUmum::where('usaha_id', $usahaSelected)
                ->selectRaw('DISTINCT YEAR(tanggal_transaksi) as thn')
                ->orderByDesc('thn')
                ->pluck('thn')
                ->filter()
                ->values()
                ->toArray();
        }

        $now = Carbon::now();
        // Smart default year: Jika tahun ini ada transaksi gunakan tahun ini, jika tidak ada gunakan tahun transaksi terbaru
        $defaultYear = $now->year;
        if (!empty($availableYears) && !in_array($defaultYear, $availableYears)) {
            $defaultYear = (int)$availableYears[0];
        }

        // Filter Periode
        $mode = $request->get('mode', 'year'); // 'year', 'month', 'custom', 'all_time'
        $selectedYear = (int)$request->get('tahun', $defaultYear);
        $selectedMonth = $request->filled('bulan') ? (int)$request->get('bulan') : null;

        if ($mode === 'month' && $selectedMonth) {
            $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
            $periodeLabel = $startDate->translatedFormat('F Y');
        } elseif ($mode === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $periodeLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        } elseif ($mode === 'all_time') {
            $minDate = JurnalUmum::where('usaha_id', $usahaSelected)->min('tanggal_transaksi') ?? $now->toDateString();
            $maxDate = JurnalUmum::where('usaha_id', $usahaSelected)->max('tanggal_transaksi') ?? $now->toDateString();
            $startDate = Carbon::parse($minDate)->startOfDay();
            $endDate = Carbon::parse($maxDate)->endOfDay();
            $periodeLabel = 'Semua Periode (' . $startDate->format('Y') . ' - ' . $endDate->format('Y') . ')';
        } else {
            // Default: 1 Tahun Penuh
            $mode = 'year';
            $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfYear();
            $periodeLabel = 'Tahun Buku ' . $selectedYear;
        }

        $startDateStr = $startDate->toDateString();
        $endDateStr = $endDate->toDateString();

        // 1. Perhitungan Laba Rugi resmi
        $labaRugi = $this->hitungLabaRugi($startDate, $endDate, $usahaSelected);

        // 2. Perhitungan Posisi Neraca per tanggal akhir periode
        $neraca = $this->hitungNeraca($endDate, $usahaSelected);

        // 3. Perhitungan Arus Kas & Saldo Kas/Bank
        $arusKas = $this->hitungArusKas($startDate, $endDate, $usahaSelected);

        // 4. Visualisasi Grafik:
        // A. Tren Finansial Bulanan (Pendapatan vs Beban vs Laba Bersih)
        $grafikTren = $this->getGrafikTrenBulanan($usahaSelected, $startDate, $endDate, $mode, $selectedYear);

        // B. Tren Arus Kas Bulanan (Kas Masuk vs Kas Keluar)
        $grafikArusKas = $this->getGrafikArusKasBulanan($usahaSelected, $startDate, $endDate, $mode, $selectedYear);

        // C. Komposisi Beban Terbesar (Top 6)
        $topBeban = $this->getTopBeban($startDate, $endDate, $usahaSelected);

        // D. Komposisi Sumber Pendapatan Terbesar (Top 6)
        $topPendapatan = $this->getTopPendapatan($startDate, $endDate, $usahaSelected);

        // E. Rasio Keuangan & Profitabilitas
        $rasioKeuangan = $this->hitungRasioKeuangan($labaRugi, $neraca);

        // 5. Aktivitas Jurnal Umum Terbaru (8 entri terbaru)
        $jurnalTerakhir = $this->getJurnalTerakhir($usahaSelected);

        return view('admin.dashboard', compact(
            'usahas',
            'usahaSelected',
            'currentUsaha',
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'mode',
            'periodeLabel',
            'startDateStr',
            'endDateStr',
            'labaRugi',
            'neraca',
            'arusKas',
            'grafikTren',
            'grafikArusKas',
            'topBeban',
            'topPendapatan',
            'rasioKeuangan',
            'jurnalTerakhir'
        ));
    }

    /**
     * Hitung Hasil Laba Rugi via LaporanKeuanganService
     */
    private function hitungLabaRugi($startDate, $endDate, $usahaId): array
    {
        return $this->laporanService->hitungLabaRugi((int)$usahaId, $startDate, $endDate);
    }

    /**
     * Hitung Posisi Neraca via LaporanKeuanganService
     */
    private function hitungNeraca($asOfDate, $usahaId): array
    {
        return $this->laporanService->hitungNeraca((int)$usahaId, $asOfDate);
    }

    /**
     * Hitung Arus Kas & Saldo Kas/Bank via LaporanKeuanganService
     */
    private function hitungArusKas($startDate, $endDate, $usahaId): array
    {
        return $this->laporanService->hitungArusKas((int)$usahaId, $startDate, $endDate);
    }

    /**
     * Tren Finansial Bulanan (Pendapatan vs Beban vs Laba Bersih) yang sinkron dengan periode
     */
    private function getGrafikTrenBulanan($usahaId, Carbon $startDate, Carbon $endDate, string $mode, int $selectedYear): array
    {
        if (!$usahaId) return [];

        $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        if ($mode === 'year') {
            // Tampilkan 12 bulan penuh dari tahun terpilih
            $mutasi = JurnalUmum::selectRaw('
                MONTH(tanggal_transaksi) as bulan,
                akuns.klasifikasi,
                SUM(jurnal_umum.debit) as total_debit,
                SUM(jurnal_umum.kredit) as total_kredit
            ')
                ->join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
                ->where('jurnal_umum.usaha_id', $usahaId)
                ->whereIn('akuns.klasifikasi', ['PENDAPATAN', 'BEBAN'])
                ->whereYear('jurnal_umum.tanggal_transaksi', $selectedYear)
                ->groupBy(\Illuminate\Support\Facades\DB::raw('MONTH(tanggal_transaksi)'), 'akuns.klasifikasi')
                ->get();

            $grouped = [];
            foreach ($mutasi as $row) {
                $grouped[$row->bulan][$row->klasifikasi] = $row;
            }

            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $p = $grouped[$m]['PENDAPATAN'] ?? null;
                $b = $grouped[$m]['BEBAN'] ?? null;

                $pendapatan = $p ? max(0, (float)($p->total_kredit - $p->total_debit)) : 0;
                $beban      = $b ? max(0, (float)($b->total_debit - $b->total_kredit)) : 0;
                $labaBersih = $pendapatan - $beban;

                $data[] = [
                    'bulan'       => $bulanNames[$m - 1],
                    'pendapatan'  => $pendapatan,
                    'beban'       => $beban,
                    'laba_bersih' => $labaBersih,
                ];
            }
            return $data;
        }

        // Mode custom atau bulan: Ambil mutasi per bulan sepanjang rentang
        $data = [];
        $cursor = $startDate->copy()->startOfMonth();
        $endCursor = $endDate->copy()->startOfMonth();

        // Jika rentang > 12 bulan, batasi 12 bulan terakhir
        if ($cursor->diffInMonths($endCursor) > 12) {
            $cursor = $endCursor->copy()->subMonths(11);
        }

        while ($cursor <= $endCursor) {
            $awalBulan = $cursor->copy()->startOfMonth();
            $akhirBulan = $cursor->copy()->endOfMonth();

            $mutasi = JurnalUmum::selectRaw('
                akuns.klasifikasi,
                SUM(jurnal_umum.debit) as total_debit,
                SUM(jurnal_umum.kredit) as total_kredit
            ')
                ->join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
                ->where('jurnal_umum.usaha_id', $usahaId)
                ->whereIn('akuns.klasifikasi', ['PENDAPATAN', 'BEBAN'])
                ->whereBetween('jurnal_umum.tanggal_transaksi', [$awalBulan, $akhirBulan])
                ->groupBy('akuns.klasifikasi')
                ->get()
                ->keyBy('klasifikasi');

            $p = $mutasi->get('PENDAPATAN');
            $b = $mutasi->get('BEBAN');

            $pendapatan = $p ? max(0, (float)($p->total_kredit - $p->total_debit)) : 0;
            $beban      = $b ? max(0, (float)($b->total_debit - $b->total_kredit)) : 0;
            $labaBersih = $pendapatan - $beban;

            $data[] = [
                'bulan'       => $cursor->translatedFormat('M y'),
                'pendapatan'  => $pendapatan,
                'beban'       => $beban,
                'laba_bersih' => $labaBersih,
            ];

            $cursor->addMonth();
        }

        return $data;
    }

    /**
     * Tren Arus Kas Bulanan (Kas Masuk vs Kas Keluar) yang sinkron dengan periode
     */
    private function getGrafikArusKasBulanan($usahaId, Carbon $startDate, Carbon $endDate, string $mode, int $selectedYear): array
    {
        if (!$usahaId) return [];

        $akunKasBank = $this->laporanService->getAkunKasBank($usahaId)->pluck('id');
        if ($akunKasBank->isEmpty()) {
            return [];
        }

        $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        if ($mode === 'year') {
            $arusKas = JurnalUmum::selectRaw('
                MONTH(tanggal_transaksi) as bulan,
                SUM(debit) as masuk,
                SUM(kredit) as keluar
            ')
                ->where('usaha_id', $usahaId)
                ->whereIn('akun_id', $akunKasBank)
                ->whereYear('tanggal_transaksi', $selectedYear)
                ->where('deskripsi', 'not like', '%Saldo Awal%')
                ->groupBy(\Illuminate\Support\Facades\DB::raw('MONTH(tanggal_transaksi)'))
                ->get()
                ->keyBy('bulan');

            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $row = $arusKas->get($m);
                $masuk = $row ? (float)$row->masuk : 0;
                $keluar = $row ? (float)$row->keluar : 0;

                $data[] = [
                    'bulan'      => $bulanNames[$m - 1],
                    'kas_masuk'  => $masuk,
                    'kas_keluar' => $keluar,
                    'net_kas'    => $masuk - $keluar,
                ];
            }
            return $data;
        }

        // Mode custom atau bulan
        $data = [];
        $cursor = $startDate->copy()->startOfMonth();
        $endCursor = $endDate->copy()->startOfMonth();

        if ($cursor->diffInMonths($endCursor) > 12) {
            $cursor = $endCursor->copy()->subMonths(11);
        }

        while ($cursor <= $endCursor) {
            $awalBulan = $cursor->copy()->startOfMonth();
            $akhirBulan = $cursor->copy()->endOfMonth();

            $row = JurnalUmum::selectRaw('SUM(debit) as masuk, SUM(kredit) as keluar')
                ->where('usaha_id', $usahaId)
                ->whereIn('akun_id', $akunKasBank)
                ->whereBetween('tanggal_transaksi', [$awalBulan, $akhirBulan])
                ->where('deskripsi', 'not like', '%Saldo Awal%')
                ->first();

            $masuk = (float)($row->masuk ?? 0);
            $keluar = (float)($row->keluar ?? 0);

            $data[] = [
                'bulan'      => $cursor->translatedFormat('M y'),
                'kas_masuk'  => $masuk,
                'kas_keluar' => $keluar,
                'net_kas'    => $masuk - $keluar,
            ];

            $cursor->addMonth();
        }

        return $data;
    }

    /**
     * Top 6 Beban Terbesar dalam periode aktif
     */
    private function getTopBeban($startDate, $endDate, $usahaId)
    {
        if (!$usahaId) return collect();

        return JurnalUmum::selectRaw('jurnal_umum.akun_id, akuns.name, SUM(jurnal_umum.debit) - SUM(jurnal_umum.kredit) as total')
            ->join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
            ->where('jurnal_umum.usaha_id', $usahaId)
            ->where('akuns.klasifikasi', 'BEBAN')
            ->whereBetween('jurnal_umum.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('jurnal_umum.akun_id', 'akuns.name')
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->take(6)
            ->get();
    }

    /**
     * Top 6 Sumber Pendapatan Terbesar dalam periode aktif
     */
    private function getTopPendapatan($startDate, $endDate, $usahaId)
    {
        if (!$usahaId) return collect();

        return JurnalUmum::selectRaw('jurnal_umum.akun_id, akuns.name, SUM(jurnal_umum.kredit) - SUM(jurnal_umum.debit) as total')
            ->join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
            ->where('jurnal_umum.usaha_id', $usahaId)
            ->where('akuns.klasifikasi', 'PENDAPATAN')
            ->whereBetween('jurnal_umum.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('jurnal_umum.akun_id', 'akuns.name')
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->take(6)
            ->get();
    }

    /**
     * Hitung ringkasan rasio keuangan utama
     */
    private function hitungRasioKeuangan(array $labaRugi, array $neraca): array
    {
        $penjualan = (float)$labaRugi['penjualanBersih'];
        $totalPendapatan = (float)$labaRugi['totalPendapatan'];
        $labaBersih = (float)$labaRugi['labaBersih'];
        $totalAset = (float)$neraca['totalAset'];
        $totalKewajiban = (float)$neraca['totalKewajiban'];
        $totalEkuitas = (float)$neraca['totalEkuitas'];

        // Return on Assets (ROA)
        $roa = $totalAset > 0 ? round(($labaBersih / $totalAset) * 100, 2) : 0;

        // Return on Equity (ROE)
        $roe = $totalEkuitas > 0 ? round(($labaBersih / $totalEkuitas) * 100, 2) : 0;

        // Debt to Equity Ratio (DER)
        $der = $totalEkuitas > 0 ? round(($totalKewajiban / $totalEkuitas) * 100, 2) : ($totalKewajiban > 0 ? 999 : 0);

        // Operating Expense Ratio (Beban Operasional / Pendapatan)
        $oer = $totalPendapatan > 0 ? round(($labaRugi['totalBeban'] / $totalPendapatan) * 100, 2) : 0;

        return [
            'grossMargin' => $labaRugi['grossProfitMargin'],
            'netMargin'   => $labaRugi['netProfitMargin'],
            'currentRatio'=> $neraca['currentRatio'],
            'roa'         => $roa,
            'roe'         => $roe,
            'der'         => $der,
            'oer'         => $oer,
        ];
    }

    /**
     * Aktivitas 8 Jurnal Terakhir
     */
    private function getJurnalTerakhir($usahaId)
    {
        if (!$usahaId) return collect();

        return JurnalUmum::where('usaha_id', $usahaId)
            ->with('akun')
            ->latest('tanggal_transaksi')
            ->latest('id')
            ->take(8)
            ->get();
    }
}
