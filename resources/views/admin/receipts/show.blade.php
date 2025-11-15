@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
        <h1 class="text-xl font-semibold text-slate-100">Detail Receipt</h1>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.receipts.edit', $receipt->id) }}" class="px-3 py-2 bg-amber-700 hover:bg-amber-600 text-slate-100 text-sm rounded transition">Edit</a>
            <a href="{{ route('admin.receipts.index') }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-500 bg-opacity-20 text-green-300 text-sm rounded border border-green-500 border-opacity-30">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
        <div class="bg-slate-800 border border-slate-700 rounded p-4">
            <h3 class="text-sm font-semibold text-slate-300 mb-3">Receipt</h3>
            <div class="space-y-2 text-sm text-slate-300">
                <div><span class="text-slate-400">Nomor:</span> {{ $receipt->nomor_receipt }}</div>
                <div><span class="text-slate-400">Tanggal:</span> {{ \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y H:i') }}</div>
                <div><span class="text-slate-400">Mesin:</span> {{ $receipt->mesin_kasir_id ?? '-' }}</div>
                <div><span class="text-slate-400">Kasir:</span> {{ auth()->user()->name ?? 'System' }}</div>
            </div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded p-4">
            <h3 class="text-sm font-semibold text-slate-300 mb-3">Pihak</h3>
            <div class="space-y-2 text-sm text-slate-300">
                @if($receipt->transaksi->pelanggan)
                    <div><span class="text-slate-400">Tipe:</span> <span class="bg-blue-500 bg-opacity-30 px-2 py-1 rounded text-xs text-blue-300">Pelanggan</span></div>
                    <div><span class="text-slate-400">Nama:</span> {{ $receipt->transaksi->pelanggan->nama }}</div>
                    <div><span class="text-slate-400">Telepon:</span> {{ $receipt->transaksi->pelanggan->telepon ?? '-' }}</div>
                @elseif($receipt->transaksi->supplier)
                    <div><span class="text-slate-400">Tipe:</span> <span class="bg-amber-500 bg-opacity-30 px-2 py-1 rounded text-xs text-amber-300">Supplier</span></div>
                    <div><span class="text-slate-400">Nama:</span> {{ $receipt->transaksi->supplier->nama }}</div>
                @else
                    <div><span class="text-slate-400">Tipe:</span> <span class="bg-slate-500 bg-opacity-30 px-2 py-1 rounded text-xs text-slate-300">Umum</span></div>
                @endif
            </div>
        </div>
    </div>

    @if($receipt->transaksi->detailProduks->count() > 0)
    <div class="bg-slate-800 border border-slate-700 rounded p-4 mb-5">
        <h3 class="text-sm font-semibold text-slate-300 mb-3">Detail Pembelian</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left text-slate-300 font-medium px-2 py-2">Produk</th>
                        <th class="text-center text-slate-300 font-medium px-2 py-2">Qty</th>
                        <th class="text-right text-slate-300 font-medium px-2 py-2">Harga</th>
                        <th class="text-right text-slate-300 font-medium px-2 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->transaksi->detailProduks as $detail)
                    <tr class="border-b border-slate-700">
                        <td class="text-slate-300 px-2 py-2">{{ $detail->produk->nama ?? '-' }}</td>
                        <td class="text-center text-slate-300 px-2 py-2">{{ $detail->qty }}</td>
                        <td class="text-right text-slate-300 px-2 py-2">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right text-slate-300 px-2 py-2">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-blue-700 bg-opacity-20 border border-blue-600 rounded p-4">
        <h3 class="text-sm font-semibold text-blue-300 mb-4">Ringkasan Pembayaran</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-slate-300"><span>Total Transaksi:</span> <span class="font-medium">Rp {{ number_format($receipt->transaksi->jumlah, 0, ',', '.') }}</span></div>
            <div class="border-b border-blue-600"></div>
            <div class="flex justify-between text-green-300"><span>Jumlah Dibayar:</span> <span class="font-medium">Rp {{ number_format($receipt->jumlah_dibayar, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-blue-300"><span>Kembalian:</span> <span class="font-medium">Rp {{ number_format($receipt->kembalian, 0, ',', '.') }}</span></div>
        </div>
    </div>
</div>
@endsection
