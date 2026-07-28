@extends('layouts.admin.app')

@section('content')
    <div class="p-4 sm:p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Pembelian #{{ $pembelian->id }}</h1>
                <p class="text-slate-500 text-sm mt-1">Tanggal:
                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}</p>
            </div>
            <a href="{{ route('admin.pembelians.index') }}"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm border border-slate-200 transition-all">Kembali</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Informasi Umum</h3>
                <div class="space-y-2.5 text-sm">
                    <div><span class="text-slate-500">Usaha:</span> <span
                            class="text-slate-900 font-medium">{{ $pembelian->usaha->nama ?? '-' }}</span></div>
                    <div><span class="text-slate-500">Label:</span> <span
                            class="text-slate-900 font-medium">{{ $pembelian->label->name ?? ($pembelian->label->nama_label ?? '-') }}</span></div>
                    <div><span class="text-slate-500">Supplier:</span> <span
                            class="text-slate-900 font-medium">{{ $pembelian->supplier->name ?? ($pembelian->supplier->nama ?? '-') }}</span></div>
                    <div><span class="text-slate-500">Akun Pembayaran:</span> <span
                            class="text-slate-900 font-medium">{{ $pembelian->akunPayment->name ?? '-' }}</span></div>
                    <div><span class="text-slate-500">Keterangan:</span> <span
                            class="text-slate-900 font-medium">{{ $pembelian->keterangan ?? '-' }}</span></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-center">
                <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Total Transaksi</h3>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900">Rp{{ number_format($pembelian->jumlah, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Detail Produk</h3>
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-slate-600 text-xs font-semibold uppercase tracking-wider">Produk</th>
                            <th class="px-4 py-2.5 text-left text-slate-600 text-xs font-semibold uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-2.5 text-left text-slate-600 text-xs font-semibold uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-4 py-2.5 text-left text-slate-600 text-xs font-semibold uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($pembelian->detailProduks as $d)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-2.5 text-slate-900 font-medium">{{ $d->product->nama ?? ($d->product->name ?? '-') }}
                                </td>
                                <td class="px-4 py-2.5 text-slate-700">{{ $d->kuantitas }}</td>
                                <td class="px-4 py-2.5 text-slate-700">Rp{{ number_format($d->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2.5 text-slate-900 font-semibold">
                                    Rp{{ number_format($d->kuantitas * $d->harga_satuan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.pembelians.edit', $pembelian->id) }}"
                class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 font-medium rounded-xl text-sm transition-all shadow-sm">Edit</a>
            <form action="{{ route('admin.pembelians.destroy', $pembelian->id) }}" method="POST" class="inline"
                onsubmit="return confirm('Yakin ingin menghapus?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 font-medium rounded-xl text-sm transition-all shadow-sm">Hapus</button>
            </form>
        </div>
    </div>
@endsection