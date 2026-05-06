@extends('layouts.admin.app')
@section('title', 'Tambah Pembayaran Di Muka')
@section('content')
<div class="min-h-screen bg-slate-950 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.pembayaran-dimuka.index') }}" class="inline-flex items-center text-slate-400 hover:text-slate-300 text-sm mb-4 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-white">Tambah Pembayaran Di Muka</h1>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-900/30 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.pembayaran-dimuka.create') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-slate-400 text-xs font-semibold mb-1">Pilih Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            @if($selectedUsahaId)
            <p class="text-slate-300 text-xs mt-2">Pembayaran akan dicatat untuk usaha: <span class="font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
            @endif
        </div>

        @if(!$selectedUsahaId)
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-8 text-center">
            <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk membuat pembayaran di muka</p>
        </div>
        @else
        <form action="{{ route('admin.pembayaran-dimuka.store') }}" method="POST" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-6 shadow-lg">
            @csrf
            <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Pembayaran <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_pembayaran" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('nama_pembayaran') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Transaksi <span class="text-red-400">*</span></label>
                    <input type="date" name="tgl_transaksi" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('tgl_transaksi') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Periode Mulai <span class="text-red-400">*</span></label>
                    <input type="date" name="periode_mulai" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('periode_mulai') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Periode Akhir <span class="text-red-400">*</span></label>
                    <input type="date" name="periode_akhir" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('periode_akhir') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Jumlah Nominal <span class="text-red-400">*</span></label>
                    <input type="number" name="jumlah_nominal" step="0.01" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('jumlah_nominal') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Akun Aset <span class="text-red-400">*</span></label>
                    <select name="akun_aset_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('akun_aset_id') border-red-500 @enderror" required>
                        <option value="">Pilih Akun Aset</option>
                        @foreach ($akunAset as $akun)
                            <option value="{{ $akun->id }}" class="bg-slate-800">{{ $akun->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Akun Kas/Bank <span class="text-red-400">*</span></label>
                    <select name="akun_kas_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('akun_kas_id') border-red-500 @enderror" required>
                        <option value="">Pilih Akun Kas/Bank</option>
                        @foreach ($akunKas as $akun)
                            <option value="{{ $akun->id }}" class="bg-slate-800">{{ $akun->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Akun Beban <span class="text-red-400">*</span></label>
                    <select name="akun_beban_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all @error('akun_beban_id') border-red-500 @enderror" required>
                        <option value="">Pilih Akun Beban</option>
                        @foreach ($akunBeban as $akun)
                            <option value="{{ $akun->id }}" class="bg-slate-800">{{ $akun->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.pembayaran-dimuka.index', ['usaha_id' => $selectedUsahaId]) }}" class="flex items-center bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Batal
                </a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
