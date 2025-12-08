<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TransaksiProduksiService;
use App\Models\Product;
use Illuminate\Http\Request;

class TransaksiProduksiController extends Controller
{
    protected $produksiService;

    public function __construct(TransaksiProduksiService $produksiService)
    {
        $this->produksiService = $produksiService;
    }

    /**
     * Menampilkan formulir produksi (Input Material dan Output Produk Jadi).
     */
    public function create()
    {
        // Untuk form, kita asumsikan semua produk bisa menjadi material atau finished product
        $products = Product::select('id', 'nama', 'stok', 'satuan_unit')->get();
        return view('admin.produksi.create_full', compact('products'));
    }

    /**
     * Memproses input produksi.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Utama
        $request->validate([
            'finished_product_id' => 'required|integer|exists:products,id',
            'quantity_produced' => 'required|numeric|min:0.01',
            'labor_overhead_cost' => 'required|numeric|min:0',
            // Validasi untuk material/bahan baku (dibuat array)
            'material_ids.*' => 'required|exists:products,id',
            'material_quantities.*' => 'required|numeric|min:0.01',
        ], [
            'finished_product_id.required' => 'Produk Jadi (Output) wajib diisi.',
            'quantity_produced.required' => 'Kuantitas hasil produksi wajib diisi.',
            'material_ids.*.required' => 'ID Material yang digunakan wajib diisi.',
            'material_quantities.*.required' => 'Kuantitas material yang digunakan wajib diisi.',
        ]);

        // 2. Format Data Material Consumed untuk Service
        $materialsConsumed = [];
        $materialIds = $request->input('material_ids');
        $materialQuantities = $request->input('material_quantities');

        if (is_array($materialIds) && is_array($materialQuantities)) {
            $count = count($materialIds);
            for ($i = 0; $i < $count; $i++) {
                if (isset($materialIds[$i]) && isset($materialQuantities[$i])) {
                    $materialsConsumed[] = [
                        'id' => (int)$materialIds[$i],
                        'qty' => (float)$materialQuantities[$i],
                        // Cost per unit tidak diinput, tapi diambil dari HPP produk di service
                    ];
                }
            }
        }

        // Cek jika tidak ada material yang diinput
        if (empty($materialsConsumed)) {
            return redirect()->back()->withInput()->withErrors(['material' => 'Minimal satu jenis material harus diinput.']);
        }

        try {
            // 3. Panggil Service Produksi
            $product = $this->produksiService->prosesBatchProduksi(
                $request->finished_product_id,
                $request->quantity_produced,
                $materialsConsumed,
                (float)$request->labor_overhead_cost
            );

            return redirect()->route('admin.produksi.create')->with('success',
                "Batch Produksi '{$product->nama}' berhasil diselesaikan.
                Stok baru: {$product->stok}. HPP Unit baru: Rp " . number_format($product->hpp_unit_rata2, 2)
            );

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['produksi' => $e->getMessage()]);
        }
    }
}
