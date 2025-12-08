<?php

namespace App\Services;

use App\Models\MutasiRekening;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MutasiRekeningService
{
    // Menggunakan fungsi catatJurnal dari service Pembelian sebelumnya
    // Saya asumsikan fungsi ini sudah ada di helper atau service Akuntansi dasar
    private function catatJurnal(Model $referensi, Akun $akun, float $jumlah, string $type, string $deskripsi, int $jurnalReferensiId)
    {
        $isDebit = $type === 'DEBIT';
        $referensiClass = get_class($referensi);

        $jurnal = JurnalUmum::create([
            'akun_id' => $akun->id,
            'tanggal_transaksi' => $referensi->tanggal,
            'deskripsi' => $deskripsi,
            'debit' => $isDebit ? $jumlah : 0,
            'kredit' => $isDebit ? 0 : $jumlah,
            'referensi_transaksi_tipe' => $referensiClass,
            'referensi_transaksi_id' => $jurnalReferensiId, // ID untuk mengelompokkan 2 entri
        ]);

        // Logika update saldo akun yang sama dengan di TransaksiPembelianService.php
        $multiplier = 0;
        if ($type === 'DEBIT') {
            $multiplier = (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) ? 1 : -1;
        } elseif ($type === 'KREDIT') {
            $multiplier = (in_array($akun->klasifikasi, ['KEWAJIBAN', 'PENDAPATAN', 'EKUITAS'])) ? 1 : -1;
        }

        $akun->saldo = (float)$akun->saldo + ($jumlah * $multiplier);
        $akun->save();

        return $jurnal;
    }

    // ----------------------------------------------------------------------
    // PROSES MUTASI (CREATE/UPDATE)
    // ----------------------------------------------------------------------
    public function prosesMutasi(MutasiRekening $mutasi): void
    {
        $akunAsal = Akun::findOrFail($mutasi->akun_asal_id);
        $akunTujuan = Akun::findOrFail($mutasi->akun_tujuan_id);
        $jumlah = (float) $mutasi->jumlah;

        // Gunakan ID Mutasi sebagai referensi ID Jurnal sementara
        $jurnalReferensiId = $mutasi->id;

        // 1. JURNAL DEBIT (Uang Masuk ke Akun Tujuan)
        $this->catatJurnal(
            $mutasi,
            $akunTujuan,
            $jumlah,
            'DEBIT',
            'Mutasi Masuk dari ' . $akunAsal->name . ' ke ' . $akunTujuan->name . ' - ' . $mutasi->deskripsi,
            $jurnalReferensiId
        );

        // 2. JURNAL KREDIT (Uang Keluar dari Akun Asal)
        $this->catatJurnal(
            $mutasi,
            $akunAsal,
            $jumlah,
            'KREDIT',
            'Mutasi Keluar ke ' . $akunTujuan->name . ' dari ' . $akunAsal->name . ' - ' . $mutasi->deskripsi,
            $jurnalReferensiId
        );
    }

    // ----------------------------------------------------------------------
    // PEMBALIKAN JURNAL (UPDATE/DELETE)
    // ----------------------------------------------------------------------
    public function reverseJurnal(MutasiRekening $mutasi): void
    {
        Log::info('Mencari jurnal untuk mutasi ID: ' . $mutasi->id);
        Log::info('Class name: ' . MutasiRekening::class);

        $jurnals = JurnalUmum::where('referensi_transaksi_id', $mutasi->id)
            ->where('referensi_transaksi_tipe', MutasiRekening::class)
            ->get();

        Log::info('Jumlah jurnal ditemukan: ' . $jurnals->count());

        foreach ($jurnals as $jurnal) {
            $akun = Akun::find($jurnal->akun_id);
            if (!$akun) continue;

            $jumlah = $jurnal->debit + $jurnal->kredit;

            // Balik saldo langsung tanpa membuat jurnal pembalik
            $multiplier = 0;
            if ($jurnal->debit > 0) {
                // Jurnal asli DEBIT, balik dengan mengurangi
                $multiplier = (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) ? -1 : 1;
            } else {
                // Jurnal asli KREDIT, balik dengan mengurangi
                $multiplier = (in_array($akun->klasifikasi, ['KEWAJIBAN', 'PENDAPATAN', 'EKUITAS'])) ? -1 : 1;
            }

            $akun->saldo = (float)$akun->saldo + ($jumlah * $multiplier);
            $akun->save();
        }

        // Hapus Jurnal Lama
        JurnalUmum::where('referensi_transaksi_id', $mutasi->id)
            ->where('referensi_transaksi_tipe', MutasiRekening::class) // ← PERBAIKAN: gunakan class name langsung
            ->delete();
    }
}
