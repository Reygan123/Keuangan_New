@extends('layouts.admin.app')

@section('title', $title)

@section('content')
<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">{{ $title }}</h1>
            @if($selectedUsahaId)
            <a href="{{ route('admin.kasbank.create', ['tipe' => $tipe, 'usaha_id' => $selectedUsahaId]) }}" class="inline-flex items-center px-4 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah {{ $tipe == 'PENERIMAAN' ? 'Penerimaan' : 'Pengeluaran' }}
            </a>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl font-medium shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 md:p-6 mb-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Usaha</label>
                    <form method="GET" action="{{ route('admin.kasbank.index', $tipe) }}" id="filterForm">
                        <select name="usaha_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">-- Pilih Usaha --</option>
                            @foreach($usahas as $usaha)
                            <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Cari Berdasarkan Label</label>
                    <input type="text" id="searchLabel" placeholder="Ketik label..." class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Filter Periode</label>
                    <input type="month" id="filterMonth" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="flex items-end">
                    <button onclick="resetFilters()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3.5 py-2 transition-colors duration-200 border border-slate-200">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        @if($selectedUsahaId)
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Label</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Akun Kas/Bank</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Akun Lawan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($transaksis as $transaksi)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                                <td class="px-4 py-3.5 text-slate-700 font-mono text-xs">{{ $transaksi->id }}</td>
                                <td class="px-4 py-3.5 text-slate-800">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                                <td class="px-4 py-3.5 text-slate-800 font-medium">{{ $transaksi->label->nama_label ?? $transaksi->label->name }}</td>
                                <td class="px-4 py-3.5 text-slate-600 text-xs">{{ $transaksi->akunPayment->name }}</td>
                                <td class="px-4 py-3.5 text-slate-600 text-xs">{{ $transaksi->akunLawan->name }}</td>
                                <td class="px-4 py-3.5 text-right text-slate-900 font-semibold font-mono">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('admin.kasbank.show', ['tipe' => $tipe, 'kasbank' => $transaksi->id]) }}" class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.kasbank.edit', ['tipe' => $tipe, 'kasbank' => $transaksi->id]) }}" class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors duration-200">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.kasbank.destroy', ['tipe' => $tipe, 'kasbank' => $transaksi->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini? Jurnal akan dibalikkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors duration-200">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500 text-sm">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk melihat transaksi kas/bank</p>
        </div>
        @endif
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('searchLabel').value = '';
    document.getElementById('filterMonth').value = '';
}
</script>
@endsection
