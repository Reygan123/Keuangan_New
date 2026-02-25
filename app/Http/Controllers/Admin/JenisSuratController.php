<?php
// app/Http/Controllers/Admin/JenisSuratController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurats = JenisSurat::orderBy('kode_surat')->get();
        return view('admin.jenis-surat.index', compact('jenisSurats'));
    }

    public function create()
    {
        return view('admin.jenis-surat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_surat' => 'required|string|max:5|unique:jenis_surats,kode_surat',
            'initial_code' => 'required|string|max:10',
            'nama_jenis' => 'required|string|max:255',
            'keterangan' => 'required|string'
        ]);

        JenisSurat::create($validated);

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Jenis Surat berhasil ditambahkan.');
    }

    public function show(JenisSurat $jenisSurat)
    {
        return view('admin.jenis-surat.show', compact('jenisSurat'));
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('admin.jenis-surat.edit', compact('jenisSurat'));
    }

    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $validated = $request->validate([
            'kode_surat' => 'required|string|max:5|unique:jenis_surats,kode_surat,' . $jenisSurat->id,
            'initial_code' => 'required|string|max:10',
            'nama_jenis' => 'required|string|max:255',
            'keterangan' => 'required|string'
        ]);

        $jenisSurat->update($validated);

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Jenis Surat berhasil diupdate.');
    }

    public function destroy(JenisSurat $jenisSurat)
    {
        $jenisSurat->delete();

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Jenis Surat berhasil dihapus.');
    }
}
