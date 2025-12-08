<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::latest()->get();
        return view('admin.pelanggans.index', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggans,email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggans,email,' . $pelanggan->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('admin.pelanggans.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
