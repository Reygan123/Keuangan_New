<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TransaksiProduksiService;
use App\Models\Product;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiProduksiController extends Controller
{
    protected $produksiService;

    public function __construct(TransaksiProduksiService $produksiService)
    {
        $this->produksiService = $produksiService;
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        $products = collect();
        if ($selectedUsahaId) {
            $products = Product::where('usaha_id', $selectedUsahaId)
                ->select('id', 'nama', 'stok', 'satuan_unit', 'hpp_unit_rata2')
                ->get();
        }

        return view('admin.produksi.create_full', compact('products', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.produksi.create')->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.produksi.create')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $request->validate([
            'finished_product_id' => 'required|integer|exists:products,id',
            'quantity_produced' => 'required|numeric|min:0.01',
            'labor_overhead_cost' => 'required|numeric|min:0',
            'material_ids.*' => 'required|exists:products,id',
            'material_quantities.*' => 'required|numeric|min:0.01',
        ], [
            'finished_product_id.required' => 'Produk Jadi (Output) wajib diisi.',
            'quantity_produced.required' => 'Kuantitas hasil produksi wajib diisi.',
            'material_ids.*.required' => 'ID Material yang digunakan wajib diisi.',
            'material_quantities.*.required' => 'Kuantitas material yang digunakan wajib diisi.',
        ]);

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
                    ];
                }
            }
        }

        if (empty($materialsConsumed)) {
            return redirect()->back()->withInput()->withErrors(['material' => 'Minimal satu jenis material harus diinput.']);
        }

        try {
            $product = $this->produksiService->prosesBatchProduksi(
                $request->finished_product_id,
                $request->quantity_produced,
                $materialsConsumed,
                (float)$request->labor_overhead_cost,
                $selectedUsahaId
            );

            return redirect()->route('admin.produksi.create', ['usaha_id' => $selectedUsahaId])->with('success',
                "Batch Produksi '{$product->nama}' berhasil diselesaikan.
                Stok baru: {$product->stok}. HPP Unit baru: Rp " . number_format($product->hpp_unit_rata2, 2)
            );

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['produksi' => $e->getMessage()]);
        }
    }
}
