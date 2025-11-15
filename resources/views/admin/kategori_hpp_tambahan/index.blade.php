@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Biaya HPP Tambahan</h1>
        <a href="{{ route('admin.kategori_hpp_tambahan.create') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
            Tambah Biaya
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-300 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500/10 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.kategori_hpp_tambahan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" placeholder="Cari nama biaya..." value="{{ request('search') }}"
                class="flex-1 bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                Cari
            </button>
            <a href="{{ route('admin.kategori_hpp_tambahan.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                Reset
            </a>
        </form>
    </div>

    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm text-white divide-y divide-slate-700/50">
                <thead class="bg-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Nama Biaya</th>
                        <th class="px-4 py-3 text-left font-semibold">Kategori Induk</th>
                        <th class="px-4 py-3 text-right font-semibold">Biaya/Unit</th>
                        <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Akun Beban</th>
                        <th class="px-4 py-3 text-center font-semibold w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse ($tambahanHpps as $item)
                        <tr class="hover:bg-slate-700/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-white">{{ $item->name }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $item->kategoriHpp->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-slate-300 hidden md:table-cell">{{ $item->akunBiaya->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.kategori_hpp_tambahan.edit', $item) }}"
                                        class="text-blue-400 hover:text-blue-300 text-xs sm:text-sm transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kategori_hpp_tambahan.destroy', $item) }}" method="POST"
                                        onsubmit="return confirm('Hapus biaya ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs sm:text-sm transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-slate-400 text-sm">Belum ada Biaya HPP Tambahan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
