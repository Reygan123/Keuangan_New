<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\AturanAutomation;
use App\Models\Invoice;
use App\Models\Nota;
use App\Models\Kuitansi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TransaksiPembelianService
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
        ]);

        $multiplier = 0;

        if ($type === 'DEBIT') {
            if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
                $multiplier = 1;
            } else {
                $multiplier = -1;
            }
        } elseif ($type === 'KREDIT') {
            if (in_array($akun->klasifikasi, ['KEWAJIBAN', 'PENDAPATAN', 'EKUITAS'])) {
                $multiplier = 1;
            } else {
                $multiplier = -1;
            }
        }

        $akun->saldo = (float)$akun->saldo + ($jumlah * $multiplier);
        $akun->save();
    }

    public function reverseJurnal(Transaksi $transaksi): void
    {
        $jurnals = JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
            ->where('referensi_transaksi_tipe', get_class($transaksi))
            ->get();

        foreach ($jurnals as $jurnal) {
            $akun = Akun::find($jurnal->akun_id);
            if (!$akun) continue;

            $jumlah = $jurnal->debit + $jurnal->kredit;

            $type = ($jurnal->debit > 0) ? 'KREDIT' : 'DEBIT';
            $deskripsi = "Pembalikan Jurnal Pembelian #" . $transaksi->id . " - " . $jurnal->deskripsi;

            $this->catatJurnal($transaksi, $akun, $jumlah, $type, $deskripsi);
        }

        JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
            ->where('referensi_transaksi_tipe', get_class($transaksi))
            ->delete();
    }

    public function reverseStokDanHpp(Transaksi $transaksi): void
    {
        $transaksi->load(['detailProduks.product']);

        foreach ($transaksi->detailProduks as $detail) {
            $prod = $detail->product->fresh();
            $qty = (float)$detail->kuantitas;

            if (Schema::hasColumn($prod->getTable(), 'stok')) {
                $stokLama = (float)($prod->stok ?? 0);
                $hppLama = (float)($prod->hpp_unit_rata2 ?? 0);

                $totalNilaiLama = $stokLama * $hppLama;
                $totalNilaiDikurangi = $qty * $hppLama;
                $stokTotalBaru = $stokLama - $qty;

                if ($stokTotalBaru > 0) {
                    $hppUnitBaru = ($totalNilaiLama - $totalNilaiDikurangi) / $stokTotalBaru;
                    $prod->hpp_unit_rata2 = $hppUnitBaru;
                } else {
                    $prod->hpp_unit_rata2 = 0;
                }

                $prod->stok = $stokTotalBaru;
                $prod->save();
            }
        }
    }

    private function generateNomorDokumen($prefix)
    {
        $tahun = Carbon::now()->format('Y');
        $bulan = Carbon::now()->format('m');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$tahun}{$bulan}-{$random}";
    }

    private function buatDokumenPembelian(Transaksi $transaksi)
    {
        $isKredit = $transaksi->akunPayment->klasifikasi === 'KEWAJIBAN';

        if ($isKredit) {
            Invoice::create([
                'transaksi_id' => $transaksi->id,
                'nomor_invoice' => $this->generateNomorDokumen('INV-PBL'),
                'tanggal_jatuh_tempo' => Carbon::parse($transaksi->tanggal)->addDays(30),
                'jumlah_pajak' => 0,
                'terms_pembayaran' => '30 hari',
                'status_invoice' => 'unpaid'
            ]);
        } else {
            Nota::create([
                'transaksi_id' => $transaksi->id,
                'nomor_nota' => $this->generateNomorDokumen('NOTA-PBL'),
                'jenis_nota' => 'pembelian',
                'is_tunai' => true
            ]);

            Kuitansi::create([
                'transaksi_id' => $transaksi->id,
                'nomor_kuitansi' => $this->generateNomorDokumen('KUIT-PBL'),
                'tanggal_pembayaran' => $transaksi->tanggal,
                'metode_pembayaran' => 'transfer',
                'jumlah_dibayar' => $transaksi->jumlah,
                'tanda_tangan_penerima' => 'Supplier'
            ]);
        }
    }

    public function prosesPembelian(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->load(['detailProduks.product', 'akunPayment', 'label']);

            $total = (float) $transaksi->jumlah;
            $aturan = AturanAutomation::where('label_id', $transaksi->label_id)->first();

            if (!$aturan) {
                throw new \Exception("Aturan Automation untuk label ID {$transaksi->label_id} tidak ditemukan.");
            }

            $akunDebit = Akun::find($aturan->akun_debit_id);
            $akunKreditFinal = $transaksi->akunPayment;

            if (!$akunDebit || !$akunKreditFinal) {
                throw new \Exception("Akun Debit (dari Aturan) atau Akun Kredit (dari Akun Pembayaran) tidak valid.");
            }

            $this->catatJurnal(
                $transaksi,
                $akunDebit,
                $total,
                'DEBIT',
                'Pembelian ' . $transaksi->label->name . ' (Debit Persediaan/Aset)'
            );

            $this->catatJurnal(
                $transaksi,
                $akunKreditFinal,
                $total,
                'KREDIT',
                'Pembelian ' . $transaksi->label->name . ' (Kredit Akun Pembayaran/Utang)'
            );

            foreach ($transaksi->detailProduks as $detail) {
                $prod = $detail->product->fresh();
                $qty = (float)$detail->kuantitas;
                $hargaBeliUnit = (float)($detail->harga_satuan ?? 0);

                if (Schema::hasColumn($prod->getTable(), 'stok')) {
                    $stokLama = (float)($prod->stok ?? 0);
                    $hppLama = (float)($prod->hpp_unit_rata2 ?? 0);

                    $totalNilaiLama = $stokLama * $hppLama;
                    $totalNilaiBaru = $qty * $hargaBeliUnit;
                    $stokTotalBaru = $stokLama + $qty;

                    if ($stokTotalBaru > 0) {
                        $hppUnitBaru = ($totalNilaiLama + $totalNilaiBaru) / $stokTotalBaru;
                        $prod->hpp_unit_rata2 = $hppUnitBaru;
                    } else {
                        $prod->hpp_unit_rata2 = $hargaBeliUnit;
                    }

                    $prod->stok = $stokTotalBaru;
                    $prod->save();
                }
            }

            $this->buatDokumenPembelian($transaksi);
        });
    }
}
