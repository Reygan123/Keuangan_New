<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['transaksi.pelanggan', 'transaksi.supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $transaksis = Transaksi::with(['pelanggan', 'supplier'])
            ->whereDoesntHave('invoice')
            ->get();

        return view('admin.invoices.create', compact('transaksis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_invoice' => 'required|string|unique:invoices,nomor_invoice',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_pajak' => 'nullable|numeric|min:0',
            'terms_pembayaran' => 'nullable|string',
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::create($validated);

            DB::commit();

            return redirect()->route('admin.invoices.index')
                ->with('success', 'Invoice berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load([
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.detailProduks',
            'transaksi.label',
            'transaksi.akunPayment',
            'transaksi.akunLawan'
        ]);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $transaksis = Transaksi::with(['pelanggan', 'supplier'])
            ->where(function($query) use ($invoice) {
                $query->whereDoesntHave('invoice')
                    ->orWhere('id', $invoice->transaksi_id);
            })
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'transaksis'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_invoice' => 'required|string|unique:invoices,nomor_invoice,' . $invoice->id,
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_pajak' => 'nullable|numeric|min:0',
            'terms_pembayaran' => 'nullable|string',
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ]);

        try {
            DB::beginTransaction();

            $invoice->update($validated);

            DB::commit();

            return redirect()->route('admin.invoices.index')
                ->with('success', 'Invoice berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();

            return redirect()->route('admin.invoices.index')
                ->with('success', 'Invoice berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    public function generateNomorInvoice()
    {
        $prefix = 'INV';
        $date = date('Ymd');

        $lastInvoice = Invoice::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->nomor_invoice, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return response()->json([
            'nomor_invoice' => $prefix . '/' . $date . '/' . $newNumber
        ]);
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ]);

        $invoice->update($validated);

        return back()->with('success', 'Status invoice berhasil diupdate.');
    }
}
