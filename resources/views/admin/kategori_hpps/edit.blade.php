@extends('layouts.admin.app')

@section('content')
<div class="p-4 md:p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-white">Edit Kategori HPP</h1>
        <p class="text-xs md:text-sm text-slate-400 mt-1">{{ $kategoriHpp->name }}</p>
    </div>

    <form action="{{ route('admin.kategori_hpps.update', $kategoriHpp) }}" method="POST" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-6 shadow-lg">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-slate-200 mb-2">Kategori HPP</label>
            <input type="text" name="name" id="name" value="{{ old('name', $kategoriHpp->name) }}" required
                   class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="kategori" class="block text-sm font-medium text-slate-200 mb-2">Kategori</label>
            <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $kategoriHpp->kategori) }}" required
                   class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('kategori') border-red-500 @enderror">
            @error('kategori')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm px-6 py-2 rounded-lg transition-colors duration-200">
                Perbarui
            </button>
            <a href="{{ route('admin.kategori_hpps.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-medium text-sm px-6 py-2 rounded-lg text-center transition-colors duration-200">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
