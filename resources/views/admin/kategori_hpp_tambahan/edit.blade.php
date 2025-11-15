@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Edit Biaya HPP Tambahan</h1>

    <form action="{{ route('admin.kategori_hpp_tambahan.update', $kategoriHppTambahan) }}" method="POST" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 p-5 sm:p-6 rounded-lg shadow-lg space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="kategori_hpp_id" class="text-slate-300 block mb-2 text-sm font-medium">Kategori HPP</label>
            <select name="kategori_hpp_id" id="kategori_hpp_id" required
                    class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('kategori_hpp_id') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoriHpps as $kategori)
                    <option value="{{ $kategori->id }}"
                        {{ old('kategori_hpp_id', $kategoriHppTambahan->kategori_hpp_id) == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->name }}
                    </option>
                @endforeach
            </select>
            @error('kategori_hpp_id')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="text-slate-300 block mb-2 text-sm font-medium">Nama Biaya</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $kategoriHppTambahan->name) }}" required
                   class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('name') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
            @error('name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit_cost" class="text-slate-300 block mb-2 text-sm font-medium">Biaya/Unit (Rp)</label>
            <input type="number" name="unit_cost" id="unit_cost" value="{{ old('unit_cost', $kategoriHppTambahan->unit_cost) }}" required min="0" step="any"
                   class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('unit_cost') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
            @error('unit_cost')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="akun_biaya_id" class="text-slate-300 block mb-2 text-sm font-medium">Akun Beban/Biaya</label>
            <select name="akun_biaya_id" id="akun_biaya_id" required
                    class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('akun_biaya_id') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                <option value="">-- Pilih Akun --</option>
                @foreach ($akunBiaya as $akun)
                    <option value="{{ $akun->id }}"
                        {{ old('akun_biaya_id', $kategoriHppTambahan->akun_biaya_id) == $akun->id ? 'selected' : '' }}>
                        {{ $akun->name }}
                    </option>
                @endforeach
            </select>
            @error('akun_biaya_id')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition-colors">
                Update
            </button>
            <a href="{{ route('admin.kategori_hpp_tambahan.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-5 py-2 rounded-lg text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
