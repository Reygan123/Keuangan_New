<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;

class TransaksiKasBankService
{
    public function __construct(
        private TransaksiPajakService $pajakService
    ) {}

    private function catatJurnal(Transaksi $transaksi, Akun $akun, float $jumlah, string $type, string $deskripsi): JurnalUmum
    {
        $isDebit = $type === 'DEBIT';

        $jurnal = JurnalUmum::create([
            'akun_id' => $akun->id,
            'tanggal_transaksi' => $transaksi->tanggal,
            'deskripsi' => $deskripsi,
            'debit' => $isDebit ? $jumlah : 0,
            'kredit' => $isDebit ? 0 : $jumlah,
            'referensi_transaksi_id' => $transaksi->id,
            'referensi_transaksi_tipe' => get_class($transaksi),
            'usaha_id' => $transaksi->usaha_id,
        ]);

        $this->recalculateAkunSaldo($akun->id);

        return $jurnal;
    }

    protected function recalculateAkunSaldo($akunId)
    {
        $saldo = JurnalUmum::where('akun_id', $akunId)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) AS balance')
            ->value('balance');

        if ($saldo !== null) {
            Akun::where('id', $akunId)->update(['saldo' => $saldo]);
        }
    }

    public function reverseJurnal(Transaksi $transaksi): void
    {
        $jurnals = JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
                             ->where('referensi_transaksi_tipe', get_class($transaksi))
                             ->where('usaha_id', $transaksi->usaha_id)
                             ->get();

        $akunIdsToRecalculate = $jurnals->pluck('akun_id')->unique()->toArray();

        JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
                  ->where('referensi_transaksi_tipe', get_class($transaksi))
                  ->where('usaha_id', $transaksi->usaha_id)
                  ->delete();

        // Hapus juga record pajak terkait (baris jurnal pajaknya sudah ikut terhapus di atas)
        $this->pajakService->hapusPajak($transaksi);

        foreach ($akunIdsToRecalculate as $akunId) {
            $this->recalculateAkunSaldo($akunId);
        }
    }

    public function prosesTransaksi(Transaksi $transaksi): void
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->load(['label', 'akunPayment', 'akunLawan']);

            $tipeUtama = $transaksi->label->tipe_utama;
            $jumlah = (float) $transaksi->jumlah;
            $akunKasBank = $transaksi->akunPayment;
            $akunLawan = $transaksi->akunLawan;

            if (!$akunKasBank || !$akunLawan) {
                throw new \Exception("Akun Kas/Bank atau Akun Lawan tidak ditemukan.");
            }

            $deskripsiKasBank = "Transaksi {$tipeUtama} untuk {$akunLawan->name}";
            $deskripsiLawan = "Transaksi {$tipeUtama} dari/ke {$akunKasBank->name}";

            $jurnalKasBank = null;

            if ($tipeUtama === 'PENERIMAAN') {
                $jurnalKasBank = $this->catatJurnal(
                    $transaksi,
                    $akunKasBank,
                    $jumlah,
                    'DEBIT',
                    $deskripsiKasBank . ' (Debit Kas/Bank)'
                );

                $this->catatJurnal(
                    $transaksi,
                    $akunLawan,
                    $jumlah,
                    'KREDIT',
                    $deskripsiLawan . ' (Kredit Akun Lawan)'
                );

            } elseif ($tipeUtama === 'PENGELUARAN') {
                $this->catatJurnal(
                    $transaksi,
                    $akunLawan,
                    $jumlah,
                    'DEBIT',
                    $deskripsiLawan . ' (Debit Akun Lawan)'
                );

                $jurnalKasBank = $this->catatJurnal(
                    $transaksi,
                    $akunKasBank,
                    $jumlah,
                    'KREDIT',
                    $deskripsiKasBank . ' (Kredit Kas/Bank)'
                );
            } else {
                throw new \Exception("Tipe utama transaksi tidak valid: {$tipeUtama}");
            }

            // === INTEGRASI PAJAK ===
            // Cek apakah label ini punya Aturan Pajak. Kalau ada, baris Kas/Bank
            // otomatis dikurangi (untuk PPh) dan/atau ditambah baris PPN.
            $this->pajakService->terapkanPajak(
                $transaksi->usaha_id,
                $transaksi->label_id,
                null,
                $jumlah,
                $transaksi->tanggal,
                $deskripsiKasBank,
                $transaksi,
                $jurnalKasBank?->id
            );

            $transaksi->status = 'PROCESSED';
            $transaksi->save();
        });
    }
}