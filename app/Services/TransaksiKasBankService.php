<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk memproses Transaksi Penerimaan, Pengeluaran, dan Pembayaran Utang/Piutang.
 * Transaksi ini dicirikan oleh pergerakan dana antara akun Kas/Bank (akun_payment_id) dan Akun Lawan (akun_lawan_id).
 */
class TransaksiKasBankService
{
    /**
     * Mencatat entri jurnal dan memicu pembaruan saldo akun.
     * PENTING: Saldo akun dihitung ulang dari Jurnal Umum, bukan dihitung manual.
     */
    private function catatJurnal(Transaksi $transaksi, Akun $akun, float $jumlah, string $type, string $deskripsi)
    {
        $isDebit = $type === 'DEBIT';

        // 1. Mencatat Entri Jurnal
        JurnalUmum::create([
            'akun_id' => $akun->id,
            'tanggal_transaksi' => $transaksi->tanggal,
            'deskripsi' => $deskripsi,
            'debit' => $isDebit ? $jumlah : 0,
            'kredit' => $isDebit ? 0 : $jumlah,
            'referensi_transaksi_id' => $transaksi->id,
            'referensi_transaksi_tipe' => get_class($transaksi),
        ]);

        // 2. Memicu Re-kalkulasi Saldo Akun yang Terkena Dampak
        // Kita tidak lagi memperbarui saldo secara manual, melainkan menghitung ulang dari jurnal.
        $this->recalculateAkunSaldo($akun->id);
    }

    /**
     * Menghitung ulang total saldo dari Jurnal Umum dan menyimpannya ke kolom saldo di tabel Akuns.
     * @param int $akunId
     * @return void
     */
    protected function recalculateAkunSaldo($akunId)
    {
        // Saldo = SUM(Debit) - SUM(Kredit)
        $saldo = JurnalUmum::where('akun_id', $akunId)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) AS balance')
            ->value('balance');

        if ($saldo !== null) {
            Akun::where('id', $akunId)->update(['saldo' => $saldo]);
        }
    }


    /**
     * Menghapus entri jurnal lama dan memperbarui saldo akun.
     * Logika reversal dihilangkan, diganti dengan hapus jurnal & re-kalkulasi saldo.
     */
    public function reverseJurnal(Transaksi $transaksi): void
    {
        $jurnals = JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
                             ->where('referensi_transaksi_tipe', get_class($transaksi))
                             ->get();

        // Kumpulkan ID Akun yang Terpengaruh sebelum jurnal dihapus
        $akunIdsToRecalculate = $jurnals->pluck('akun_id')->unique()->toArray();

        // 1. Hapus entri jurnal terkait transaksi ini
        JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
                  ->where('referensi_transaksi_tipe', get_class($transaksi))
                  ->delete();

        // 2. RE-KALKULASI Saldo Akun yang Terpengaruh (Mengembalikan saldo ke keadaan sebelum transaksi)
        foreach ($akunIdsToRecalculate as $akunId) {
            $this->recalculateAkunSaldo($akunId);
        }
    }

    /**
     * Memproses transaksi Kas/Bank (Penerimaan atau Pengeluaran).
     *
     * @param Transaksi $transaksi Model transaksi yang sudah dimuat relasinya.
     */
    public function prosesTransaksi(Transaksi $transaksi): void
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->load(['label', 'akunPayment', 'akunLawan']); // akunLawan adalah relasi ke akun_lawan_id

            $tipeUtama = $transaksi->label->tipe_utama;
            $jumlah = (float) $transaksi->jumlah;
            $akunKasBank = $transaksi->akunPayment; // Akun Kas/Bank (Akun Utama)
            $akunLawan = $transaksi->akunLawan;     // Akun Lawan (Pendapatan/Beban/Utang/Piutang)

            if (!$akunKasBank || !$akunLawan) {
                throw new \Exception("Akun Kas/Bank atau Akun Lawan tidak ditemukan.");
            }

            $deskripsiKasBank = "Transaksi {$tipeUtama} untuk {$akunLawan->name}";
            $deskripsiLawan = "Transaksi {$tipeUtama} dari/ke {$akunKasBank->name}";

            if ($tipeUtama === 'PENERIMAAN') {
                // PENERIMAAN: Kas/Bank (D), Akun Lawan (K)

                // 1. Debit Kas/Bank (ASET BERTAMBAH) - Memanggil catatJurnal yang akan update saldo
                $this->catatJurnal(
                    $transaksi,
                    $akunKasBank,
                    $jumlah,
                    'DEBIT',
                    $deskripsiKasBank . ' (Debit Kas/Bank)'
                );

                // 2. Kredit Akun Lawan (PENDAPATAN/PIUTANG) - Memanggil catatJurnal yang akan update saldo
                $this->catatJurnal(
                    $transaksi,
                    $akunLawan,
                    $jumlah,
                    'KREDIT',
                    $deskripsiLawan . ' (Kredit Akun Lawan)'
                );

            } elseif ($tipeUtama === 'PENGELUARAN') {
                // PENGELUARAN: Akun Lawan (D), Kas/Bank (K)

                // 1. Debit Akun Lawan (BEBAN/UTANG) - Memanggil catatJurnal yang akan update saldo
                $this->catatJurnal(
                    $transaksi,
                    $akunLawan,
                    $jumlah,
                    'DEBIT',
                    $deskripsiLawan . ' (Debit Akun Lawan)'
                );

                // 2. Kredit Kas/Bank (ASET BERKURANG) - Memanggil catatJurnal yang akan update saldo
                $this->catatJurnal(
                    $transaksi,
                    $akunKasBank,
                    $jumlah,
                    'KREDIT',
                    $deskripsiKasBank . ' (Kredit Kas/Bank)'
                );
            } else {
                throw new \Exception("Tipe utama transaksi tidak valid: {$tipeUtama}");
            }

            // Set status transaksi menjadi diproses
            $transaksi->status = 'PROCESSED';
            $transaksi->save();
        });
    }
}
