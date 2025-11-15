<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk mengelola seluruh siklus transaksi produksi (input material dan output produk jadi).
 */
class TransaksiProduksiService
{
    /**
     * Memproses satu batch produksi, mengurangi material dan menambah produk jadi.
     *
     * @param int $finishedProductId ID Produk Jadi (Output)
     * @param float $quantityProduced Kuantitas Produk Jadi yang dihasilkan.
     * @param array $materialsConsumed Array material yang digunakan: [['id' => 1, 'qty' => 10.5, 'cost_per_unit' => 15000], ...]
     * @param float $laborOverheadCost Total biaya Tenaga Kerja dan Overhead (di luar biaya material).
     * @return Product Produk Jadi yang telah diperbarui.
     */
    public function prosesBatchProduksi(
        int $finishedProductId,
        float $quantityProduced,
        array $materialsConsumed,
        float $laborOverheadCost
    ): Product
    {
        return DB::transaction(function () use (
            $finishedProductId,
            $quantityProduced,
            $materialsConsumed,
            $laborOverheadCost
        ) {

            $totalMaterialCost = 0.0;

            // ==========================================================
            // LANGKAH 1: KONSUMSI MATERIAL (MENGURANGI STOK & MENGHITUNG BIAYA)
            // ==========================================================
            foreach ($materialsConsumed as $material) {
                $materialProduct = Product::findOrFail($material['id']);
                $qtyConsumed = (float)$material['qty'];

                if ($materialProduct->stok < $qtyConsumed) {
                    throw new \Exception("Stok {$materialProduct->nama} tidak mencukupi untuk produksi. Stok tersedia: {$materialProduct->stok}, Dibutuhkan: {$qtyConsumed}.");
                }

                // Ambil HPP rata-rata saat ini untuk menghitung nilai yang keluar
                $hppMaterial = (float)$materialProduct->hpp_unit_rata2;

                // Kurangi Stok Bahan Baku
                $materialProduct->stok -= $qtyConsumed;
                $materialProduct->save();

                // Hitung nilai biaya material yang keluar (debit WIP/Biaya Produksi, kredit Persediaan Material)
                $cost = $hppMaterial * $qtyConsumed;
                $totalMaterialCost += $cost;

                // TODO: Di sini perlu ada logging ke tabel produksi_details dan Jurnal Akuntansi (Material Keluar)
            }

            // ==========================================================
            // LANGKAH 2: HITUNG TOTAL HPP PRODUKSI
            // ==========================================================

            $totalCostOfGoods = $totalMaterialCost + $laborOverheadCost;

            // ==========================================================
            // LANGKAH 3: PENYELESAIAN PRODUK JADI (MENAMBAH STOK & HPP)
            // ==========================================================

            $product = Product::findOrFail($finishedProductId);

            $stokLama = (float) $product->stok;
            $hppLama = (float) $product->hpp_unit_rata2;

            $nilaiPersediaanLama = $stokLama * $hppLama;
            $nilaiProduksiBaru = $totalCostOfGoods;

            $stokTotalBaru = $stokLama + $quantityProduced;

            if ($stokTotalBaru > 0) {
                $hppUnitBaru = ($nilaiPersediaanLama + $nilaiProduksiBaru) / $stokTotalBaru;
            } else {
                // Jika quantityProduced adalah 0, ini harusnya tidak terjadi
                $hppUnitBaru = $laborOverheadCost / 1;
            }

            // Tambah Stok Produk Jadi
            $product->stok = $stokTotalBaru; // <<< STOK PRODUK JADI BERTAMBAH
            $product->hpp_unit_rata2 = $hppUnitBaru; // HPP di-update dengan rata-rata bergerak
            $product->save();

            // TODO: Di sini perlu ada logging ke tabel production_records dan Jurnal Akuntansi (Produk Jadi Masuk)

            return $product;
        });
    }
}
