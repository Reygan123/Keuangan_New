<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\KategoriHpp;
use App\Models\Akun;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function getKategoriHppByUsaha(Request $request)
    {
        $usahaId = $request->get('usaha_id');

        $kategoriHpps = KategoriHpp::where('usaha_id', $usahaId)->get();

        return response()->json($kategoriHpps);
    }

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = Product::with(['kategoriHpp', 'akunPendapatan', 'akunPersediaan', 'akunHpp', 'usaha']);

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
            }
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $products = $query->latest()->get();

        return view('admin.products.index', compact('products', 'usahas', 'usahaSelected'));
    }

    public function create()
    {
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.products.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $usahas = $currentUser->usahas()->get();
        $kategoriHpps = KategoriHpp::where('usaha_id', $currentUsaha->id)->get();
        $akunPendapatan = Akun::where('usaha_id', $currentUsaha->id)->where('klasifikasi', 'PENDAPATAN')->get();
        $akunPersediaan = Akun::where('usaha_id', $currentUsaha->id)->where('klasifikasi', 'ASET')->get();
        $akunHpps = Akun::where('usaha_id', $currentUsaha->id)->where('klasifikasi', 'BEBAN')->get();

        return view('admin.products.create', compact('kategoriHpps', 'akunPendapatan', 'akunPersediaan', 'akunHpps', 'usahas'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.products.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $usahaId = $request->get('usaha_id', $currentUsaha->id);

        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return redirect()->route('admin.products.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $request->validate([
            'nama' => 'required|string|max:255|unique:products,nama',
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'hpp_unit_rata2' => 'nullable|numeric|min:0',
            'akun_pendapatan_id' => 'required|exists:akuns,id',
            'akun_persediaan_id' => 'required|exists:akuns,id',
            'akun_hpp_id' => 'required|exists:akuns,id',
            'satuan_unit' => 'required|string|max:50',
            'stok' => 'nullable|integer|min:0',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        $data = $request->all();
        $data['usaha_id'] = $usahaId;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $product->usaha_id)->exists()) {
            return redirect()->route('admin.products.index')->with('error', 'Anda tidak memiliki akses ke produk ini.');
        }

        $usahas = $currentUser->usahas()->get();
        $kategoriHpps = KategoriHpp::where('usaha_id', $product->usaha_id)->get();
        $akunPendapatan = Akun::where('usaha_id', $product->usaha_id)->where('klasifikasi', 'PENDAPATAN')->get();
        $akunPersediaan = Akun::where('usaha_id', $product->usaha_id)->where('klasifikasi', 'ASET')->get();
        $akunHpps = Akun::where('usaha_id', $product->usaha_id)->where('klasifikasi', 'BEBAN')->get();

        return view('admin.products.edit', compact('product', 'kategoriHpps', 'akunPendapatan', 'akunPersediaan', 'akunHpps', 'usahas'));
    }

    public function update(Request $request, Product $product)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $product->usaha_id)->exists()) {
            return redirect()->route('admin.products.index')->with('error', 'Anda tidak memiliki akses ke produk ini.');
        }

        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'nama')->ignore($product->id),
            ],
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'hpp_unit_rata2' => 'nullable|numeric|min:0',
            'akun_pendapatan_id' => 'required|exists:akuns,id',
            'akun_persediaan_id' => 'required|exists:akuns,id',
            'akun_hpp_id' => 'required|exists:akuns,id',
            'satuan_unit' => 'required|string|max:50',
            'stok' => 'nullable|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $product->usaha_id)->exists()) {
            return back()->with('error', 'Anda tidak memiliki akses ke produk ini.');
        }

        if ($product->transaksiDetails()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus. Produk ini sudah memiliki riwayat transaksi.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
