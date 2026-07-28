@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900 mb-1">Import Jurnal Umum</h1>
            <p class="text-slate-500 text-sm">Upload file Excel jurnal umum, lalu petakan nama akun ke akun di sistem.</p>
        </div>

        @if(session('error'))
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.jurnal-umum.import.preview') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if($usahas->count() > 0)
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha</label>
                    <select name="usaha_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        @foreach($usahas as $u)
                        <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Bulan</label>
                        <select name="bulan" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        @error('bulan')<p class="mt-1 text-rose-600 text-xs font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun</label>
                        <select name="tahun" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            @for($t = now()->year; $t >= now()->year - 5; $t--)
                            <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                        @error('tahun')<p class="mt-1 text-rose-600 text-xs font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">File Excel (.xlsx)</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required
                        class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:text-xs file:font-semibold hover:file:bg-slate-200">
                    @error('file')<p class="mt-1 text-rose-600 text-xs font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-4 text-xs text-slate-600 space-y-1">
                    <p class="font-semibold text-slate-800">Format Excel yang didukung:</p>
                    <p>— Setiap transaksi terdiri dari 2 baris: baris debit lalu baris kredit</p>
                    <p>— Nama akun debit di kolom C, nilai debit di kolom F</p>
                    <p>— Nama akun kredit di kolom D baris berikutnya</p>
                    <p>— Nama bulan (Januari, Februari, dst) sebagai penanda periode</p>
                </div>
                <button type="submit" class="w-full px-4 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    Upload & Preview Mapping
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
