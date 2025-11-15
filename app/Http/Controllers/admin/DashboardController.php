<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Transaksi;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->format('Y-m');
        $tanggalAwalBulan = Carbon::now()->startOfMonth();
        $tanggalAkhirBulan = Carbon::now()->endOfMonth();

        $labaRugiBersih = $this->hitungLabaRugiBersih($tanggalAwalBulan, $tanggalAkhirBulan);
        $arusKasBersih = $this->hitungArusKasBersih($tanggalAwalBulan, $tanggalAkhirBulan);
        $totalAsetLancar = $this->hitungTotalAsetLancar();
        $totalKewajibanLancar = $this->hitungTotalKewajibanLancar();

        $saldoKas = $this->getSaldoAkunByKlasifikasi('KAS');
        $saldoBank = $this->getSaldoAkunByKlasifikasi('BANK');
        $piutangBelumTertagih = $this->getSaldoAkunByKlasifikasi('PIUTANG');
        $utangBelumTerbayar = $this->getSaldoAkunByKlasifikasi('UTANG');

        $grafikArusKas = $this->getGrafikArusKasBulanan();
        $pieChartBeban = $this->getPieChartBeban($tanggalAwalBulan, $tanggalAkhirBulan);

        $penerimaanTerakhir = $this->getPenerimaanTerakhir();
        $pengeluaranTerakhir = $this->getPengeluaranTerakhir();

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
            'pengeluaranTerakhir'
        ));
    }

    private function hitungLabaRugiBersih($tanggalAwal, $tanggalAkhir)
    {
        $pendapatan = JurnalUmum::whereHas('akun', function($q) {
            $q->where('klasifikasi', 'PENDAPATAN');
        })
        ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
        ->sum('kredit');

        $beban = JurnalUmum::whereHas('akun', function($q) {
            $q->where('klasifikasi', 'BEBAN');
        })
        ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
        ->sum('debit');

        return $pendapatan - $beban;
    }

    private function hitungArusKasBersih($tanggalAwal, $tanggalAkhir)
    {
        $penerimaan = Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENERIMAAN');
        })
        ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
        ->sum('jumlah');

        $pengeluaran = Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENGELUARAN');
        })
        ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
        ->sum('jumlah');

        return $penerimaan - $pengeluaran;
    }

    private function hitungTotalAsetLancar()
    {
        return Akun::where('klasifikasi', 'ASET')
            ->where('nama_kelompok', 'LANCAR')
            ->sum('saldo');
    }

    private function hitungTotalKewajibanLancar()
    {
        return Akun::where('klasifikasi', 'KEWAJIBAN')
            ->where('nama_kelompok', 'LANCAR')
            ->sum('saldo');
    }

    private function getSaldoAkunByKlasifikasi($klasifikasi)
    {
        return Akun::where('klasifikasi', $klasifikasi)->sum('saldo');
    }

    private function getGrafikArusKasBulanan()
    {
        $bulanSekarang = Carbon::now();
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $awalBulan = $bulan->copy()->startOfMonth();
            $akhirBulan = $bulan->copy()->endOfMonth();

            $penerimaan = Transaksi::whereHas('label', function($q) {
                $q->where('tipe_utama', 'PENERIMAAN');
            })
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->sum('jumlah');

            $pengeluaran = Transaksi::whereHas('label', function($q) {
                $q->where('tipe_utama', 'PENGELUARAN');
            })
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->sum('jumlah');

            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'penerimaan' => $penerimaan,
                'pengeluaran' => $pengeluaran
            ];
        }

        return $data;
    }

    private function getPieChartBeban($tanggalAwal, $tanggalAkhir)
    {
        return JurnalUmum::whereHas('akun', function($q) {
            $q->where('klasifikasi', 'BEBAN');
        })
        ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
        ->select('akun_id', DB::raw('SUM(debit) as total'))
        ->with('akun')
        ->groupBy('akun_id')
        ->get();
    }

    private function getPenerimaanTerakhir()
    {
        return Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENERIMAAN');
        })
        ->with(['akunPayment', 'label'])
        ->latest()
        ->take(5)
        ->get();
    }

    private function getPengeluaranTerakhir()
    {
        return Transaksi::whereHas('label', function($q) {
            $q->where('tipe_utama', 'PENGELUARAN');
        })
        ->with(['akunPayment', 'label'])
        ->latest()
        ->take(5)
        ->get();
    }
}
