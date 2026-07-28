@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-50 text-slate-900 p-4 md:p-6">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h2 class="text-lg md:text-xl font-bold text-slate-900">{{ isset($mutasi) ? 'Edit Mutasi' : 'Buat Mutasi Baru' }}</h2>
                <a href="{{ route('admin.mutasi_rekening.index') }}"
                    class="text-xs md:text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200 font-medium">
                    Kembali
                </a>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 mb-6 shadow-sm">
                @if (isset($mutasi))
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
                @else
                    <form method="GET" action="{{ route('admin.mutasi_rekening.create') }}"
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
                        <p class="text-slate-600 text-xs mt-2.5">Mutasi akan dicatat untuk usaha: <span
                                class="font-semibold text-slate-900">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span>
                        </p>
                    @endif
                @endif
            </div>

            @if (!isset($mutasi) && !$selectedUsahaId)
                <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
                    <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk membuat mutasi</p>
                </div>
            @else
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 md:p-6 shadow-sm">
                    @if ($errors->any())
                        <div
                            class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs md:text-sm">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        action="{{ isset($mutasi) ? route('admin.mutasi_rekening.update', $mutasi) : route('admin.mutasi_rekening.store') }}"
                        method="POST" class="space-y-4">
                        @csrf
                        @if (isset($mutasi))
                            @method('PUT')
                        @endif

                        <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">

                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Tanggal</label>
                            <input type="date" name="tanggal"
                                value="{{ old('tanggal', isset($mutasi) ? $mutasi->tanggal->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                required
                                class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs md:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Asal</label>
                                <select name="akun_asal_id" required
                                    class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                    <option value="">-- Pilih Akun Asal --</option>
                                    @foreach ($kasBankAkun as $akun)
                                        <option value="{{ $akun->id }}"
                                            @if (old('akun_asal_id', isset($mutasi) ? $mutasi->akun_asal_id : '') == $akun->id) selected @endif>
                                            {{ $akun->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs md:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun
                                    Tujuan</label>
                                <select name="akun_tujuan_id" required
                                    class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                    <option value="">-- Pilih Akun Tujuan --</option>
                                    @foreach ($kasBankAkun as $akun)
                                        <option value="{{ $akun->id }}"
                                            @if (old('akun_tujuan_id', isset($mutasi) ? $mutasi->akun_tujuan_id : '') == $akun->id) selected @endif>
                                            {{ $akun->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Jumlah</label>
                            <input type="number" step="0.01" min="0" name="jumlah"
                                value="{{ old('jumlah', isset($mutasi) ? $mutasi->jumlah : '') }}" required
                                class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Deskripsi
                                (opsional)</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">{{ old('deskripsi', isset($mutasi) ? $mutasi->deskripsi : '') }}</textarea>
                        </div>

                        <div
                            class="flex flex-col-reverse sm:flex-row items-center gap-3 justify-end pt-4 border-t border-slate-200">
                            <a href="{{ route('admin.mutasi_rekening.index') }}"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs md:text-sm font-semibold text-center transition-colors duration-200 border border-slate-200">
                                Batal
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#005198] hover:bg-[#003d73] text-white text-xs md:text-sm font-semibold transition-colors duration-200 shadow-sm">
                                {{ isset($mutasi) ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>

                    @if (isset($mutasi))
                        <div class="mt-6 pt-4 border-t border-slate-200">
                            <form action="{{ route('admin.mutasi_rekening.destroy', $mutasi) }}" method="POST"
                                onsubmit="return confirm('Hapus mutasi rekening ini? Aksi ini akan membalik jurnal.');"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs md:text-sm font-semibold transition-colors duration-200 shadow-sm">
                                    Hapus Mutasi Ini
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
