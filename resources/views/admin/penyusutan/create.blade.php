@extends('layouts.admin.app')
@section('title', 'Tambah Aset Tetap')
@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="mb-4">
            <a href="{{ route('admin.penyusutan.index') }}" class="inline-flex items-center text-blue-400 hover:text-blue-300 text-xs sm:text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 sm:p-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-6">Tambah Aset Tetap Baru</h1>

            <form action="{{ route('admin.penyusutan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Akun Aset <span class="text-red-500">*</span></label>
                        <select name="akun_aset_id" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                            <option value="">Pilih Akun Aset</option>
                            @foreach($akunAset as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        </select>
                        @error('akun_aset_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Uraian Aset <span class="text-red-500">*</span></label>
                        <input type="text" name="uraian" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Nama aset" required>
                        @error('uraian')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Tanggal Perolehan <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_perolehan" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                        @error('tgl_perolehan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Harga Beli <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_beli" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" step="0.01" placeholder="0" required>
                        @error('harga_beli')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Golongan <span class="text-red-500">*</span></label>
                        <input type="text" name="golongan" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Golongan aset" required>
                        @error('golongan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Umur Ekonomis (tahun) <span class="text-red-500">*</span></label>
                        <input type="number" name="umur_ekonomis" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="0" required>
                        @error('umur_ekonomis')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Nilai Sisa <span class="text-red-500">*</span></label>
                        <input type="number" name="nilai_sisa" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" step="0.01" placeholder="0" required>
                        @error('nilai_sisa')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Akun Beban Penyusutan <span class="text-red-500">*</span></label>
                        <select name="akun_beban_id" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                            <option value="">Pilih Akun Beban</option>
                            @foreach($akunBeban as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        </select>
                        @error('akun_beban_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Akun Akumulasi Penyusutan <span class="text-red-500">*</span></label>
                        <select name="akun_akumulasi_id" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                            <option value="">Pilih Akun Akumulasi</option>
                            @foreach($akunAkumulasi as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        </select>
                        @error('akun_akumulasi_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex gap-2 mt-6 sm:mt-8">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.penyusutan.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
