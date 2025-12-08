<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use Illuminate\Http\Request;

class UsahaController extends Controller
{
    public function index()
    {
        $usaha = Usaha::first();
        return view('admin.usahas.index', compact('usaha'));
    }

    public function store(Request $request)
    {
        if (Usaha::exists()) {
            return redirect()->route('admin.usahas.index')->with('error', 'Hanya boleh ada 1 data usaha');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'faq' => 'nullable|string',
            'website' => 'nullable|url|max:255',
        ]);

        Usaha::create($validated);

        return redirect()->route('admin.usahas.index')->with('success', 'Usaha berhasil ditambahkan.');
    }

    public function update(Request $request, Usaha $usaha)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'faq' => 'nullable|string',
            'website' => 'nullable|url|max:255'
        ]);

        $usaha->update($validated);

        return redirect()->route('admin.usahas.index')->with('success', 'Usaha berhasil diperbarui.');
    }

    public function destroy(Usaha $usaha)
    {
        $usaha->delete();

        return redirect()->route('admin.usahas.index')->with('success', 'Usaha berhasil dihapus.');
    }
}
