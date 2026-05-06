<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Transaksi;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');
        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        $tanggalAwalBulan = Carbon::now()->startOfMonth();
        $tanggalAkhirBulan = Carbon::now()->endOfMonth();
        $tanggalAwalTahun = Carbon::now()->startOfYear();
        $tanggalAkhirTahun = Carbon::now()->endOfYear();

        $labaRugiBersih       = $this->hitungLabaRugiBersih($tanggalAwalBulan, $tanggalAkhirBulan, $usahaSelected);
        $arusKasBersih        = $this->hitungArusKasBersih($tanggalAwalBulan, $tanggalAkhirBulan, $usahaSelected);
        $totalAsetLancar      = $this->hitungTotalAsetLancar($usahaSelected);
        $totalKewajibanLancar = $this->hitungTotalKewajibanLancar($usahaSelected);
        $saldoKas             = $this->hitungSaldoKas($usahaSelected);
        $saldoBank            = $this->hitungSaldoBank($usahaSelected);
        $piutangBelumTertagih = $this->hitungPiutang($usahaSelected);
        $utangBelumTerbayar   = $this->hitungUtang($usahaSelected);
        $grafikArusKas        = $this->getGrafikArusKasBulanan($usahaSelected);
        $pieChartBeban        = $this->getPieChartBeban($tanggalAwalBulan, $tanggalAkhirBulan, $usahaSelected);
        $penerimaanTerakhir   = $this->getPenerimaanTerakhir($usahaSelected);
        $pengeluaranTerakhir  = $this->getPengeluaranTerakhir($usahaSelected);

        return view('admin.dashboard', compact(
            'labaRugiBersih', 'arusKasBersih', 'totalAsetLancar', 'totalKewajibanLancar',
            'saldoKas', 'saldoBank', 'piutangBelumTertagih', 'utangBelumTerbayar',
            'grafikArusKas', 'pieChartBeban', 'penerimaanTerakhir', 'pengeluaranTerakhir',
            'usahas', 'usahaSelected'
        ));
    }

    private function hitungSaldoDariJurnal($usahaId, array $kondisiAkun): float
    {
        $akuns = Akun::where('usaha_id', $usahaId);
        foreach ($kondisiAkun as $key => $val) {
            if (is_array($val)) {
                $akuns->whereIn($key, $val);
            } else {
                $akuns->where($key, $val);
            }
        }
        $akunIds = $akuns->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_debit ?? 0) - ($result->total_kredit ?? 0);
    }

    private function hitungLabaRugiBersih($tanggalAwal, $tanggalAkhir, $usahaId): float
    {
        if (!$usahaId) return 0;

        $mutasi = JurnalUmum::selectRaw('
            akuns.klasifikasi,
            SUM(jurnal_umum.debit) as total_debit,
            SUM(jurnal_umum.kredit) as total_kredit
        ')
            ->join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
            ->where('jurnal_umum.usaha_id', $usahaId)
            ->whereIn('akuns.klasifikasi', ['PENDAPATAN', 'BEBAN'])
            ->whereBetween('jurnal_umum.tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('akuns.klasifikasi')
            ->get()->keyBy('klasifikasi');

        $p = $mutasi->get('PENDAPATAN');
        $b = $mutasi->get('BEBAN');

        $pendapatan = $p ? ($p->total_kredit - $p->total_debit) : 0;
        $beban      = $b ? ($b->total_debit - $b->total_kredit) : 0;

        return $pendapatan - $beban;
    }

    private function hitungArusKasBersih($tanggalAwal, $tanggalAkhir, $usahaId): float
    {
        if (!$usahaId) return 0;

        $akunKasBank = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->where(function ($q) {
                $q->where('name', 'like', '%kas%')
                    ->orWhere('name', 'like', '%bank%')
                    ->orWhere('name', 'like', '%bca%')
                    ->orWhere('name', 'like', '%bri%')
                    ->orWhere('name', 'like', '%mandiri%');
            })->pluck('id');

        if ($akunKasBank->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as masuk, SUM(kredit) as keluar')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunKasBank)
            ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->where('deskripsi', 'not like', '%Saldo Awal%')
            ->first();

        return ($result->masuk ?? 0) - ($result->keluar ?? 0);
    }

    private function hitungTotalAsetLancar($usahaId): float
    {
        if (!$usahaId) return 0;

        $akunIds = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'LANCAR')
            ->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_debit ?? 0) - ($result->total_kredit ?? 0);
    }

    private function hitungTotalKewajibanLancar($usahaId): float
    {
        if (!$usahaId) return 0;

        $akunIds = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'KEWAJIBAN')
            ->where('sub_klasifikasi', 'LANCAR')
            ->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_kredit ?? 0) - ($result->total_debit ?? 0);
    }

    private function hitungSaldoKas($usahaId): float
    {
        if (!$usahaId) return 0;

        $akunIds = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->where(function ($q) {
                $q->where('name', 'like', '%kas%')
                    ->where('name', 'not like', '%bank%');
            })->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_debit ?? 0) - ($result->total_kredit ?? 0);
    }

    private function hitungSaldoBank($usahaId): float
    {
        if (!$usahaId) return 0;

        $akunIds = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->where(function ($q) {
                $q->where('name', 'like', '%bank%')
                    ->orWhere('name', 'like', '%bca%')
                    ->orWhere('name', 'like', '%bri%')
                    ->orWhere('name', 'like', '%mandiri%')
                    ->orWhere('name', 'like', '%bni%')
                    ->orWhere('name', 'like', '%cimb%');
            })->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_debit ?? 0) - ($result->total_kredit ?? 0);
    }

    private function hitungPiutang($usahaId): float
    {
        if (!$usahaId) return 0;

        $akunIds = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->where('name', 'like', '%piutang%')
            ->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_debit ?? 0) - ($result->total_kredit ?? 0);
    }

    private function hitungUtang($usahaId): float
    {
        if (!$usahaId) return 0;

        $akunIds = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'KEWAJIBAN')
            ->where(function ($q) {
                $q->where('name', 'like', '%utang%')
                    ->orWhere('name', 'like', '%hutang%');
            })->pluck('id');

        if ($akunIds->isEmpty()) return 0;

        $result = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunIds)
            ->first();

        return ($result->total_kredit ?? 0) - ($result->total_debit ?? 0);
    }

    private function getGrafikArusKasBulanan($usahaId): array
    {
        if (!$usahaId) return [];

        $akunKasBank = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->where(function ($q) {
                $q->where('name', 'like', '%kas%')
                    ->orWhere('name', 'like', '%bank%')
                    ->orWhere('name', 'like', '%bca%')
                    ->orWhere('name', 'like', '%bri%')
                    ->orWhere('name', 'like', '%mandiri%');
            })->pluck('id');

        $data = [];
        $bulanSekarang = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $awal = $bulan->copy()->startOfMonth();
            $akhir = $bulan->copy()->endOfMonth();

            if ($akunKasBank->isEmpty()) {
                $penerimaan = 0;
                $pengeluaran = 0;
            } else {
                $result = JurnalUmum::selectRaw('SUM(debit) as masuk, SUM(kredit) as keluar')
                    ->where('usaha_id', $usahaId)
                    ->whereIn('akun_id', $akunKasBank)
                    ->whereBetween('tanggal_transaksi', [$awal, $akhir])
                    ->where('deskripsi', 'not like', '%Saldo Awal%')
                    ->first();

                $penerimaan = $result->masuk ?? 0;
                $pengeluaran = $result->keluar ?? 0;
            }

            $data[] = [
                'bulan'       => $bulan->format('M Y'),
                'penerimaan'  => $penerimaan,
                'pengeluaran' => $pengeluaran,
            ];
        }

        return $data;
    }

    private function getPieChartBeban($tanggalAwal, $tanggalAkhir, $usahaId)
    {
        if (!$usahaId) return collect();

        return JurnalUmum::selectRaw('jurnal_umum.akun_id, SUM(jurnal_umum.debit) - SUM(jurnal_umum.kredit) as total')
            ->join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
            ->where('jurnal_umum.usaha_id', $usahaId)
            ->where('akuns.klasifikasi', 'BEBAN')
            ->whereBetween('jurnal_umum.tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('jurnal_umum.akun_id')
            ->having('total', '>', 0)
            ->with('akun')
            ->get();
    }

    private function getPenerimaanTerakhir($usahaId)
    {
        if (!$usahaId) return collect();

        return Transaksi::whereHas('label', fn($q) => $q->where('tipe_utama', 'PENERIMAAN'))
            ->where('usaha_id', $usahaId)
            ->with(['akunPayment', 'label'])
            ->latest('tanggal')
            ->take(5)
            ->get();
    }

    private function getPengeluaranTerakhir($usahaId)
    {
        if (!$usahaId) return collect();

        return Transaksi::whereHas('label', fn($q) => $q->where('tipe_utama', 'PENGELUARAN'))
            ->where('usaha_id', $usahaId)
            ->with(['akunPayment', 'label'])
            ->latest('tanggal')
            ->take(5)
            ->get();
    }
}
