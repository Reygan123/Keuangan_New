@extends('layouts.admin.app')
@section('title', 'Tambah Aset Tetap')
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
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mb-6">Tambah Aset Tetap Baru</h1>

                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 mb-6">
                    <form method="GET" action="{{ route('admin.penyusutan.create') }}"
                        class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Pilih Usaha</label>
                            <select name="usaha_id" onchange="this.form.submit()"
                                class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">-- Pilih Usaha --</option>
                                @foreach ($usahas as $usaha)
                                    <option value="{{ $usaha->id }}"
                                        {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    @if ($selectedUsahaId)
                        <p class="text-slate-600 text-xs mt-2.5">Aset akan dicatat untuk usaha: <span
                                class="font-semibold text-slate-900">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span>
                        </p>
                    @endif
                </div>

                @if (!$selectedUsahaId)
                    <div class="bg-white border border-slate-200/80 rounded-xl p-8 text-center shadow-sm">
                        <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk menambahkan aset</p>
                    </div>
                @else
                    <form action="{{ route('admin.penyusutan.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Aset <span
                                        class="text-rose-500">*</span></label>
                                <select name="akun_aset_id"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    required>
                                    <option value="">Pilih Akun Aset</option>
                                    @foreach ($akunAset as $akun)
                                        <option value="{{ $akun->id }}">{{ $akun->name }}</option>
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
                                    placeholder="Nama aset" required>
                                @error('uraian')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Tanggal Perolehan
                                    <span class="text-rose-500">*</span></label>
                                <input type="date" name="tgl_perolehan"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    required>
                                @error('tgl_perolehan')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Harga Beli <span
                                        class="text-rose-500">*</span></label>
                                <input type="number" name="harga_beli"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    step="0.01" placeholder="0" required>
                                @error('harga_beli')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Golongan <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="golongan"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    placeholder="Golongan aset" required>
                                @error('golongan')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Umur Ekonomis
                                    (tahun)
                                    <span class="text-rose-500">*</span></label>
                                <input type="number" name="umur_ekonomis"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    placeholder="0" required>
                                @error('umur_ekonomis')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Nilai Sisa <span
                                        class="text-rose-500">*</span></label>
                                <input type="number" name="nilai_sisa"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    step="0.01" placeholder="0" required>
                                @error('nilai_sisa')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Beban
                                    Penyusutan
                                    <span class="text-rose-500">*</span></label>
                                <select name="akun_beban_id"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    required>
                                    <option value="">Pilih Akun Beban</option>
                                    @foreach ($akunBeban as $akun)
                                        <option value="{{ $akun->id }}">{{ $akun->name }}</option>
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
                                    <option value="">Pilih Akun Akumulasi</option>
                                    @foreach ($akunAkumulasi as $akun)
                                        <option value="{{ $akun->id }}">{{ $akun->name }}</option>
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
                                Simpan
                            </button>
                            <a href="{{ route('admin.penyusutan.index') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 text-xs sm:text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors font-semibold border border-slate-200">Batal</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
