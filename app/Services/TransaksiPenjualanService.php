<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\Akun;
use App\Models\AturanAutomation;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiPenjualanService
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
            ->where('usaha_id', $transaksi->usaha_id)
            ->get();

        foreach ($jurnals as $jurnal) {
            $akun = Akun::find($jurnal->akun_id);
            if (!$akun) continue;

            $jumlah = $jurnal->debit + $jurnal->kredit;

            $type = ($jurnal->debit > 0) ? 'KREDIT' : 'DEBIT';
            $deskripsi = "Pembalikan Jurnal Transaksi #" . $transaksi->id . " - " . $jurnal->deskripsi;

            $this->catatJurnal($transaksi, $akun, $jumlah, $type, $deskripsi);
        }

        JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
            ->where('referensi_transaksi_tipe', get_class($transaksi))
            ->where('usaha_id', $transaksi->usaha_id)
            ->delete();
    }

    public function reverseStokDanHpp(Transaksi $transaksi): void
    {
        $transaksi->load(['detailProduks.product']);

        foreach ($transaksi->detailProduks as $detail) {
            $prod = $detail->product;
            $qty = (float)$detail->kuantitas;

            if (Schema::hasColumn($prod->getTable(), 'stok')) {
                $fresh = $prod->fresh();
                $stokNow = (float)($fresh->stok ?? 0);
                $newStok = $stokNow + $qty;
                $prod->stok = $newStok;
                $prod->save();
            }
        }
    }

    public function prosesPenjualan(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->load(['detailProduks.product', 'akunPayment', 'label']);

            $total = (float) $transaksi->jumlah;
            $aturan = AturanAutomation::where('label_id', $transaksi->label_id)
                ->where('usaha_id', $transaksi->usaha_id)
                ->first();

            if (!$aturan) {
                throw new \Exception("Aturan Automation untuk label ID {$transaksi->label_id} tidak ditemukan.");
            }

            $akunKreditRevenue = Akun::where('id', $aturan->akun_kredit_id)
                ->where('usaha_id', $transaksi->usaha_id)
                ->first();

            $akunDebitFinal = $transaksi->akunPayment;

            if (!$akunDebitFinal || !$akunKreditRevenue) {
                throw new \Exception("Akun Pendapatan (dari Aturan) atau Akun Debit (dari Akun Pembayaran) tidak valid.");
            }

            $isCreditSale = strtolower($akunDebitFinal->klasifikasi) === 'aset' && Str::contains(strtolower($akunDebitFinal->name), 'piutang');

            $this->catatJurnal(
                $transaksi,
                $akunDebitFinal,
                $total,
                'DEBIT',
                'Pencatatan Penjualan - Debit Akun Penerimaan: ' . $akunDebitFinal->name
            );

            $this->catatJurnal(
                $transaksi,
                $akunKreditRevenue,
                $total,
                'KREDIT',
                'Pencatatan Penjualan - Kredit Akun Pendapatan: ' . $akunKreditRevenue->name
            );

            if ($isCreditSale) {
                $nomor = 'INV-' . Carbon::now()->format('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                DB::table('invoices')->insert([
                    'transaksi_id' => $transaksi->id,
                    'nomor_invoice' => $nomor,
                    'tanggal_jatuh_tempo' => Carbon::parse($transaksi->tanggal)->addDays(30)->format('Y-m-d'),
                    'jumlah_pajak' => 0,
                    'terms_pembayaran' => '30 hari',
                    'status_invoice' => 'unpaid',
                    'usaha_id' => $transaksi->usaha_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $nomor = 'RCPT-' . Carbon::now()->format('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                DB::table('receipts')->insert([
                    'transaksi_id' => $transaksi->id,
                    'nomor_receipt' => $nomor,
                    'mesin_kasir_id' => null,
                    'jumlah_dibayar' => $total,
                    'kembalian' => 0,
                    'usaha_id' => $transaksi->usaha_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            foreach ($transaksi->detailProduks as $detail) {
                $prod = $detail->product;
                $qty = (float)$detail->kuantitas;

                $hppUnit = (float)($prod->hpp_unit_rata2 ?? 0);
                $lineHpp = $qty * $hppUnit;

                if (Schema::hasColumn($prod->getTable(), 'stok')) {
                    $fresh = $prod->fresh();
                    $stokNow = (float)($fresh->stok ?? 0);
                    $newStok = $stokNow - $qty;
                    $prod->stok = $newStok;
                    $prod->save();
                }

                if ($lineHpp > 0) {
                    $kategoriHpp = $prod->kategoriHpp;

                    $akunPersediaan = Akun::where('id', $prod->akun_persediaan_id)
                        ->where('usaha_id', $transaksi->usaha_id)
                        ->first();

                    if ($akunPersediaan) {
                        $this->catatJurnal(
                            $transaksi,
                            $akunPersediaan,
                            $lineHpp,
                            'KREDIT',
                            'Kredit Persediaan - HPP Produk: ' . $prod->nama
                        );
                    }

                    $akunHpp = Akun::where('id', $prod->akun_hpp_id)
                        ->where('usaha_id', $transaksi->usaha_id)
                        ->first();

                    if (!$akunHpp && $kategoriHpp) {
                        $akunHpp = Akun::where('id', $kategoriHpp->hpp_akun_id ?? null)
                            ->where('usaha_id', $transaksi->usaha_id)
                            ->first();
                    }

                    if ($akunHpp) {
                        $this->catatJurnal(
                            $transaksi,
                            $akunHpp,
                            $lineHpp,
                            'DEBIT',
                            'Debit Beban HPP Produk: ' . $prod->nama
                        );
                    }
                }
            }
        });
    }
}
