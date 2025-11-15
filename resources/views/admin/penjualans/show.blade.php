@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Detail Penjualan #{{ $penjualan->id }}</h1>
            <p class="text-slate-400 text-sm mt-1">Tanggal: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.penjualans.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm">Kembali</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-slate-300 uppercase mb-3">Informasi Umum</h3>
            <div class="space-y-2 text-sm">
                <div><span class="text-slate-400">Label:</span> <span class="text-white">{{ $penjualan->label->nama_label ?? '-' }}</span></div>
                <div><span class="text-slate-400">Pelanggan:</span> <span class="text-white">{{ $penjualan->pelanggan->nama ?? '-' }}</span></div>
                <div><span class="text-slate-400">Akun Pembayaran:</span> <span class="text-white">{{ $penjualan->akunPayment->name ?? '-' }}</span></div>
                <div><span class="text-slate-400">Keterangan:</span> <span class="text-white">{{ $penjualan->keterangan ?? '-' }}</span></div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-slate-300 uppercase mb-3">Total Transaksi</h3>
            <div class="text-2xl font-bold text-white">Rp{{ number_format($penjualan->jumlah, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-slate-300 uppercase mb-3">Detail Produk</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-slate-300 text-xs">Produk</th>
                        <th class="px-4 py-2 text-left text-slate-300 text-xs">Qty</th>
                        <th class="px-4 py-2 text-left text-slate-300 text-xs">Harga Satuan</th>
                        <th class="px-4 py-2 text-left text-slate-300 text-xs">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($penjualan->detailProduks as $d)
                    <tr class="hover:bg-slate-700/40">
                        <td class="px-4 py-2 text-slate-200">{{ $d->product->nama ?? $d->product->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-slate-200">{{ $d->kuantitas }}</td>
                        <td class="px-4 py-2 text-slate-200">Rp{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-slate-200 font-semibold">Rp{{ number_format($d->kuantitas * $d->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('admin.penjualans.edit', $penjualan->id) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm">Edit</a>
        <form action="{{ route('admin.penjualans.destroy', $penjualan->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">Hapus</button>
        </form>
    </div>
</div>
@endsection
