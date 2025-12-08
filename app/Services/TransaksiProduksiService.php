<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransaksiProduksiService
{
    public function prosesBatchProduksi(
        int $finishedProductId,
        float $quantityProduced,
        array $materialsConsumed,
        float $laborOverheadCost,
        int $usahaId
    ): Product
    {
        return DB::transaction(function () use (
            $finishedProductId,
            $quantityProduced,
            $materialsConsumed,
            $laborOverheadCost,
            $usahaId
        ) {

            $totalMaterialCost = 0.0;

            foreach ($materialsConsumed as $material) {
                $materialProduct = Product::where('id', $material['id'])
                    ->where('usaha_id', $usahaId)
                    ->firstOrFail();

                $qtyConsumed = (float)$material['qty'];

                if ($materialProduct->stok < $qtyConsumed) {
                    throw new \Exception("Stok {$materialProduct->nama} tidak mencukupi untuk produksi. Stok tersedia: {$materialProduct->stok}, Dibutuhkan: {$qtyConsumed}.");
                }

                $hppMaterial = (float)$materialProduct->hpp_unit_rata2;

                $materialProduct->stok -= $qtyConsumed;
                $materialProduct->save();

                $cost = $hppMaterial * $qtyConsumed;
                $totalMaterialCost += $cost;
            }

            $totalCostOfGoods = $totalMaterialCost + $laborOverheadCost;

            $product = Product::where('id', $finishedProductId)
                ->where('usaha_id', $usahaId)
                ->firstOrFail();

            $stokLama = (float) $product->stok;
            $hppLama = (float) $product->hpp_unit_rata2;

            $nilaiPersediaanLama = $stokLama * $hppLama;
            $nilaiProduksiBaru = $totalCostOfGoods;

            $stokTotalBaru = $stokLama + $quantityProduced;

            if ($stokTotalBaru > 0) {
                $hppUnitBaru = ($nilaiPersediaanLama + $nilaiProduksiBaru) / $stokTotalBaru;
            } else {
                $hppUnitBaru = $laborOverheadCost / 1;
            }

            $product->stok = $stokTotalBaru;
            $product->hpp_unit_rata2 = $hppUnitBaru;
            $product->save();

            return $product;
        });
    }
}
