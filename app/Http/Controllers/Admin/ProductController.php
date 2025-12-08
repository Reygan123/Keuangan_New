<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\KategoriHpp;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua Produk.
     */
    public function index()
    {
        // Eager load semua relasi yang dibutuhkan untuk tampilan tabel
        $products = Product::with(['kategoriHpp', 'akunPendapatan', 'akunPersediaan', 'akunHpp'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk membuat Produk baru.
     */
    public function create()
    {
        $kategoriHpps = KategoriHpp::all();
        $akunPendapatan = Akun::where('klasifikasi', 'PENDAPATAN')->get();
        // Akun Persediaan (ASET, biasanya kategori 1xxx)
        $akunPersediaan = Akun::where('klasifikasi', 'ASET')->get();
        $akunHpps = Akun::where('klasifikasi', 'BEBAN')->get();

        return view('admin.products.create', compact('kategoriHpps', 'akunPendapatan', 'akunPersediaan', 'akunHpps'));
    }

    /**
     * Menyimpan Produk baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:products,nama',
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'hpp_unit_rata2' => 'nullable|numeric|min:0',
            'akun_pendapatan_id' => 'required|exists:akuns,id',
            'akun_persediaan_id' => 'required|exists:akuns,id',
            'akun_hpp_id' => 'required|exists:akuns,id',
            'satuan_unit' => 'required|string|max:50',
            'stok' => 'nullable|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit Produk.
     */
    public function edit(Product $product)
    {
        $kategoriHpps = KategoriHpp::all();
        $akunPendapatan = Akun::where('klasifikasi', 'PENDAPATAN')->get();
        $akunPersediaan = Akun::where('klasifikasi', 'ASET')->get();

        $akunHpps = Akun::where('klasifikasi', 'BEBAN')->get();

        return view('admin.products.edit', compact('product', 'kategoriHpps', 'akunPendapatan', 'akunPersediaan', 'akunHpps'));
    }

    /**
     * Memperbarui Produk di database.
     */
    public function update(Request $request, Product $product)
    {
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

    /**
     * Menghapus Produk dari database.
     */
    public function destroy(Product $product)
    {
        // Peringatan: Anda mungkin ingin memeriksa apakah produk ini sudah terlibat dalam transaksi
        if ($product->transaksiDetails()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus. Produk ini sudah memiliki riwayat transaksi.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
