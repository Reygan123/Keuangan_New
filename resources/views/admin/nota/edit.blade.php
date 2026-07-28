@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900 p-4 sm:p-6">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Nota</h1>
                <p class="text-slate-500 text-sm">Perbarui data dan rincian nota transaksi</p>
            </div>
            <a href="{{ route('admin.nota.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.nota.update', $nota->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
                <h2 class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-3">Informasi Utama</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nomor Nota</label>
                        <input type="text" name="nomor_nota" value="{{ old('nomor_nota', $nota->nomor_nota) }}" required class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-400/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($nota->tanggal)->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-400/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Pelanggan / Pihak Kedua</label>
                        <input type="text" name="pelanggan" value="{{ old('pelanggan', $nota->pelanggan) }}" class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-400/20 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Keterangan / Catatan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-400/20 transition-all">{{ old('keterangan', $nota->keterangan) }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-base font-semibold text-slate-900">Rincian Item</h2>
                    <button type="button" id="addItemBtn" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors border border-slate-200">
                        + Tambah Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" id="itemsTable">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 font-semibold">Deskripsi Barang/Jasa</th>
                                <th class="px-4 py-3 font-semibold w-28 text-center">Jumlah</th>
                                <th class="px-4 py-3 font-semibold w-40 text-right">Harga Satuan</th>
                                <th class="px-4 py-3 font-semibold w-40 text-right">Subtotal</th>
                                <th class="px-4 py-3 font-semibold w-16 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($nota->items as $index => $item)
                            <tr class="item-row">
                                <td class="px-4 py-3">
                                    <input type="text" name="items[{{ $index }}][deskripsi]" value="{{ $item->deskripsi }}" required class="w-full px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-slate-400 focus:bg-white transition-all">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[{{ $index }}][jumlah]" value="{{ $item->jumlah }}" min="1" required class="item-qty w-full px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm text-center focus:outline-none focus:border-slate-400 focus:bg-white transition-all">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[{{ $index }}][harga]" value="{{ $item->harga }}" min="0" required class="item-price w-full px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm text-right focus:outline-none focus:border-slate-400 focus:bg-white transition-all">
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-slate-900 item-subtotal">
                                    Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-row-btn p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <div class="text-right space-y-1">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pembayaran</span>
                        <div class="text-2xl font-bold text-slate-900" id="grandTotal">Rp {{ number_format($nota->total ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.nota.index') }}" class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
                    Update Nota
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
