@extends('layouts.admin.app')

@section('content')
<div class="p-4 md:p-6 max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-white">Kategori HPP</h1>
            <p class="text-xs md:text-sm text-slate-400 mt-1">Kelola daftar kategori HPP Anda</p>
        </div>
        <a href="{{ route('admin.kategori_hpps.create') }}" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors duration-200">
            + Tambah
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.kategori_hpps.index') }}" method="GET" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori HPP atau kategori..." class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors duration-200">
                    Cari
                </button>
                <a href="{{ route('admin.kategori_hpps.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors duration-200">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-white divide-y divide-slate-700/50">
                <thead class="bg-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-200">Kategori HPP</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-200">Kategori</th>
                        <th class="px-4 py-3 text-center font-semibold text-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse ($kategoriHpps as $item)
                        <tr class="hover:bg-slate-700/30 transition-colors duration-150">
                            <td class="px-4 py-3 font-medium text-white">{{ $item->name }}</td>
                            <td class="px-4 py-3 text-slate-300 text-xs md:text-sm">{{ $item->kategori }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.kategori_hpps.edit', $item) }}" class="text-blue-400 hover:text-blue-300 text-xs md:text-sm font-medium transition-colors duration-150">
                                        Edit
                                    </a>
                                    <span class="text-slate-600">|</span>
                                    <form action="{{ route('admin.kategori_hpps.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs md:text-sm font-medium transition-colors duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-400">
                                <p class="text-sm">Belum ada kategori HPP yang dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
