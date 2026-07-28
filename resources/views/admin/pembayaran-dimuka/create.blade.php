@extends('layouts.admin.app')
@section('title', 'Tambah Pembayaran Di Muka')
@section('content')
<div class="min-h-screen bg-slate-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.pembayaran-dimuka.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-800 text-sm mb-4 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Tambah Pembayaran Di Muka</h1>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('admin.pembayaran-dimuka.create') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Pilih Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            @if($selectedUsahaId)
            <p class="text-slate-600 text-xs mt-2.5">Pembayaran akan dicatat untuk usaha: <span class="font-semibold text-slate-900">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
            @endif
        </div>

        @if(!$selectedUsahaId)
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk membuat pembayaran di muka</p>
        </div>
        @else
        <form action="{{ route('admin.pembayaran-dimuka.store') }}" method="POST" class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-6">
            @csrf
            <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nama Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_pembayaran" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('nama_pembayaran') border-rose-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tgl_transaksi" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('tgl_transaksi') border-rose-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Periode Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="periode_mulai" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('periode_mulai') border-rose-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Periode Akhir <span class="text-rose-500">*</span></label>
                    <input type="date" name="periode_akhir" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('periode_akhir') border-rose-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Jumlah Nominal <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_nominal" step="0.01" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('jumlah_nominal') border-rose-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Aset <span class="text-rose-500">*</span></label>
                    <select name="akun_aset_id" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('akun_aset_id') border-rose-500 @enderror" required>
                        <option value="">Pilih Akun Aset</option>
                        @foreach ($akunAset as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Kas/Bank <span class="text-rose-500">*</span></label>
                    <select name="akun_kas_id" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('akun_kas_id') border-rose-500 @enderror" required>
                        <option value="">Pilih Akun Kas/Bank</option>
                        @foreach ($akunKas as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Akun Beban <span class="text-rose-500">*</span></label>
                    <select name="akun_beban_id" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all @error('akun_beban_id') border-rose-500 @enderror" required>
                        <option value="">Pilih Akun Beban</option>
                        @foreach ($akunBeban as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="flex items-center bg-[#005198] hover:bg-[#003d73] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.pembayaran-dimuka.index', ['usaha_id' => $selectedUsahaId]) }}" class="flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors border border-slate-200">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Batal
                </a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
