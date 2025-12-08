<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriHppTambahan;
use App\Models\KategoriHpp;
use App\Models\Akun; // Import Model Akun
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriHppTambahanController extends Controller
{
    public function index()
    {
        // Eager load relasi kategoriHpp DAN akunBiaya
        $tambahanHpps = KategoriHppTambahan::with(['kategoriHpp', 'akunBiaya'])->latest()->get();
        return view('admin.kategori_hpp_tambahan.index', compact('tambahanHpps'));
    }

    public function create()
    {
        $kategoriHpps = KategoriHpp::all();
        // Ambil Akun dengan klasifikasi BEBAN (atau Beban Pokok) untuk biaya tambahan
        $akunBiaya = Akun::where('klasifikasi', 'BEBAN')->get();

        return view('admin.kategori_hpp_tambahan.create', compact('kategoriHpps', 'akunBiaya'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_hpp_tambahans', 'name')->where(function ($query) use ($request) {
                    return $query->where('kategori_hpp_id', $request->kategori_hpp_id);
                }),
            ],
            // Aturan validasi baru
            'unit_cost' => 'required|numeric|min:0',
            'akun_biaya_id' => 'required|exists:akuns,id',
        ]);

        KategoriHppTambahan::create($request->all());

        return redirect()->route('admin.kategori_hpp_tambahan.index')->with('success', 'Biaya Tambahan berhasil ditambahkan.');
    }

    public function edit(KategoriHppTambahan $kategoriHppTambahan)
    {
        $kategoriHpps = KategoriHpp::all();
        $akunBiaya = Akun::where('klasifikasi', 'BEBAN')->get();

        return view('admin.kategori_hpp_tambahan.edit', compact('kategoriHppTambahan', 'kategoriHpps', 'akunBiaya'));
    }

    public function update(Request $request, KategoriHppTambahan $kategoriHppTambahan)
    {
        $request->validate([
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_hpp_tambahans', 'name')->where(function ($query) use ($request) {
                    return $query->where('kategori_hpp_id', $request->kategori_hpp_id);
                })->ignore($kategoriHppTambahan->id),
            ],
            // Aturan validasi baru
            'unit_cost' => 'required|numeric|min:0',
            'akun_biaya_id' => 'required|exists:akuns,id',
        ]);

        $kategoriHppTambahan->update($request->all());

        return redirect()->route('admin.kategori_hpp_tambahan.index')->with('success', 'Biaya Tambahan berhasil diperbarui.');
    }

    // Fungsi destroy tetap sama
    public function destroy(KategoriHppTambahan $kategoriHppTambahan)
    {
        $kategoriHppTambahan->delete();
        return redirect()->route('admin.kategori_hpp_tambahan.index')->with('success', 'Biaya Tambahan berhasil dihapus.');
    }
}
