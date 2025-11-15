@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Tambah Produk Baru</h1>

    <form action="{{ route('admin.products.store') }}" method="POST" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 p-5 sm:p-6 rounded-lg shadow-lg space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label for="nama" class="text-slate-300 block mb-2 text-sm font-medium">Nama Produk</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm placeholder-slate-400 @error('nama') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                @error('nama')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kategori_hpp_id" class="text-slate-300 block mb-2 text-sm font-medium">Kategori HPP</label>
                <select name="kategori_hpp_id" id="kategori_hpp_id" required
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('kategori_hpp_id') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoriHpps as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_hpp_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->name }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_hpp_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- <div>
                <label for="hpp_unit_rata2" class="text-slate-300 block mb-2 text-sm font-medium">HPP Unit Rata-Rata</label>
                <input type="number" name="hpp_unit_rata2" id="hpp_unit_rata2" value="{{ old('hpp_unit_rata2', 0) }}" step="any" min="0"
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('hpp_unit_rata2') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                @error('hpp_unit_rata2')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div> --}}

            <div>
                <label for="akun_pendapatan_id" class="text-slate-300 block mb-2 text-sm font-medium">Akun Pendapatan</label>
                <select name="akun_pendapatan_id" id="akun_pendapatan_id" required
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('akun_pendapatan_id') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    <option value="">-- Pilih Akun --</option>
                    @foreach ($akunPendapatan as $akun)
                        <option value="{{ $akun->id }}" {{ old('akun_pendapatan_id') == $akun->id ? 'selected' : '' }}>
                            {{ $akun->name }}
                        </option>
                    @endforeach
                </select>
                @error('akun_pendapatan_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="akun_hpp_id" class="text-slate-300 block mb-2 text-sm font-medium">Akun Beban Pokok Penjualan</label>
                <select name="akun_hpp_id" id="akun_hpp_id" required
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('akun_hpp_id') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    <option value="">-- Pilih Akun --</option>
                    @foreach ($akunHpps as $akun)
                        <option value="{{ $akun->id }}" {{ old('akun_hpp_id') == $akun->id ? 'selected' : '' }}>
                            {{ $akun->name }}
                        </option>
                    @endforeach
                </select>
                @error('akun_hpp_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="akun_persediaan_id" class="text-slate-300 block mb-2 text-sm font-medium">Akun Persediaan</label>
                <select name="akun_persediaan_id" id="akun_persediaan_id" required
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('akun_persediaan_id') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    <option value="">-- Pilih Akun --</option>
                    @foreach ($akunPersediaan as $akun)
                        <option value="{{ $akun->id }}" {{ old('akun_persediaan_id') == $akun->id ? 'selected' : '' }}>
                            {{ $akun->name }}
                        </option>
                    @endforeach
                </select>
                @error('akun_persediaan_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="satuan_unit" class="text-slate-300 block mb-2 text-sm font-medium">Satuan Unit</label>
                <input type="text" name="satuan_unit" id="satuan_unit" value="{{ old('satuan_unit') }}" required
                        placeholder="Pcs, Kg, Unit"
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm placeholder-slate-400 @error('satuan_unit') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                @error('satuan_unit')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- <div>
                <label for="stok" class="text-slate-300 block mb-2 text-sm font-medium">Stok Awal</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" required min="0"
                        class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-sm @error('stok') border-red-500 @enderror focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                @error('stok')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition-colors">
                Simpan Produk
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-5 py-2 rounded-lg text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
