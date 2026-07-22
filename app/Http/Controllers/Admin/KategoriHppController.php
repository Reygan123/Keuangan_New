<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriHpp;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KategoriHppController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = KategoriHpp::query();

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
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        $kategoriHpps = $query->latest()->get();

        return view('admin.kategori_hpps.index', compact('kategoriHpps', 'usahas', 'usahaSelected'));
    }

    public function create()
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();

        if ($usahas->count() == 0) {
            return redirect()->route('admin.kategori_hpps.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        return view('admin.kategori_hpps.create', compact('usahas'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        $validated['usaha_id'] = $request->get('usaha_id');

        if (!$currentUser->usahas()->where('usahas.id', $validated['usaha_id'])->exists()) {
            return redirect()->route('admin.kategori_hpps.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $existing = KategoriHpp::where('usaha_id', $validated['usaha_id'])
            ->where('name', $validated['name'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Kategori HPP dengan nama ini sudah ada di usaha ini.')->withInput();
        }

        KategoriHpp::create($validated);

        return redirect()->route('admin.kategori_hpps.index')->with('success', 'Kategori HPP berhasil ditambahkan.');
    }

    public function edit(KategoriHpp $kategoriHpp)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kategoriHpp->usaha_id)->exists()) {
            return redirect()->route('admin.kategori_hpps.index')->with('error', 'Anda tidak memiliki akses ke kategori HPP ini.');
        }

        $usahas = $currentUser->usahas()->get();

        return view('admin.kategori_hpps.edit', compact('kategoriHpp', 'usahas'));
    }

    public function update(Request $request, KategoriHpp $kategoriHpp)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kategoriHpp->usaha_id)->exists()) {
            return redirect()->route('admin.kategori_hpps.index')->with('error', 'Anda tidak memiliki akses ke kategori HPP ini.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_hpps', 'name')
                    ->where('usaha_id', $kategoriHpp->usaha_id)
                    ->ignore($kategoriHpp->id),
            ],
            'kategori' => 'required|string|max:50',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        if (!$currentUser->usahas()->where('usahas.id', $validated['usaha_id'])->exists()) {
            return redirect()->route('admin.kategori_hpps.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $kategoriHpp->update($validated);

        return redirect()->route('admin.kategori_hpps.index')->with('success', 'Kategori HPP berhasil diperbarui.');
    }

    public function destroy(KategoriHpp $kategoriHpp)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kategoriHpp->usaha_id)->exists()) {
            return back()->with('error', 'Anda tidak memiliki akses ke kategori HPP ini.');
        }

        if ($kategoriHpp->products()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus. Kategori ini masih digunakan oleh beberapa produk.');
        }

        $kategoriHpp->delete();

        return redirect()->route('admin.kategori_hpps.index')->with('success', 'Kategori HPP berhasil dihapus.');
    }
}
