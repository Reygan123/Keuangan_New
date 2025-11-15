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
    /**
     * Mencatat entri Jurnal Umum dan memperbarui saldo Akun secara cache.
     * @param Transaksi $transaksi
     * @param Akun $akun
     * @param float $jumlah
     * @param string $type ('DEBIT' atau 'KREDIT')
     * @param string $deskripsi
     */
    private function catatJurnal(Transaksi $transaksi, Akun $akun, float $jumlah, string $type, string $deskripsi)
    {
        $isDebit = $type === 'DEBIT';

        // 1. Catat Jurnal Umum
        JurnalUmum::create([
            'akun_id' => $akun->id,
            'tanggal_transaksi' => $transaksi->tanggal,
            'deskripsi' => $deskripsi,
            'debit' => $isDebit ? $jumlah : 0,
            'kredit' => $isDebit ? 0 : $jumlah,
            'referensi_transaksi_id' => $transaksi->id,
            'referensi_transaksi_tipe' => get_class($transaksi),
        ]);

        // 2. Update Saldo Akun (Cache)
        $multiplier = 0;

        if ($type === 'DEBIT') {
            // Debit menambah saldo normal ASET/BEBAN
            if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
                $multiplier = 1;
            } else { // Debit mengurangi saldo normal Kewajiban/Pendapatan/Ekuitas
                $multiplier = -1;
            }
        } elseif ($type === 'KREDIT') {
            // Kredit menambah saldo normal Kewajiban/Pendapatan/Ekuitas
            if (in_array($akun->klasifikasi, ['KEWAJIBAN', 'PENDAPATAN', 'EKUITAS'])) {
                $multiplier = 1;
            } else { // Kredit mengurangi saldo normal ASET/BEBAN
                $multiplier = -1;
            }
        }

        $akun->saldo = (float)$akun->saldo + ($jumlah * $multiplier);
        $akun->save();
    }

    /**
     * Membalikkan entri jurnal dan saldo akun yang terkait dengan transaksi.
     */
    public function reverseJurnal(Transaksi $transaksi): void
    {
        $jurnals = JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
            ->where('referensi_transaksi_tipe', get_class($transaksi))
            ->get();

        foreach ($jurnals as $jurnal) {
            $akun = Akun::find($jurnal->akun_id);
            if (!$akun) continue;

            $jumlah = $jurnal->debit + $jurnal->kredit;

            // Jurnal Reversal: Balikkan posisi Debit dan Kredit
            // Jika jurnal lama DEBIT, reversalnya KREDIT, dan sebaliknya
            $type = ($jurnal->debit > 0) ? 'KREDIT' : 'DEBIT';
            $deskripsi = "Pembalikan Jurnal Transaksi #" . $transaksi->id . " - " . $jurnal->deskripsi;

            // Memanggil catatJurnal akan membuat entri reversal dan
            // secara otomatis memperbarui saldo akun.
            $this->catatJurnal($transaksi, $akun, $jumlah, $type, $deskripsi);
        }

        // Hapus entri jurnal lama agar tidak tercatat ganda saat update/re-run prosesPenjualan
        // Ini memastikan Jurnal Umum hanya memiliki entri reversal, atau entri baru setelah update.
        JurnalUmum::where('referensi_transaksi_id', $transaksi->id)
            ->where('referensi_transaksi_tipe', get_class($transaksi))
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

    /**
     * Memproses transaksi penjualan, mencatat jurnal, dan update stok/dokumen.
     */
    public function prosesPenjualan(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->load(['detailProduks.product', 'akunPayment', 'label']);

            $total = (float) $transaksi->jumlah;
            $aturan = AturanAutomation::where('label_id', $transaksi->label_id)->first();

            if (!$aturan) {
                // Handle case where automation rule is missing (data error)
                throw new \Exception("Aturan Automation untuk label ID {$transaksi->label_id} tidak ditemukan.");
            }

            // AKUN DARI ATURAN OTOMATISASI
            $akunKreditRevenue = Akun::find($aturan->akun_kredit_id); // Ini adalah Akun Pendapatan Penjualan

            // AKUN DARI PILIHAN PENGGUNA (akun_payment_id)
            $akunDebitFinal = $transaksi->akunPayment; // Ini adalah Akun Kas/Bank atau Piutang Usaha

            if (!$akunDebitFinal || !$akunKreditRevenue) {
                throw new \Exception("Akun Pendapatan (dari Aturan) atau Akun Debit (dari Akun Pembayaran) tidak valid.");
            }

            // 1. JURNAL UTAMA (PENJUALAN)
            // Debit: Kas/Piutang Usaha (ASET) | Kredit: Pendapatan Penjualan (PENDAPATAN)

            // --- TENTUKAN APAKAH INI PENJUALAN KREDIT ---
            // Penjualan Kredit jika akun payment yang dipilih adalah akun Piutang Usaha
            $isCreditSale = strtolower($akunDebitFinal->klasifikasi) === 'aset' && Str::contains(strtolower($akunDebitFinal->name), 'piutang');

            // --- Jurnal Sisi DEBIT (ASET: Kas/Piutang BERTAMBAH) ---
            $this->catatJurnal(
                $transaksi,
                $akunDebitFinal, // <-- PERBAIKAN: Menggunakan akun_payment_id sebagai DEBIT
                $total,
                'DEBIT',
                'Pencatatan Penjualan - Debit Akun Penerimaan: ' . $akunDebitFinal->name
            );

            // --- Jurnal Sisi KREDIT (PENDAPATAN: Penjualan BERTAMBAH) ---
            $this->catatJurnal(
                $transaksi,
                $akunKreditRevenue, // Menggunakan Akun Pendapatan dari aturan otomatisasi
                $total,
                'KREDIT',
                'Pencatatan Penjualan - Kredit Akun Pendapatan: ' . $akunKreditRevenue->name
            );


            // 2. PEMBUATAN DOKUMEN (INVOICE atau RECEIPT)
            if ($isCreditSale) {
                // Buat INVOICE (Untuk Penjualan Kredit)
                $nomor = 'INV-' . Carbon::now()->format('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                DB::table('invoices')->insert([
                    'transaksi_id' => $transaksi->id,
                    'nomor_invoice' => $nomor,
                    'tanggal_jatuh_tempo' => Carbon::parse($transaksi->tanggal)->addDays(30)->format('Y-m-d'),
                    'jumlah_pajak' => 0,
                    'terms_pembayaran' => '30 hari',
                    'status_invoice' => 'unpaid',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // Buat RECEIPT (Untuk Penjualan Tunai/Non-Kredit)
                $nomor = 'RCPT-' . Carbon::now()->format('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                DB::table('receipts')->insert([
                    'transaksi_id' => $transaksi->id,
                    'nomor_receipt' => $nomor,
                    'mesin_kasir_id' => null,
                    'jumlah_dibayar' => $total,
                    'kembalian' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // 3. JURNAL & UPDATE STOK HPP (COGS)
            foreach ($transaksi->detailProduks as $detail) {
                $prod = $detail->product;
                $qty = (float)$detail->kuantitas;

                // Menggunakan HPP rata-rata unit produk
                $hppUnit = (float)($prod->hpp_unit_rata2 ?? 0);
                $lineHpp = $qty * $hppUnit;

                // Update Stok Produk
                if (Schema::hasColumn($prod->getTable(), 'stok')) {
                    // Gunakan fresh() untuk memastikan mengambil stok terbaru dari DB
                    $fresh = $prod->fresh();
                    $stokNow = (float)($fresh->stok ?? 0);
                    $newStok = $stokNow - $qty;
                    $prod->stok = $newStok;
                    $prod->save();
                }

                // Catat Jurnal HPP hanya jika produk memiliki Harga Pokok Penjualan (HPP > 0)
                if ($lineHpp > 0) {
                    // Asumsi: Anda memiliki relasi kategoriHpp
                    $kategoriHpp = $prod->kategoriHpp;

                    // Kredit Persediaan (ASET berkurang)
                    $akunPersediaan = Akun::find($prod->akun_persediaan_id); // Menggunakan akun persediaan dari produk
                    if ($akunPersediaan) {
                        $this->catatJurnal(
                            $transaksi,
                            $akunPersediaan,
                            $lineHpp,
                            'KREDIT',
                            'Kredit Persediaan - HPP Produk: ' . $prod->nama
                        );
                    }

                    // Debit HPP (BEBAN bertambah)
                    $akunHpp = Akun::find($prod->akun_hpp_id); // Asumsi Anda punya field akun_hpp_id di model Product
                    if (!$akunHpp && $kategoriHpp) {
                        // Fallback jika tidak ada di produk, gunakan dari kategori HPP
                        $akunHpp = Akun::find($kategoriHpp->hpp_akun_id ?? null);
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
