<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabelTransaksi;
use App\Models\Usaha;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = LabelTransaksi::query();

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
                $q->where('nama_label', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('tipe_utama', 'like', '%' . $search . '%');
            });
        }

        $labels = $query->latest()->get();

        return view('admin.label_transaksis.index', compact('labels', 'usahas', 'usahaSelected'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.label_transaksis.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $usahaId = $request->get('usaha_id', $currentUsaha->id);

        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return redirect()->route('admin.label_transaksis.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $validated = $request->validate([
            'nama_label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_utama' => 'required|string|in:PENJUALAN,PEMBELIAN,PENGELUARAN,PENERIMAAN,ASET,INTERNAL,PRODUKSI',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        $validated['usaha_id'] = $usahaId;

        LabelTransaksi::create($validated);

        return redirect()->route('admin.label_transaksis.index', ['usaha_id' => $usahaId])->with('success', 'Label transaksi berhasil ditambahkan.');
    }

    public function update(Request $request, LabelTransaksi $label_transaksi)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $label_transaksi->usaha_id)->exists()) {
            return redirect()->route('admin.label_transaksis.index')->with('error', 'Anda tidak memiliki akses ke label ini.');
        }

        $validated = $request->validate([
            'nama_label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_utama' => 'required|string|in:PENJUALAN,PEMBELIAN,PENGELUARAN,PENERIMAAN,ASET,INTERNAL,PRODUKSI',
        ]);

        $label_transaksi->update($validated);

        return redirect()->route('admin.label_transaksis.index', ['usaha_id' => $label_transaksi->usaha_id])->with('success', 'Label transaksi berhasil diperbarui.');
    }

    public function destroy(LabelTransaksi $label_transaksi)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $label_transaksi->usaha_id)->exists()) {
            return back()->with('error', 'Anda tidak memiliki akses ke label ini.');
        }

        try {
            $label_transaksi->delete();
            return redirect()->route('admin.label_transaksis.index', ['usaha_id' => $label_transaksi->usaha_id])->with('success', 'Label transaksi berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $errorCode = $e->errorInfo[1] ?? null;
                if ($errorCode == 1451) {
                    return redirect()->route('admin.label_transaksis.index', ['usaha_id' => $label_transaksi->usaha_id])
                        ->with('error', 'Tidak dapat menghapus label ini karena masih digunakan dalam aturan automasi atau transaksi. Hapus terlebih dahulu data yang terkait.');
                }
            }

            return redirect()->route('admin.label_transaksis.index', ['usaha_id' => $label_transaksi->usaha_id])
                ->with('error', 'Terjadi kesalahan saat menghapus label. Silakan coba lagi.');
        }
    }
}
