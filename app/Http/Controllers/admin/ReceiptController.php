<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receipts = Receipt::with(['transaksi.pelanggan', 'transaksi.supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.receipts.index', compact('receipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transaksis = Transaksi::with(['pelanggan', 'supplier', 'detailProduks'])
            ->whereDoesntHave('receipt')
            ->get();

        return view('admin.receipts.create', compact('transaksis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_receipt' => 'required|string|unique:receipts,nomor_receipt',
            'mesin_kasir_id' => 'nullable|string',
            'jumlah_dibayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0'
        ]);

        // Validasi jumlah dibayar harus >= jumlah transaksi
        $transaksi = Transaksi::findOrFail($validated['transaksi_id']);
        if ($validated['jumlah_dibayar'] < $transaksi->jumlah) {
            return back()->with('error', 'Jumlah dibayar tidak boleh kurang dari total transaksi.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $receipt = Receipt::create($validated);

            DB::commit();

            return redirect()->route('admin.receipts.index')
                ->with('success', 'Receipt berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat receipt: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Receipt $receipt)
    {
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receipt $receipt)
    {
        $transaksis = Transaksi::with(['pelanggan', 'supplier'])
            ->where(function($query) use ($receipt) {
                $query->whereDoesntHave('receipt')
                    ->orWhere('id', $receipt->transaksi_id);
            })
            ->get();

        return view('admin.receipts.edit', compact('receipt', 'transaksis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_receipt' => 'required|string|unique:receipts,nomor_receipt,' . $receipt->id,
            'mesin_kasir_id' => 'nullable|string',
            'jumlah_dibayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0'
        ]);

        // Validasi jumlah dibayar
        $transaksi = Transaksi::findOrFail($validated['transaksi_id']);
        if ($validated['jumlah_dibayar'] < $transaksi->jumlah) {
            return back()->with('error', 'Jumlah dibayar tidak boleh kurang dari total transaksi.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $receipt->update($validated);

            DB::commit();

            return redirect()->route('admin.receipts.index')
                ->with('success', 'Receipt berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate receipt: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        try {
            $receipt->delete();

            return redirect()->route('admin.receipts.index')
                ->with('success', 'Receipt berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus receipt: ' . $e->getMessage());
        }
    }

    /**
     * Generate nomor receipt otomatis
     */
    public function generateNomorReceipt()
    {
        $prefix = 'RCP';
        $date = date('Ymd');

        $lastReceipt = Receipt::whereDate('created_at', today())
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

    /**
     * Print receipt
     */
    public function print(Receipt $receipt)
    {
        $receipt->load([
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.detailProduks.produk'
        ]);

        return view('admin.receipts.print', compact('receipt'));
    }

    /**
     * Get transaksi details untuk auto-fill
     */
    public function getTransaksiDetails($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'supplier', 'detailProduks.produk'])
            ->findOrFail($id);

        return response()->json([
            'jumlah' => $transaksi->jumlah,
            'pelanggan' => $transaksi->pelanggan ? $transaksi->pelanggan->nama : null,
            'supplier' => $transaksi->supplier ? $transaksi->supplier->nama : null,
            'detail_produk' => $transaksi->detailProduks
        ]);
    }
}
