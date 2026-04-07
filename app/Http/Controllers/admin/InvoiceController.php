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
        /** @var \App\Models\User $currentUser */
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
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        $transaksis = collect();
        if ($currentUsaha) {
            $transaksis = Transaksi::with(['pelanggan', 'supplier'])
                ->where('usaha_id', $currentUsaha->id)
                ->whereDoesntHave('invoice')
                ->get();
        }

        return view('admin.invoices.create', compact('transaksis', 'usahas', 'currentUsaha'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $currentUser */
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

        $rules = [
            'nomor_invoice' => 'required|string|unique:invoices,nomor_invoice',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_pajak' => 'nullable|numeric|min:0',
            'terms_pembayaran' => 'nullable|string',
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ];

        if ($request->has('transaksi_id') && $request->transaksi_id != '') {
            $rules['transaksi_id'] = 'required|exists:transaksis,id';
            $transaksiRequired = true;
        } else {
            $transaksiRequired = false;
            $rules['to_client_name'] = 'required|string';
            $rules['nama_bank'] = 'nullable|string';
            $rules['nomor_rekening'] = 'nullable|string';
        }

        $validated = $request->validate($rules);
        $validated['usaha_id'] = $currentUsaha->id;

        try {
            DB::beginTransaction();

            $invoice = Invoice::create($validated);

            if (!$transaksiRequired) {
                $itemsData = [];
                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        if (!empty($item['description']) && $item['qty'] > 0 && $item['harga'] > 0) {
                            $itemsData[] = [
                                'description' => $item['description'],
                                'qty' => $item['qty'],
                                'harga' => $item['harga'],
                                'total' => $item['qty'] * $item['harga']
                            ];
                        }
                    }
                }

                if (!empty($itemsData)) {
                    $invoice->invoiceItems()->createMany($itemsData);
                }
            }

            DB::commit();

            return redirect()->route('admin.invoices.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Invoice berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Invoice $invoice, Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($invoice->usaha_id);
        $usahas = $currentUser->usahas()->get();

        $transaksis = Transaksi::with(['pelanggan', 'supplier'])
            ->where('usaha_id', $invoice->usaha_id)
            ->where(function ($query) use ($invoice) {
                $query->whereDoesntHave('invoice')
                    ->orWhere('id', $invoice->transaksi_id);
            })
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'transaksis', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $rules = [
            'nomor_invoice' => 'required|string|unique:invoices,nomor_invoice,' . $invoice->id,
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_pajak' => 'nullable|numeric|min:0',
            'terms_pembayaran' => 'nullable|string',
            'status_invoice' => 'required|in:pending,paid,cancelled,overdue'
        ];

        if ($request->has('transaksi_id') && $request->transaksi_id != '') {
            $rules['transaksi_id'] = 'required|exists:transaksis,id';
            $transaksiRequired = true;
        } else {
            $transaksiRequired = false;
            $rules['to_client_name'] = 'required|string';
            $rules['nama_bank'] = 'nullable|string';
            $rules['nomor_rekening'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            if (!$transaksiRequired) {
                $validated['transaksi_id'] = null;
            }

            $invoice->update($validated);

            if (!$transaksiRequired) {
                $invoice->invoiceItems()->delete();

                $itemsData = [];
                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        if (!empty($item['description']) && $item['qty'] > 0 && $item['harga'] > 0) {
                            $itemsData[] = [
                                'description' => $item['description'],
                                'qty' => $item['qty'],
                                'harga' => $item['harga'],
                                'total' => $item['qty'] * $item['harga']
                            ];
                        }
                    }
                }

                if (!empty($itemsData)) {
                    $invoice->invoiceItems()->createMany($itemsData);
                }
            }

            DB::commit();

            return redirect()->route('admin.invoices.index', ['usaha_id' => $invoice->usaha_id])
                ->with('success', 'Invoice berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        /** @var \App\Models\User $currentUser */ 
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
            'transaksi.akunLawan',
            'invoiceItems',
            'usaha'
        ]);

        $templateName = 'pdf';
        if ($invoice->usaha && strtolower($invoice->usaha->nama) === 'jatidiri') {
            $templateName = 'jatidiri';
        }

        return view('admin.invoices.show', compact('invoice', 'templateName'));
    }
}
