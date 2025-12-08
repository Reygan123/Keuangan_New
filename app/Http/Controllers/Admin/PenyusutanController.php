<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailAsetTetap;
use App\Models\Penyusutan;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenyusutanController extends Controller
{
    public function index()
    {
        $asetTetap = DetailAsetTetap::with(['akunAset', 'penyusutans'])->get();

        $asetTetap->each(function ($aset) {
            $aset->total_akumulasi = $aset->penyusutans ? $aset->penyusutans->sum('jumlah_penyusutan') : 0;
            $aset->nilai_buku = $aset->harga_beli - $aset->total_akumulasi;
        });

        return view('admin.penyusutan.index', compact('asetTetap'));
    }

    public function create()
    {
        $akunAset = Akun::where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'TETAP')
            ->get();

        $akunBeban = Akun::where('klasifikasi', 'BEBAN')->get();
        $akunAkumulasi = Akun::where('name', 'like', '%akumulasi%')->get();

        return view('admin.penyusutan.create', compact('akunAset', 'akunBeban', 'akunAkumulasi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'akun_aset_id' => 'required|exists:akuns,id',
            'uraian' => 'required|string|max:255',
            'tgl_perolehan' => 'required|date',
            'harga_beli' => 'required|numeric|min:0',
            'golongan' => 'required|string|max:100',
            'umur_ekonomis' => 'required|integer|min:1',
            'nilai_sisa' => 'required|numeric|min:0',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_akumulasi_id' => 'required|exists:akuns,id'
        ]);

        DetailAsetTetap::create($validated);

        return redirect()->route('admin.penyusutan.index')->with('success', 'Aset tetap berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $aset = DetailAsetTetap::findOrFail($id);
        $akunAset = Akun::where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'TETAP')
            ->get();

        $akunBeban = Akun::where('klasifikasi', 'BEBAN')->get();
        $akunAkumulasi = Akun::where('name', 'like', '%akumulasi%')->get();

        return view('admin.penyusutan.edit', compact('aset', 'akunAset', 'akunBeban', 'akunAkumulasi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'akun_aset_id' => 'required|exists:akuns,id',
            'uraian' => 'required|string|max:255',
            'tgl_perolehan' => 'required|date',
            'harga_beli' => 'required|numeric|min:0',
            'golongan' => 'required|string|max:100',
            'umur_ekonomis' => 'required|integer|min:1',
            'nilai_sisa' => 'required|numeric|min:0',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_akumulasi_id' => 'required|exists:akuns,id'
        ]);

        $aset = DetailAsetTetap::findOrFail($id);
        $aset->update($validated);

        return redirect()->route('admin.penyusutan.index')->with('success', 'Aset tetap berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aset = DetailAsetTetap::findOrFail($id);
        $aset->delete();

        return redirect()->route('admin.penyusutan.index')->with('success', 'Aset tetap berhasil dihapus.');
    }

    public function prosesPenyusutan(Request $request)
    {
        // Default bulan adalah bulan yang diminta atau bulan saat ini
        $bulan = $request->input('bulan', now()->format('Y-m'));
        // Tanggal penyusutan diambil dari akhir bulan yang dipilih
        $tanggal_penyusutan = Carbon::parse($bulan)->endOfMonth();

        DB::beginTransaction();

        try {
            // Memuat aset dengan data penyusutan yang sudah ada
            $asetAktif = DetailAsetTetap::with(['penyusutans'])->get();
            $totalPenyusutan = 0;
            $asetDiproses = 0;
            $akunTerdampak = []; // Untuk melacak akun yang perlu dihitung ulang

            foreach ($asetAktif as $aset) {
                if ($this->perluDisusutkan($aset, $tanggal_penyusutan)) {
                    $jumlah_penyusutan_final = $this->hitungPenyusutan($aset);

                    if ($jumlah_penyusutan_final > 0) {
                        // 1. Catat Log Penyusutan
                        $penyusutan = Penyusutan::create([
                            'detail_aset_id' => $aset->id,
                            'tanggal_penyusutan' => $tanggal_penyusutan,
                            'jumlah_penyusutan' => $jumlah_penyusutan_final,
                            'akun_beban_id' => $aset->akun_beban_id,
                            'akun_akumulasi_id' => $aset->akun_akumulasi_id
                        ]);

                        // 2. Buat Jurnal Umum dan Recalculate Saldo
                        $this->buatJurnalPenyusutan($penyusutan, $aset);

                        // 3. Tambahkan akun ke daftar untuk memastikan recalculate jika ada banyak aset
                        $akunTerdampak[$aset->akun_beban_id] = true;
                        $akunTerdampak[$aset->akun_akumulasi_id] = true;


                        $totalPenyusutan += $jumlah_penyusutan_final;
                        $asetDiproses++;
                    }
                }
            }

            // PENTING: Panggil recalculate HANYA SEKALI untuk setiap Akun Terdampak
            foreach (array_keys($akunTerdampak) as $akun_id) {
                $this->recalculateAkunSaldo($akun_id);
            }

            DB::commit();

            return redirect()->route('admin.penyusutan.index')
                ->with('success', "Penyusutan berhasil diproses. $asetDiproses aset disusutkan dengan total Rp " . number_format($totalPenyusutan, 2, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.penyusutan.index')
                ->with('error', "Gagal memproses penyusutan: " . $e->getMessage());
        }
    }

    private function perluDisusutkan($aset, $tanggal_penyusutan)
    {
        // 1. Cek duplikasi di bulan yang sama untuk aset ini
        if ($this->isPenyusutanSudahDicatat($aset, $tanggal_penyusutan)) {
            return false;
        }

        $tglPerolehan = Carbon::parse($aset->tgl_perolehan);
        $totalBulan = $tglPerolehan->diffInMonths($tanggal_penyusutan);

        // Cek agar penyusutan tidak dilakukan di bulan perolehan
        if ($tglPerolehan->format('Y-m') === $tanggal_penyusutan->format('Y-m')) {
            return false;
        }

        // 2. Cek apakah Nilai Buku sudah mencapai Nilai Sisa
        $totalAkumulasi = $aset->penyusutans ? $aset->penyusutans->sum('jumlah_penyusutan') : 0;
        $nilaiBuku = $aset->harga_beli - $totalAkumulasi;

        if ($nilaiBuku <= $aset->nilai_sisa) {
            return false; // Aset sudah habis disusutkan
        }

        // 3. Cek apakah sudah melewati umur ekonomis
        // Menggunakan <= umur ekonomis total bulan (untuk pengecekan tambahan, meskipun Nilai Sisa harusnya mengunci)
        return $totalBulan < ($aset->umur_ekonomis * 12);
    }

    private function isPenyusutanSudahDicatat($aset, $tanggal_penyusutan)
    {
        return $aset->penyusutans()
            ->whereYear('tanggal_penyusutan', $tanggal_penyusutan->year)
            ->whereMonth('tanggal_penyusutan', $tanggal_penyusutan->month)
            ->exists();
    }

    private function hitungPenyusutan($aset)
    {
        $totalAkumulasi = $aset->penyusutans ? $aset->penyusutans->sum('jumlah_penyusutan') : 0;
        $nilaiBuku = $aset->harga_beli - $totalAkumulasi;

        // Perhitungan Garis Lurus Per Bulan
        $hargaPenyusutan = $aset->harga_beli - $aset->nilai_sisa;
        $penyusutanBulanan = $hargaPenyusutan / ($aset->umur_ekonomis * 12);

        // PENTING: Penyesuaian bulan terakhir agar tidak melewati Nilai Sisa (rounding/bulan penuh)
        if ($nilaiBuku - $penyusutanBulanan < $aset->nilai_sisa) {
            // Ambil selisih yang tersisa (Nilai Buku Saat Ini - Nilai Sisa)
            $penyusutanBulanan = $nilaiBuku - $aset->nilai_sisa;
        }

        return round($penyusutanBulanan, 2); // Pembulatan 2 desimal
    }

    private function buatJurnalPenyusutan($penyusutan, $aset)
    {
        // Jurnal 1: Debit Beban Penyusutan
        JurnalUmum::create([
            'akun_id' => $penyusutan->akun_beban_id,
            'tanggal_transaksi' => $penyusutan->tanggal_penyusutan,
            'deskripsi' => "Beban Penyusutan - " . $aset->uraian,
            'debit' => $penyusutan->jumlah_penyusutan,
            'kredit' => 0,
            'referensi_transaksi_id' => $penyusutan->id,
            'referensi_transaksi_tipe' => 'penyusutan',
            'sumber_log_type' => 'penyusutan',
            'sumber_log_id' => $penyusutan->id
        ]);

        // Jurnal 2: Kredit Akumulasi Penyusutan
        JurnalUmum::create([
            'akun_id' => $penyusutan->akun_akumulasi_id,
            'tanggal_transaksi' => $penyusutan->tanggal_penyusutan,
            'deskripsi' => "Akumulasi Penyusutan - " . $aset->uraian,
            'debit' => 0,
            'kredit' => $penyusutan->jumlah_penyusutan,
            'referensi_transaksi_id' => $penyusutan->id,
            'referensi_transaksi_tipe' => 'penyusutan',
            'sumber_log_type' => 'penyusutan',
            'sumber_log_id' => $penyusutan->id
        ]);

    }

    private function recalculateAkunSaldo($akunId)
    {
        $akun = Akun::find($akunId);
        if (!$akun) return;

        $totalDebit = JurnalUmum::where('akun_id', $akunId)->sum('debit');
        $totalKredit = JurnalUmum::where('akun_id', $akunId)->sum('kredit');
        $saldoAwal = $akun->saldo_awal ?? 0;

        if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
            $saldo = $saldoAwal + $totalDebit - $totalKredit;
        }
        else {
            $saldo = $saldoAwal + $totalKredit - $totalDebit;
        }

        $akun->saldo = $saldo;
        $akun->save();
    }

    public function riwayat($id)
    {
        $aset = DetailAsetTetap::with(['penyusutans.akunBeban', 'penyusutans.akunAkumulasi'])->findOrFail($id);
        $riwayat = $aset->penyusutans;

        return view('admin.penyusutan.riwayat', compact('aset', 'riwayat'));
    }
}
