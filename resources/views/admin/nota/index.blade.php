@extends('layouts.admin.app')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-control {
        background-color: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }

    .ts-dropdown {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border-color: #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .ts-dropdown .active {
        background-color: #f1f5f9 !important;
    }

    .ts-dropdown .option {
        color: #334155 !important;
    }
</style>

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Daftar Nota</h1>
                <p class="text-slate-500 text-sm">Kelola dan lihat histori nota transaksi usaha</p>
            </div>
            <a href="{{ route('admin.nota.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all h-fit">
                + Tambah Nota Baru
            </a>
        </div>

        @if (session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
            <form method="GET" action="{{ route('admin.nota.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="filterForm">
                @if (isset($usahas) && $usahas->count() > 0)
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        <option value="">Semua Usaha</option>
                        @foreach ($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ request('usaha_id') == $usaha->id ? 'selected' : '' }}>
                            {{ $usaha->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor nota/pelanggan..." onkeyup="debouncedSubmit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
            </form>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5 font-semibold text-slate-600 uppercase tracking-wider text-xs">Nomor Nota</th>
                            <th class="px-4 py-3.5 font-semibold text-slate-600 uppercase tracking-wider text-xs">Tanggal</th>
                            <th class="px-4 py-3.5 font-semibold text-slate-600 uppercase tracking-wider text-xs">Pelanggan</th>
                            <th class="px-4 py-3.5 font-semibold text-slate-600 uppercase tracking-wider text-xs text-right">Total</th>
                            <th class="px-4 py-3.5 font-semibold text-slate-600 uppercase tracking-wider text-xs hidden md:table-cell">Keterangan</th>
                            <th class="px-4 py-3.5 font-semibold text-slate-600 uppercase tracking-wider text-xs text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($notas as $n)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3.5 font-semibold text-slate-900">{{ $n->nomor_nota }}</td>
                            <td class="px-4 py-3.5 text-slate-700">{{ \Carbon\Carbon::parse($n->tanggal)->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 text-slate-800 font-medium">{{ $n->pelanggan ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-right font-semibold text-slate-900 font-mono">Rp {{ number_format($n->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-slate-500 hidden md:table-cell text-xs">{{ Str::limit($n->keterangan, 35) ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.nota.edit', $n->id) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 rounded-lg text-xs font-semibold transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.nota.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus nota ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-lg text-xs font-semibold transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500 text-sm">
                                Belum ada data nota yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($notas) && method_exists($notas, 'hasPages') && $notas->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $notas->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    let timeout = null;
    function debouncedSubmit() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    }
</script>
@endsection
