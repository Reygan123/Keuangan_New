@extends('layouts.admin.app')
@section('title', 'Edit Aset Tetap')
@section('content')
    <div class="min-h-screen bg-slate-950">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="mb-4">
                <a href="{{ route('admin.penyusutan.index') }}"
                    class="inline-flex items-center text-blue-400 hover:text-blue-300 text-xs sm:text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 sm:p-6">
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Edit Aset Tetap</h1>
                <p class="text-slate-400 text-xs sm:text-sm mb-6">#{{ $aset->id }} - {{ $aset->uraian }}</p>

                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600/20 border border-blue-500/30 rounded-lg p-3">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs">Usaha</p>
                            <p class="text-white font-semibold">
                                {{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.penyusutan.update', $aset->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Akun Aset <span
                                    class="text-red-500">*</span></label>
                            <select name="akun_aset_id"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                required>
                                @foreach ($akunAset as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ $aset->akun_aset_id == $akun->id ? 'selected' : '' }}>{{ $akun->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akun_aset_id')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Uraian Aset <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="uraian"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                value="{{ $aset->uraian }}" required>
                            @error('uraian')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Tanggal Perolehan <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tgl_perolehan"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                value="{{ $aset->tgl_perolehan }}" required>
                            @error('tgl_perolehan')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Harga Beli <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="harga_beli"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                step="0.01" value="{{ $aset->harga_beli }}" required>
                            @error('harga_beli')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Golongan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="golongan"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                value="{{ $aset->golongan }}" required>
                            @error('golongan')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Umur Ekonomis (tahun)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="umur_ekonomis"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                value="{{ $aset->umur_ekonomis }}" required>
                            @error('umur_ekonomis')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Nilai Sisa <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="nilai_sisa"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                step="0.01" value="{{ $aset->nilai_sisa }}" required>
                            @error('nilai_sisa')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Akun Beban Penyusutan
                                <span class="text-red-500">*</span></label>
                            <select name="akun_beban_id"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                required>
                                @foreach ($akunBeban as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ $aset->akun_beban_id == $akun->id ? 'selected' : '' }}>{{ $akun->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akun_beban_id')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-300 mb-2">Akun Akumulasi
                                Penyusutan <span class="text-red-500">*</span></label>
                            <select name="akun_akumulasi_id"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                                required>
                                @foreach ($akunAkumulasi as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ $aset->akun_akumulasi_id == $akun->id ? 'selected' : '' }}>{{ $akun->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akun_akumulasi_id')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-2 mt-6 sm:mt-8">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update
                        </button>
                        <a href="{{ route('admin.penyusutan.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
