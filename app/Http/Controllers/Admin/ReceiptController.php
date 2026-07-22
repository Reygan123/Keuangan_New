<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\Transaksi;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        if (!$currentUsaha) {
            $receipts = collect();
        } else {
            $receipts = Receipt::with(['transaksi.pelanggan', 'transaksi.supplier'])
                ->where('usaha_id', $currentUsaha->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.receipts.index', compact('receipts', 'usahas', 'currentUsaha'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        if (!$currentUsaha) {
            $transaksis = collect();
        } else {
            $transaksis = Transaksi::with(['pelanggan', 'supplier', 'detailProduks'])
                ->where('usaha_id', $currentUsaha->id)
                ->whereDoesntHave('receipt')
                ->get();
        }

        return view('admin.receipts.create', compact('transaksis', 'usahas', 'currentUsaha'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        if (!$currentUsaha) {
            return back()->with('error', 'Pilih usaha terlebih dahulu')->withInput();
        }

        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_receipt' => 'required|string|unique:receipts,nomor_receipt',
            'mesin_kasir_id' => 'nullable|string',
            'jumlah_dibayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0'
        ]);

        $transaksi = Transaksi::findOrFail($validated['transaksi_id']);

        if ($transaksi->usaha_id != $currentUsaha->id) {
            return back()->with('error', 'Transaksi tidak tersedia untuk usaha ini')->withInput();
        }

        if ($validated['jumlah_dibayar'] < $transaksi->jumlah) {
            return back()->with('error', 'Jumlah dibayar tidak boleh kurang dari total transaksi.')
                ->withInput();
        }

        $validated['usaha_id'] = $currentUsaha->id;

        try {
            DB::beginTransaction();

            $receipt = Receipt::create($validated);

            DB::commit();

            return redirect()->route('admin.receipts.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Receipt berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat receipt: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Receipt $receipt)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $receipt->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $receipt->load([
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.detailProduks.produk',
            'transaksi.label',
            'transaksi.akunPayment',
            'transaksi.akunLawan'
        ]);

        return view('admin.receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt, Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $receipt->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($receipt->usaha_id);
        $usahas = $currentUser->usahas()->get();

        $transaksis = Transaksi::with(['pelanggan', 'supplier'])
            ->where('usaha_id', $receipt->usaha_id)
            ->where(function($query) use ($receipt) {
                $query->whereDoesntHave('receipt')
                    ->orWhere('id', $receipt->transaksi_id);
            })
            ->get();

        return view('admin.receipts.edit', compact('receipt', 'transaksis', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $receipt->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_receipt' => 'required|string|unique:receipts,nomor_receipt,' . $receipt->id,
            'mesin_kasir_id' => 'nullable|string',
            'jumlah_dibayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0'
        ]);

        $transaksi = Transaksi::findOrFail($validated['transaksi_id']);

        if ($transaksi->usaha_id != $receipt->usaha_id) {
            return back()->with('error', 'Transaksi tidak tersedia untuk usaha ini')->withInput();
        }

        if ($validated['jumlah_dibayar'] < $transaksi->jumlah) {
            return back()->with('error', 'Jumlah dibayar tidak boleh kurang dari total transaksi.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $receipt->update($validated);

            DB::commit();

            return redirect()->route('admin.receipts.index', ['usaha_id' => $receipt->usaha_id])
                ->with('success', 'Receipt berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate receipt: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Receipt $receipt)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $receipt->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        try {
            $receipt->delete();

            return redirect()->route('admin.receipts.index', ['usaha_id' => $receipt->usaha_id])
                ->with('success', 'Receipt berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus receipt: ' . $e->getMessage());
        }
    }

    public function generateNomorReceipt(Request $request)
    {
        $currentUser = Auth::user();
        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        if (!$currentUsaha) {
            return response()->json(['nomor_receipt' => 'PILIH-USAH']);
        }

        $prefix = 'RCP';
        $date = date('Ymd');

        $lastReceipt = Receipt::whereDate('created_at', today())
            ->where('usaha_id', $currentUsaha->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = intval(substr($lastReceipt->nomor_receipt, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return response()->json([
            'nomor_receipt' => $prefix . '/' . $date . '/' . $newNumber
        ]);
    }

    public function print(Receipt $receipt)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $receipt->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $receipt->load([
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.detailProduks.produk'
        ]);

        return view('admin.receipts.print', compact('receipt'));
    }

    public function getTransaksiDetails($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'supplier', 'detailProduks.produk'])
            ->findOrFail($id);

        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $transaksi->usaha_id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'jumlah' => $transaksi->jumlah,
            'pelanggan' => $transaksi->pelanggan ? $transaksi->pelanggan->nama : null,
            'supplier' => $transaksi->supplier ? $transaksi->supplier->nama : null,
            'detail_produk' => $transaksi->detailProduks
        ]);
    }
}
