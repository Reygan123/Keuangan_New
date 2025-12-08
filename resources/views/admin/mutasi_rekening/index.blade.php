@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col gap-4 md:gap-6 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h1 class="text-lg md:text-xl font-bold text-slate-100">Mutasi Rekening</h1>
                <a href="{{ route('admin.mutasi_rekening.create') }}" class="inline-flex items-center px-3 md:px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-lg text-xs md:text-sm font-medium transition-colors duration-200">
                    Buat Mutasi
                </a>
            </div>

            @if (session('success'))
            <div class="p-3 rounded-lg bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 text-xs md:text-sm">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-3 md:p-4">
                <form method="GET" action="{{ route('admin.mutasi_rekening.index') }}" class="flex flex-col gap-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" placeholder="Tanggal Dari" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                        </div>
                        <div>
                            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" placeholder="Tanggal Sampai" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                        </div>
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari akun..." class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg px-3 py-2 text-xs md:text-sm font-medium transition-colors duration-200">
                                Filter
                            </button>
                            <a href="{{ route('admin.mutasi_rekening.index') }}" class="flex-1 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm font-medium text-center transition-colors duration-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg">
            <table class="w-full text-xs md:text-sm">
                <thead class="bg-slate-800/80 border-b border-slate-700/60 sticky top-0">
                    <tr>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-200">Tanggal</th>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-200">Akun Asal</th>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-200">Akun Tujuan</th>
                        <th class="px-3 md:px-4 py-3 text-right font-semibold text-slate-200">Jumlah</th>
                        <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-200 hidden sm:table-cell">Deskripsi</th>
                        <th class="px-3 md:px-4 py-3 text-center font-semibold text-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    @forelse($mutasi as $m)
                    <tr class="hover:bg-slate-700/50 transition-colors duration-150">
                        <td class="px-3 md:px-4 py-3 text-slate-300">{{ \Carbon\Carbon::parse($m->tanggal)->format('d M Y') }}</td>
                        <td class="px-3 md:px-4 py-3 text-slate-300">{{ optional($m->akunAsal)->name }}</td>
                        <td class="px-3 md:px-4 py-3 text-slate-300">{{ optional($m->akunTujuan)->name }}</td>
                        <td class="px-3 md:px-4 py-3 text-right text-slate-300">{{ number_format($m->jumlah, 2, ',', '.') }}</td>
                        <td class="px-3 md:px-4 py-3 text-slate-400 hidden sm:table-cell text-xs">{{ Str::limit($m->deskripsi, 30) }}</td>
                        <td class="px-3 md:px-4 py-3 text-center">
                            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                <!-- <a href="{{ route('admin.mutasi_rekening.edit', $m) }}" class="px-2 py-1 bg-amber-600/90 hover:bg-amber-600 text-white rounded text-xs font-medium transition-colors duration-200">
                                            Edit
                                        </a> -->
                                <!-- Debug: -->
                                <form action="{{ route('admin.mutasi_rekening.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus mutasi rekening ini? Aksi ini akan membalik jurnal.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-2 py-1 bg-red-600/90 hover:bg-red-600 text-white rounded text-xs font-medium transition-colors duration-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-3 md:px-4 py-8 text-center text-slate-400 text-xs md:text-sm">
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
    </div>
</div>
@endsection