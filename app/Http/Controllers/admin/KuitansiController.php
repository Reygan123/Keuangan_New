<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuitansi;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KuitansiController extends Controller
{
    public function create()
    {
        $transaksis = Transaksi::all();
        return view('admin.kuitansi.create', compact('transaksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_kuitansi' => 'required|string|unique:kuitansis',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'jumlah_dibayar' => 'required|numeric',
            'tanda_tangan_penerima' => 'required|string'
        ]);

        Kuitansi::create($request->all());

        return redirect()->route('admin.kuitansi.index')->with('success', 'Kuitansi berhasil dibuat');
    }

    public function edit($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $transaksis = Transaksi::all();
        return view('admin.kuitansi.edit', compact('kuitansi', 'transaksis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_kuitansi' => 'required|string|unique:kuitansis,nomor_kuitansi,' . $id,
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'jumlah_dibayar' => 'required|numeric',
            'tanda_tangan_penerima' => 'required|string'
        ]);

        $kuitansi = Kuitansi::findOrFail($id);
        $kuitansi->update($request->all());

        return redirect()->route('admin.kuitansi.index')->with('success', 'Kuitansi berhasil diupdate');
    }

    public function index()
    {
        $kuitansis = Kuitansi::with('transaksi')->get();
        return view('admin.kuitansi.index', compact('kuitansis'));
    }

    public function show($id)
    {
        $kuitansi = Kuitansi::with([
            'transaksi',
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.akunPayment',
            'transaksi.akunLawan',
            'transaksi.detailProduks'
        ])->findOrFail($id);

        return view('admin.kuitansi.show', compact('kuitansi'));
    }

    public function print($id)
    {
        $kuitansi = Kuitansi::with([
            'transaksi',
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.akunPayment',
            'transaksi.akunLawan',
            'transaksi.detailProduks'
        ])->findOrFail($id);

        return view('admin.kuitansi.print', compact('kuitansi'));
    }

    public function destroy($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $kuitansi->delete();

        return redirect()->route('admin.kuitansi.index')->with('success', 'Kuitansi berhasil dihapus');
    }
}
