<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabelTransaksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LabelTransaksiController extends Controller
{
    public function index()
    {
        $labels = LabelTransaksi::latest()->get();
        return view('admin.label_transaksis.index', compact('labels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_utama' => 'required|string|in:PENJUALAN,PEMBELIAN,PENGELUARAN,PENERIMAAN,ASET,INTERNAL,PRODUKSI',
        ]);

        LabelTransaksi::create($validated);

        return redirect()->route('admin.label_transaksis.index')->with('success', 'Label transaksi berhasil ditambahkan.');
    }

    public function update(Request $request, LabelTransaksi $label_transaksi)
    {
        $validated = $request->validate([
            'nama_label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_utama' => 'required|string|in:PENJUALAN,PEMBELIAN,PENGELUARAN,PENERIMAAN,ASET,INTERNAL,PRODUKSI',
        ]);

        $label_transaksi->update($validated);

        return redirect()->route('admin.label_transaksis.index')->with('success', 'Label transaksi berhasil diperbarui.');
    }

    public function destroy(LabelTransaksi $label_transaksi)
    {
        try {
            $label_transaksi->delete();
            return redirect()->route('admin.label_transaksis.index')->with('success', 'Label transaksi berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $errorCode = $e->errorInfo[1] ?? null;
                if ($errorCode == 1451) {
                    return redirect()->route('admin.label_transaksis.index')
                        ->with('error', 'Tidak dapat menghapus label ini karena masih digunakan dalam aturan automasi atau transaksi. Hapus terlebih dahulu data yang terkait.');
                }
            }

            return redirect()->route('admin.label_transaksis.index')
                ->with('error', 'Terjadi kesalahan saat menghapus label. Silakan coba lagi.');
        }
    }
}
