<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function create()
    {
        $transaksis = Transaksi::all();
        return view('admin.nota.create', compact('transaksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_nota' => 'required|string|unique:notas',
            'jenis_nota' => 'required|string',
            'is_tunai' => 'required|boolean'
        ]);

        Nota::create($request->all());

        return redirect()->route('admin.nota.index')->with('success', 'Nota berhasil dibuat');
    }

    public function edit($id)
    {
        $nota = Nota::findOrFail($id);
        $transaksis = Transaksi::all();
        return view('admin.nota.edit', compact('nota', 'transaksis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_nota' => 'required|string|unique:notas,nomor_nota,' . $id,
            'jenis_nota' => 'required|string',
            'is_tunai' => 'required|boolean'
        ]);

        $nota = Nota::findOrFail($id);
        $nota->update($request->all());

        return redirect()->route('admin.nota.index')->with('success', 'Nota berhasil diupdate');
    }

    public function index()
    {
        $notas = Nota::with('transaksi')->get();
        return view('admin.nota.index', compact('notas'));
    }

    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->delete();

        return redirect()->route('admin.nota.index')->with('success', 'Nota berhasil dihapus');
    }
}
