<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriHpp;
use App\Models\KategoriHppTambahan;
use App\Models\Akun;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KategoriHppTambahanController extends Controller
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

        $query = KategoriHppTambahan::with(['kategoriHpp', 'akunBiaya', 'usaha']);

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
            $query->where('name', 'like', '%' . $search . '%');
        }

        $kategoriHppTambahans = $query->latest()->get();

        return view('admin.kategori_hpp_tambahan.index', compact('kategoriHppTambahans', 'usahas', 'usahaSelected'));
    }

    public function create()
    {
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.kategori_hpp_tambahan.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $usahas = $currentUser->usahas()->get();
        $kategoriHpps = KategoriHpp::where('usaha_id', $currentUsaha->id)->get();
        $akunBiaya = Akun::where('usaha_id', $currentUsaha->id)->where('klasifikasi', 'BEBAN')->get();

        return view('admin.kategori_hpp_tambahan.create', compact('kategoriHpps', 'akunBiaya', 'usahas'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.kategori_hpp_tambahan.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $usahaId = $request->get('usaha_id', $currentUsaha->id);

        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return redirect()->route('admin.kategori_hpp_tambahan.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'unit_cost' => 'required|numeric|min:0',
            'akun_biaya_id' => 'required|exists:akuns,id',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        $existing = KategoriHppTambahan::where('usaha_id', $usahaId)
            ->where('name', $request->name)
            ->first();

        if ($existing) {
            return back()->with('error', 'Biaya tambahan dengan nama ini sudah ada di usaha ini.')->withInput();
        }

        $data = $request->all();
        $data['usaha_id'] = $usahaId;

        KategoriHppTambahan::create($data);

        return redirect()->route('admin.kategori_hpp_tambahan.index')->with('success', 'Biaya tambahan berhasil ditambahkan.');
    }

    public function edit(KategoriHppTambahan $kategoriHppTambahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kategoriHppTambahan->usaha_id)->exists()) {
            return redirect()->route('admin.kategori_hpp_tambahan.index')->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        $usahas = $currentUser->usahas()->get();
        $kategoriHpps = KategoriHpp::where('usaha_id', $kategoriHppTambahan->usaha_id)->get();
        $akunBiaya = Akun::where('usaha_id', $kategoriHppTambahan->usaha_id)->where('klasifikasi', 'BEBAN')->get();

        return view('admin.kategori_hpp_tambahan.edit', compact('kategoriHppTambahan', 'kategoriHpps', 'akunBiaya', 'usahas'));
    }

    public function update(Request $request, KategoriHppTambahan $kategoriHppTambahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kategoriHppTambahan->usaha_id)->exists()) {
            return redirect()->route('admin.kategori_hpp_tambahan.index')->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_hpp_tambahans', 'name')
                    ->where('usaha_id', $kategoriHppTambahan->usaha_id)
                    ->ignore($kategoriHppTambahan->id),
            ],
            'kategori_hpp_id' => 'required|exists:kategori_hpps,id',
            'unit_cost' => 'required|numeric|min:0',
            'akun_biaya_id' => 'required|exists:akuns,id',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        if (!$currentUser->usahas()->where('usahas.id', $request->usaha_id)->exists()) {
            return redirect()->route('admin.kategori_hpp_tambahan.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $kategoriHppTambahan->update($request->all());

        return redirect()->route('admin.kategori_hpp_tambahan.index')->with('success', 'Biaya tambahan berhasil diperbarui.');
    }

    public function destroy(KategoriHppTambahan $kategoriHppTambahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kategoriHppTambahan->usaha_id)->exists()) {
            return back()->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        $kategoriHppTambahan->delete();

        return redirect()->route('admin.kategori_hpp_tambahan.index')->with('success', 'Biaya tambahan berhasil dihapus.');
    }
}
