<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaksi;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
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
            $invoices = collect();
        } else {
            $invoices = Invoice::with(['transaksi.pelanggan', 'transaksi.supplier'])
                ->where('usaha_id', $currentUsaha->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.invoices.index', compact('invoices', 'usahas', 'currentUsaha'));
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
            $transaksis = Transaksi::with(['pelanggan', 'supplier'])
                ->where('usaha_id', $currentUsaha->id)
                ->whereDoesntHave('invoice')
                ->get();
        }

        return view('admin.invoices.create', compact('transaksis', 'usahas', 'currentUsaha'));
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
            'nomor_invoice' => 'required|string|unique:invoices,nomor_invoice',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_pajak' => 'nullable|numeric|min:0',
            'terms_pembayaran' => 'nullable|string',
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ]);

        $validated['usaha_id'] = $currentUsaha->id;

        try {
            DB::beginTransaction();

            $invoice = Invoice::create($validated);

            DB::commit();

            return redirect()->route('admin.invoices.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Invoice berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

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

    public function edit(Invoice $invoice, Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($invoice->usaha_id);
        $usahas = $currentUser->usahas()->get();

        $transaksis = Transaksi::with(['pelanggan', 'supplier'])
            ->where('usaha_id', $invoice->usaha_id)
            ->where(function($query) use ($invoice) {
                $query->whereDoesntHave('invoice')
                    ->orWhere('id', $invoice->transaksi_id);
            })
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'transaksis', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

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

            return redirect()->route('admin.invoices.index', ['usaha_id' => $invoice->usaha_id])
                ->with('success', 'Invoice berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Invoice $invoice)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        try {
            $invoice->delete();

            return redirect()->route('admin.invoices.index', ['usaha_id' => $invoice->usaha_id])
                ->with('success', 'Invoice berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    public function generateNomorInvoice(Request $request)
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
            return response()->json(['nomor_invoice' => 'PILIH-USAH']);
        }

        $prefix = 'INV';
        $date = date('Ymd');

        $lastInvoice = Invoice::whereDate('created_at', today())
            ->where('usaha_id', $currentUsaha->id)
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
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ]);

        $invoice->update($validated);

        return back()->with('success', 'Status invoice berhasil diupdate.');
    }
}
