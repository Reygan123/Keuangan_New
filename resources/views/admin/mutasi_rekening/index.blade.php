@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col gap-4 md:gap-6 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h1 class="text-lg md:text-xl font-bold text-slate-900">Mutasi Rekening</h1>
                @if($selectedUsahaId)
                <a href="{{ route('admin.mutasi_rekening.create', ['usaha_id' => $selectedUsahaId]) }}" class="inline-flex items-center px-4 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white rounded-xl text-xs md:text-sm font-semibold transition-colors duration-200 shadow-sm">
                    Buat Mutasi
                </a>
                @endif
            </div>

            @if (session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs md:text-sm font-medium shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs md:text-sm font-medium shadow-sm">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white border border-slate-200/80 rounded-2xl p-3 md:p-4 shadow-sm">
                <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Usaha</label>
                        <select name="usaha_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                            <option value="">-- Pilih Usaha --</option>
                            @foreach($usahas as $usaha)
                            <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Cari Akun</label>
                        <input type="text" name="search" value="{{ request('search') }}" oninput="document.getElementById('filterForm').submit()" placeholder="Cari akun..." class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                    </div>
                    <div class="flex gap-2 items-end">
                        <a href="{{ route('admin.mutasi_rekening.index') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl px-3.5 py-2 text-xs md:text-sm font-semibold text-center transition-colors duration-200 border border-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($selectedUsahaId)
        <div class="overflow-x-auto bg-white border border-slate-200/80 rounded-2xl shadow-sm">
            <table class="w-full text-xs md:text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0">
                    <tr>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider">Akun Asal</th>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider">Akun Tujuan</th>
                        <th class="px-3 md:px-4 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider hidden sm:table-cell">Deskripsi</th>
                        <th class="px-3 md:px-4 py-3 text-center font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($mutasi as $m)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                        <td class="px-3 md:px-4 py-3.5 text-slate-800">{{ \Carbon\Carbon::parse($m->tanggal)->format('d M Y') }}</td>
                        <td class="px-3 md:px-4 py-3.5 text-slate-800 font-medium">{{ optional($m->akunAsal)->name }}</td>
                        <td class="px-3 md:px-4 py-3.5 text-slate-800 font-medium">{{ optional($m->akunTujuan)->name }}</td>
                        <td class="px-3 md:px-4 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ number_format($m->jumlah, 2, ',', '.') }}</td>
                        <td class="px-3 md:px-4 py-3.5 text-slate-500 hidden sm:table-cell text-xs">{{ Str::limit($m->deskripsi, 30) }}</td>
                        <td class="px-3 md:px-4 py-3.5 text-center">
                            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                <a href="{{ route('admin.mutasi_rekening.edit', $m) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 rounded-lg text-xs font-semibold transition-colors duration-200">
                                    Edit
                                </a>
                                <form action="{{ route('admin.mutasi_rekening.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus mutasi rekening ini? Aksi ini akan membalik jurnal.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-lg text-xs font-semibold transition-colors duration-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-3 md:px-4 py-8 text-center text-slate-500 text-xs md:text-sm">
                            Belum ada mutasi rekening.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-center md:justify-end">
            {{ $mutasi->links() }}
        </div>
        @else
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk melihat mutasi rekening</p>
        </div>
        @endif
    </div>
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
