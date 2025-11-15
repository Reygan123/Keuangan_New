@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Penjualan</h1>
            <p class="text-slate-400 text-sm mt-1">Daftar transaksi penjualan</p>
        </div>
        <a href="{{ route('admin.penjualans.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow text-sm">
            Tambah Penjualan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 rounded-lg flex justify-between items-center text-sm">
            {{ session('success') }}
            <button type="button" class="opacity-70 hover:opacity-100" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    <div class="bg-slate-800/40 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4 mb-4">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Cari</button>
                <a href="{{ route('admin.penjualans.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-slate-800/40 backdrop-blur-sm rounded-lg border border-slate-700/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700 text-sm">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">ID</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Tanggal</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Produk</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Total</th>
                        <th class="px-4 py-3 text-center text-slate-300 uppercase text-xs font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @forelse($transaksis as $t)
                    <tr class="hover:bg-slate-700/40 transition">
                        <td class="px-4 py-3 text-slate-200">{{ $t->id }}</td>
                        <td class="px-4 py-3 text-slate-200">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-200">{{ $t->pelanggan->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-200 text-xs">
                            @foreach($t->detailProduks as $d)
                                <span>{{ $d->product->nama ?? $d->product->name ?? '-' }}</span>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-slate-200 font-semibold">Rp{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.penjualans.show', $t->id) }}" class="px-2 py-1 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs">Lihat</a>
                                <a href="{{ route('admin.penjualans.edit', $t->id) }}" class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs">Edit</a>
                                <form action="{{ route('admin.penjualans.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
