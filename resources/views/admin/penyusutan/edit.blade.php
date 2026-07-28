@extends('layouts.admin.app')
@section('title', 'Edit Aset Tetap')
@section('content')
    <div class="min-h-screen bg-slate-50 py-4 sm:py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.penyusutan.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-sm">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1">Edit Aset Tetap</h1>
                <p class="text-slate-500 text-xs sm:text-sm mb-6">#{{ $aset->id }} - {{ $aset->uraian }}</p>

                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-50 border border-blue-200/80 rounded-xl p-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-medium">Usaha</p>
                            <p class="text-slate-900 font-semibold">
                                {{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.penyusutan.update', $aset->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Aset <span
                                    class="text-rose-500">*</span></label>
                            <select name="akun_aset_id"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                required>
                                @foreach ($akunAset as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ $aset->akun_aset_id == $akun->id ? 'selected' : '' }}>{{ $akun->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akun_aset_id')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Uraian Aset <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="uraian"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                value="{{ $aset->uraian }}" required>
                            @error('uraian')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Tanggal Perolehan <span
                                    class="text-rose-500">*</span></label>
                            <input type="date" name="tgl_perolehan"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                value="{{ $aset->tgl_perolehan }}" required>
                            @error('tgl_perolehan')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Harga Beli <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" name="harga_beli"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                step="0.01" value="{{ $aset->harga_beli }}" required>
                            @error('harga_beli')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Golongan <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="golongan"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                value="{{ $aset->golongan }}" required>
                            @error('golongan')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Umur Ekonomis (tahun)
                                <span class="text-rose-500">*</span></label>
                            <input type="number" name="umur_ekonomis"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                value="{{ $aset->umur_ekonomis }}" required>
                            @error('umur_ekonomis')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Nilai Sisa <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" name="nilai_sisa"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                step="0.01" value="{{ $aset->nilai_sisa }}" required>
                            @error('nilai_sisa')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Beban Penyusutan
                                <span class="text-rose-500">*</span></label>
                            <select name="akun_beban_id"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                required>
                                @foreach ($akunBeban as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ $aset->akun_beban_id == $akun->id ? 'selected' : '' }}>{{ $akun->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akun_beban_id')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Akumulasi
                                Penyusutan <span class="text-rose-500">*</span></label>
                            <select name="akun_akumulasi_id"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                required>
                                @foreach ($akunAkumulasi as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ $aset->akun_akumulasi_id == $akun->id ? 'selected' : '' }}>{{ $akun->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akun_akumulasi_id')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 sm:mt-8 pt-4 border-t border-slate-200">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-xs sm:text-sm bg-[#005198] hover:bg-[#003d73] text-white rounded-xl transition-colors font-semibold shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update
                        </button>
                        <a href="{{ route('admin.penyusutan.index') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-xs sm:text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors font-semibold border border-slate-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
