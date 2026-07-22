@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Tambah Jenis Surat Baru</h1>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.jenis-surat.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Kode Surat <span class="text-red-400">*</span></label>
                            <input type="text" name="kode_surat" value="{{ old('kode_surat') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('kode_surat') border-red-500 @enderror" required placeholder="Contoh: 01, 02, 021">
                            @error('kode_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Initial Code <span class="text-red-400">*</span></label>
                            <input type="text" name="initial_code" value="{{ old('initial_code') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('initial_code') border-red-500 @enderror" required placeholder="Contoh: ST, SK, SPN">
                            @error('initial_code')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nama Jenis <span class="text-red-400">*</span></label>
                            <input type="text" name="nama_jenis" value="{{ old('nama_jenis') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nama_jenis') border-red-500 @enderror" required placeholder="Contoh: Surat Tugas">
                            @error('nama_jenis')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Keterangan <span class="text-red-400">*</span></label>
                            <textarea name="keterangan" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('keterangan') border-red-500 @enderror" required placeholder="Deskripsi fungsi dan kegunaan jenis surat">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.jenis-surat.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
