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

        $multiplier = 0;

        if ($type === 'DEBIT') {
            $multiplier = in_array($akun->klasifikasi, ['ASET', 'BEBAN']) ? 1 : -1;
        } elseif ($type === 'KREDIT') {
            $multiplier = in_array($akun->klasifikasi, ['KEWAJIBAN', 'PENDAPATAN', 'EKUITAS']) ? 1 : -1;
        }

        $akun->saldo = (float)$akun->saldo + ($jumlah * $multiplier);
        $akun->save();

        return $jurnal;
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

        $this->pajakService->hapusPajak($transaksi); // ← TAMBAHAN
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
                $prod->stok = $stokNow + $qty;
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

            $jurnalDebit = $this->catatJurnal(
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

            // === INTEGRASI PAJAK — PER PRODUK (PPN Keluaran) ===
            // Baris DEBIT (Kas/Piutang) otomatis BERTAMBAH sesuai pajak yang kena.
            $totalPajak = 0.0;

            foreach ($transaksi->detailProduks as $detail) {
                $lineTotal = (float) $detail->kuantitas * (float) $detail->harga_satuan;

                $hasilPajak = $this->pajakService->terapkanPajak(
                    $transaksi->usaha_id,
                    null,
                    $detail->product_id,
                    $lineTotal,
                    $transaksi->tanggal,
                    'Penjualan ' . ($detail->product->nama ?? 'Produk'),
                    $transaksi,
                    $jurnalDebit->id
                );

                foreach ($hasilPajak as $tp) {
                    $totalPajak += (float) $tp->jumlah_pajak;
                }
            }

            $totalDiterima = $total + $totalPajak;

            // === DOKUMEN: Invoice atau Receipt, sekarang ikut cerminkan pajak ===
            if ($isCreditSale) {
                $nomor = 'INV-' . Carbon::now()->format('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                DB::table('invoices')->insert([
                    'transaksi_id' => $transaksi->id,
                    'nomor_invoice' => $nomor,
                    'tanggal_jatuh_tempo' => Carbon::parse($transaksi->tanggal)->addDays(30)->format('Y-m-d'),
                    'jumlah_pajak' => $totalPajak,
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
                    'jumlah_dibayar' => $totalDiterima,
                    'kembalian' => 0,
                    'usaha_id' => $transaksi->usaha_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // === HPP & Update Stok (tidak berubah, tidak terpengaruh pajak) ===
            foreach ($transaksi->detailProduks as $detail) {
                $prod = $detail->product;
                $qty = (float)$detail->kuantitas;

                $hppUnit = (float)($prod->hpp_unit_rata2 ?? 0);
                $lineHpp = $qty * $hppUnit;

                if (Schema::hasColumn($prod->getTable(), 'stok')) {
                    $fresh = $prod->fresh();
                    $stokNow = (float)($fresh->stok ?? 0);
                    $prod->stok = $stokNow - $qty;
                    $prod->save();
                }

                if ($lineHpp > 0) {
                    $kategoriHpp = $prod->kategoriHpp;

                    $akunPersediaan = Akun::where('id', $prod->akun_persediaan_id)
                        ->where('usaha_id', $transaksi->usaha_id)
                        ->first();

                    if ($akunPersediaan) {
                        $this->catatJurnal(
                            $transaksi, $akunPersediaan, $lineHpp, 'KREDIT',
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
                            $transaksi, $akunHpp, $lineHpp, 'DEBIT',
                            'Debit Beban HPP Produk: ' . $prod->nama
                        );
                    }
                }
            }
        });
    }
}