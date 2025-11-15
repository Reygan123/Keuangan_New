@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-50">Detail Invoice</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $invoice->nomor_invoice }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="flex items-center gap-2 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path></svg>
                    Edit
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-2 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-900/50 border border-green-700 rounded-lg text-green-200 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Invoice</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Nomor Invoice</p>
                            <p class="text-slate-200 font-medium">{{ $invoice->nomor_invoice }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tanggal Dibuat</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Jatuh Tempo</p>
                            <div class="flex items-center gap-2">
                                <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</p>
                                @php
                                    $jatuhTempo = \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $now->diffInDays($jatuhTempo, false);
                                @endphp
                                @if($diff < 0 && $invoice->status_invoice != 'paid')
                                <span class="px-2 py-1 bg-red-900/50 text-red-200 rounded text-xs font-medium">Terlambat {{ abs($diff) }} hari</span>
                                @elseif($diff >= 0 && $diff <= 7 && $invoice->status_invoice != 'paid')
                                <span class="px-2 py-1 bg-amber-900/50 text-amber-200 rounded text-xs font-medium">{{ $diff }} hari lagi</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-slate-400">Status</p>
                            @switch($invoice->status_invoice)
                                @case('pending')
                                    <span class="inline-block px-2 py-1 bg-amber-900/50 text-amber-200 rounded text-xs font-medium">Pending</span>
                                    @break
                                @case('paid')
                                    <span class="inline-block px-2 py-1 bg-green-900/50 text-green-200 rounded text-xs font-medium">Paid</span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-block px-2 py-1 bg-red-900/50 text-red-200 rounded text-xs font-medium">Cancelled</span>
                                    @break
                                @case('overdue')
                                    <span class="inline-block px-2 py-1 bg-slate-900 text-slate-200 rounded text-xs font-medium">Overdue</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Pihak</h3>
                    <div class="space-y-3 text-sm">
                        @if($invoice->transaksi->pelanggan)
                        <div>
                            <p class="text-slate-400 mb-1">Pelanggan</p>
                            <p class="text-slate-200 font-medium">{{ $invoice->transaksi->pelanggan->nama }}</p>
                            @if($invoice->transaksi->pelanggan->alamat)
                            <p class="text-slate-400 text-xs">{{ $invoice->transaksi->pelanggan->alamat }}</p>
                            @endif
                            @if($invoice->transaksi->pelanggan->telepon)
                            <p class="text-slate-400 text-xs">Tel: {{ $invoice->transaksi->pelanggan->telepon }}</p>
                            @endif
                        </div>
                        @elseif($invoice->transaksi->supplier)
                        <div>
                            <p class="text-slate-400 mb-1">Supplier</p>
                            <p class="text-slate-200 font-medium">{{ $invoice->transaksi->supplier->nama }}</p>
                            @if($invoice->transaksi->supplier->alamat)
                            <p class="text-slate-400 text-xs">{{ $invoice->transaksi->supplier->alamat }}</p>
                            @endif
                            @if($invoice->transaksi->supplier->telepon)
                            <p class="text-slate-400 text-xs">Tel: {{ $invoice->transaksi->supplier->telepon }}</p>
                            @endif
                        </div>
                        @endif
                        <div>
                            <p class="text-slate-400">Tanggal Transaksi</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($invoice->transaksi->tanggal)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Detail Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-700/50 border-b border-slate-600">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">Keterangan</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">Label</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">Akun Payment</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">Akun Lawan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-3 py-2 text-slate-300">{{ $invoice->transaksi->keterangan ?? '-' }}</td>
                                <td class="px-3 py-2 text-slate-300">{{ $invoice->transaksi->label->nama ?? '-' }}</td>
                                <td class="px-3 py-2 text-slate-300">{{ $invoice->transaksi->akunPayment->nama ?? '-' }}</td>
                                <td class="px-3 py-2 text-slate-300">{{ $invoice->transaksi->akunLawan->nama ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($invoice->transaksi->detailProduks->count() > 0)
            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Produk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-700/50 border-b border-slate-600">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">No</th>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">Produk</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-200">Qty</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-200">Harga</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-200">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($invoice->transaksi->detailProduks as $key => $detail)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-3 py-2 text-slate-300">{{ $key + 1 }}</td>
                                <td class="px-3 py-2 text-slate-300">{{ $detail->produk->nama ?? '-' }}</td>
                                <td class="px-3 py-2 text-right text-slate-300">{{ $detail->qty }}</td>
                                <td class="px-3 py-2 text-right text-slate-300">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right text-slate-300">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($invoice->terms_pembayaran)
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Terms Pembayaran</h3>
                    <div class="bg-slate-700/50 rounded p-3 text-slate-300 text-sm">
                        {{ $invoice->terms_pembayaran }}
                    </div>
                </div>
                @endif

                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Ringkasan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Subtotal</span>
                            <span class="text-slate-200 font-medium">Rp {{ number_format($invoice->transaksi->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Pajak</span>
                            <span class="text-slate-200 font-medium">Rp {{ number_format($invoice->jumlah_pajak ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-600 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-slate-200 font-semibold">Total</span>
                                <span class="text-blue-400 font-bold">Rp {{ number_format($invoice->transaksi->jumlah + ($invoice->jumlah_pajak ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Update Status</h3>
                <form action="{{ route('admin.invoices.update-status', $invoice->id) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Status Invoice</label>
                        <select name="status_invoice" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                            <option value="pending" {{ $invoice->status_invoice == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $invoice->status_invoice == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ $invoice->status_invoice == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="overdue" {{ $invoice->status_invoice == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
