<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Transaksi;
use App\Models\JurnalUmum;
use App\Models\Usaha;
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

        $bulanIni = Carbon::now()->format('Y-m');
        $tanggalAwalBulan = Carbon::now()->startOfMonth();
        $tanggalAkhirBulan = Carbon::now()->endOfMonth();

        $labaRugiBersih = $this->hitungLabaRugiBersih($tanggalAwalBulan, $tanggalAkhirBulan, $usahaSelected);
        $arusKasBersih = $this->hitungArusKasBersih($tanggalAwalBulan, $tanggalAkhirBulan, $usahaSelected);
        $totalAsetLancar = $this->hitungTotalAsetLancar($usahaSelected);
        $totalKewajibanLancar = $this->hitungTotalKewajibanLancar($usahaSelected);

        $saldoKas = $this->getSaldoAkunByKlasifikasi('KAS', $usahaSelected);
        $saldoBank = $this->getSaldoAkunByKlasifikasi('BANK', $usahaSelected);
        $piutangBelumTertagih = $this->getSaldoAkunByKlasifikasi('PIUTANG', $usahaSelected);
        $utangBelumTerbayar = $this->getSaldoAkunByKlasifikasi('UTANG', $usahaSelected);

        $grafikArusKas = $this->getGrafikArusKasBulanan($usahaSelected);
        $pieChartBeban = $this->getPieChartBeban($tanggalAwalBulan, $tanggalAkhirBulan, $usahaSelected);

        $penerimaanTerakhir = $this->getPenerimaanTerakhir($usahaSelected);
        $pengeluaranTerakhir = $this->getPengeluaranTerakhir($usahaSelected);

        return view('admin.dashboard', compact(
            'labaRugiBersih',
            'arusKasBersih',
            'totalAsetLancar',
            'totalKewajibanLancar',
            'saldoKas',
            'saldoBank',
            'piutangBelumTertagih',
            'utangBelumTerbayar',
            'grafikArusKas',
            'pieChartBeban',
            'penerimaanTerakhir',
            'pengeluaranTerakhir',
            'usahas',
            'usahaSelected'
        ));
    }

    private function hitungLabaRugiBersih($tanggalAwal, $tanggalAkhir, $usahaId)
    {
        $query = JurnalUmum::whereHas('akun', function($q) {
            $q->where('klasifikasi', 'PENDAPATAN');
        })
        ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        $pendapatan = $query->sum('kredit');

        $query = JurnalUmum::whereHas('akun', function($q) {
            $q->where('klasifikasi', 'BEBAN');
        })
        ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        $beban = $query->sum('debit');

        return $pendapatan - $beban;
    }

    private function hitungArusKasBersih($tanggalAwal, $tanggalAkhir, $usahaId)
    {
        $query = Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENERIMAAN');
        })
        ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        $penerimaan = $query->sum('jumlah');

        $query = Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENGELUARAN');
        })
        ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        $pengeluaran = $query->sum('jumlah');

        return $penerimaan - $pengeluaran;
    }

    private function hitungTotalAsetLancar($usahaId)
    {
        $query = Akun::where('klasifikasi', 'ASET')
            ->where('nama_kelompok', 'LANCAR');

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        return $query->sum('saldo');
    }

    private function hitungTotalKewajibanLancar($usahaId)
    {
        $query = Akun::where('klasifikasi', 'KEWAJIBAN')
            ->where('nama_kelompok', 'LANCAR');

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        return $query->sum('saldo');
    }

    private function getSaldoAkunByKlasifikasi($klasifikasi, $usahaId)
    {
        $query = Akun::where('klasifikasi', $klasifikasi);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        return $query->sum('saldo');
    }

    private function getGrafikArusKasBulanan($usahaId)
    {
        $bulanSekarang = Carbon::now();
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $awalBulan = $bulan->copy()->startOfMonth();
            $akhirBulan = $bulan->copy()->endOfMonth();

            $query = Transaksi::whereHas('label', function($q) {
                $q->where('tipe_utama', 'PENERIMAAN');
            })
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan]);

            if ($usahaId) {
                $query->where('usaha_id', $usahaId);
            }

            $penerimaan = $query->sum('jumlah');

            $query = Transaksi::whereHas('label', function($q) {
                $q->where('tipe_utama', 'PENGELUARAN');
            })
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan]);

            if ($usahaId) {
                $query->where('usaha_id', $usahaId);
            }

            $pengeluaran = $query->sum('jumlah');

            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'penerimaan' => $penerimaan,
                'pengeluaran' => $pengeluaran
            ];
        }

        return $data;
    }

    private function getPieChartBeban($tanggalAwal, $tanggalAkhir, $usahaId)
    {
        $query = JurnalUmum::whereHas('akun', function($q) {
            $q->where('klasifikasi', 'BEBAN');
        })
        ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        return $query->select('akun_id', DB::raw('SUM(debit) as total'))
            ->with('akun')
            ->groupBy('akun_id')
            ->get();
    }

    private function getPenerimaanTerakhir($usahaId)
    {
        $query = Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENERIMAAN');
        })
        ->with(['akunPayment', 'label']);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        return $query->latest()
            ->take(5)
            ->get();
    }

    private function getPengeluaranTerakhir($usahaId)
    {
        $query = Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENGELUARAN');
        })
        ->with(['akunPayment', 'label']);

        if ($usahaId) {
            $query->where('usaha_id', $usahaId);
        }

        return $query->latest()
            ->take(5)
            ->get();
    }
}