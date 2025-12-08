<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriHpp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriHppController extends Controller
{
    /**
     * Menampilkan daftar semua Kategori HPP.
     */
    public function index()
    {
        $kategoriHpps = KategoriHpp::latest()->get();
        return view('admin.kategori_hpps.index', compact('kategoriHpps'));
    }

    /**
     * Menampilkan form untuk membuat Kategori HPP baru.
     */
    public function create()
    {
        return view('admin.kategori_hpps.create');
    }

    /**
     * Menyimpan Kategori HPP baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kategori_hpps,name',
            'kategori' => 'required|string|max:50',
        ]);

        KategoriHpp::create($request->all());

        return redirect()->route('admin.kategori_hpps.index')->with('success', 'Kategori HPP berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit Kategori HPP.
     */
    public function edit(KategoriHpp $kategoriHpp)
    {
        return view('admin.kategori_hpps.edit', compact('kategoriHpp'));
    }

    /**
     * Memperbarui Kategori HPP di database.
     */
    public function update(Request $request, KategoriHpp $kategoriHpp)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan nama unik kecuali nama kategori itu sendiri
                Rule::unique('kategori_hpps', 'name')->ignore($kategoriHpp->id),
            ],
            'kategori' => 'required|string|max:50',
        ]);

        $kategoriHpp->update($request->only(['name', 'kategori']));

        return redirect()->route('admin.kategori_hpps.index')->with('success', 'Kategori HPP berhasil diperbarui.');
    }

    /**
     * Menghapus Kategori HPP dari database.
     */
    public function destroy(KategoriHpp $kategoriHpp)
    {
        // Peringatan: Memeriksa relasi Produk sebelum menghapus
        if ($kategoriHpp->products()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus. Kategori ini masih digunakan oleh beberapa produk.');
        }

        $kategoriHpp->delete();

        return redirect()->route('admin.kategori_hpps.index')->with('success', 'Kategori HPP berhasil dihapus.');
    }
}
