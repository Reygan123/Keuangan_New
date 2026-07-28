<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AturanPajak;
use App\Models\JenisPajak;
use App\Models\LabelTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AturanPajakController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

        $aturanPajaks = collect();
        $labelTransaksis = collect();
        $jenisPajaks = collect();

        if ($usahaSelected) {
            $aturanPajaks = AturanPajak::with(['label', 'product', 'jenisPajak'])
                ->where('usaha_id', $usahaSelected)
                ->orderBy('id', 'desc')
                ->get();

            $labelTransaksis = LabelTransaksi::where('usaha_id', $usahaSelected)
                ->orderBy('nama_label')
                ->get();

            $jenisPajaks = JenisPajak::where('usaha_id', $usahaSelected)
                ->where('aktif', true)
                ->orderBy('nama')
                ->get();
        }

        return view('admin.aturan-pajak.index', compact(
            'aturanPajaks', 'labelTransaksis', 'jenisPajaks', 'usahas', 'usahaSelected'
        ));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $usahaId = $request->input('usaha_id');

        if (!$usahaId || !$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return back()->with('error', 'Usaha tidak valid atau Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'label_id'       => 'nullable|exists:label_transaksis,id',
            'product_id'     => 'nullable|exists:products,id',
            'jenis_pajak_id' => 'required|exists:jenis_pajaks,id',
            'tarif_override' => 'nullable|numeric|min:0|max:100',
        ]);

        if (empty($validated['label_id']) && empty($validated['product_id'])) {
            return back()->with('error', 'Pilih minimal Label Transaksi atau Produk sebagai target aturan.')->withInput();
        }

        $exists = AturanPajak::where('usaha_id', $usahaId)
            ->where('jenis_pajak_id', $validated['jenis_pajak_id'])
            ->where('label_id', $validated['label_id'] ?? null)
            ->where('product_id', $validated['product_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Aturan pajak dengan kombinasi ini sudah ada.')->withInput();
        }

        AturanPajak::create([
            'usaha_id'       => $usahaId,
            'label_id'       => $validated['label_id'] ?? null,
            'product_id'     => $validated['product_id'] ?? null,
            'jenis_pajak_id' => $validated['jenis_pajak_id'],
            'tarif_override' => $validated['tarif_override'] ?? null,
        ]);

        return redirect()->route('admin.aturan_pajaks.index', ['usaha_id' => $usahaId])
            ->with('success', 'Aturan pajak berhasil ditambahkan.');
    }

    public function update(Request $request, AturanPajak $aturanPajak)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $aturanPajak->usaha_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'label_id'       => 'nullable|exists:label_transaksis,id',
            'product_id'     => 'nullable|exists:products,id',
            'jenis_pajak_id' => 'required|exists:jenis_pajaks,id',
            'tarif_override' => 'nullable|numeric|min:0|max:100',
        ]);

        if (empty($validated['label_id']) && empty($validated['product_id'])) {
            return back()->with('error', 'Pilih minimal Label Transaksi atau Produk sebagai target aturan.')->withInput();
        }

        $aturanPajak->update([
            'label_id'       => $validated['label_id'] ?? null,
            'product_id'     => $validated['product_id'] ?? null,
            'jenis_pajak_id' => $validated['jenis_pajak_id'],
            'tarif_override' => $validated['tarif_override'] ?? null,
        ]);

        return redirect()->route('admin.aturan_pajaks.index', ['usaha_id' => $aturanPajak->usaha_id])
            ->with('success', 'Aturan pajak berhasil diperbarui.');
    }

    public function destroy(AturanPajak $aturanPajak)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $aturanPajak->usaha_id)->exists()) {
            abort(403);
        }

        $usahaId = $aturanPajak->usaha_id;
        $aturanPajak->delete();

        return redirect()->route('admin.aturan_pajaks.index', ['usaha_id' => $usahaId])
            ->with('success', 'Aturan pajak berhasil dihapus.');
    }
}