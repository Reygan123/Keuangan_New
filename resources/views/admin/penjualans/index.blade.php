@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Penjualan</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar transaksi penjualan</p>
        </div>
        @if($selectedUsahaId)
        <a href="{{ route('admin.penjualans.create', ['usaha_id' => $selectedUsahaId]) }}" class="inline-flex items-center gap-2 bg-[#005198] hover:bg-[#003d73] text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm text-sm transition-all">
            Tambah Penjualan
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex justify-between items-center text-sm shadow-sm">
            <span>{{ session('success') }}</span>
            <button type="button" class="text-emerald-600 hover:text-emerald-900 font-bold" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mb-4">
        <form method="GET" id="filterForm" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Usaha</label>
                <select name="usaha_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3 py-2 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Cari Pelanggan</label>
                <input type="text" name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}" oninput="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3 py-2 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-slate-700 text-xs font-semibold mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <a href="{{ route('admin.penjualans.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium border border-slate-200 rounded-xl text-sm h-10 flex items-center transition-all">Reset</a>
            </div>
        </form>
    </div>

    @if($selectedUsahaId)
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-600 uppercase text-xs font-semibold tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-slate-600 uppercase text-xs font-semibold tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-slate-600 uppercase text-xs font-semibold tracking-wider">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-slate-600 uppercase text-xs font-semibold tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-left text-slate-600 uppercase text-xs font-semibold tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-slate-600 uppercase text-xs font-semibold tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($transaksis as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3 text-slate-800 font-medium">{{ $t->id }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-800 font-medium">{{ $t->pelanggan->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600 text-xs">
                            @foreach($t->detailProduks as $d)
                                <span>{{ $d->product->nama ?? $d->product->name ?? '-' }}</span>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-slate-900 font-semibold">Rp{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.penjualans.show', $t->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-medium transition-all">Lihat</a>
                                <a href="{{ route('admin.penjualans.edit', $t->id) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 rounded-lg text-xs font-medium transition-all">Edit</a>
                                <form action="{{ route('admin.penjualans.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 rounded-lg text-xs font-medium transition-all">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500 text-sm">Belum ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8 text-center">
        <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk melihat transaksi penjualan</p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let timeoutId;

    document.querySelector('input[name="search"]').addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 500);
    });
});
</script>
@endsection