<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;

class TransaksiKasBankService
{
    private function catatJurnal(Transaksi $transaksi, Akun $akun, float $jumlah, string $type, string $deskripsi)
    {
        $isDebit = $type === 'DEBIT';

        JurnalUmum::create([
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

            if ($tipeUtama === 'PENERIMAAN') {
                $this->catatJurnal(
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

            $transaksi->status = 'PROCESSED';
            $transaksi->save();
        });
    }
}
