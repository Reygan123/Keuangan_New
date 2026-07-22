@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-50 mb-1">Import Jurnal Umum</h1>
            <p class="text-slate-400 text-sm">Upload file Excel jurnal umum, lalu petakan nama akun ke akun di sistem.</p>
        </div>

        @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-900/50 border border-red-700 rounded text-red-300 text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-slate-800 rounded-lg border border-slate-700 p-6">
            <form method="POST" action="{{ route('admin.jurnal-umum.import.preview') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if($usahas->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Usaha</label>
                    <select name="usaha_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                        @foreach($usahas as $u)
                        <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">File Excel (.xlsx)</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required
                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-slate-600 file:text-slate-100 file:text-xs">
                    @error('file')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>
                <div class="bg-slate-700/50 rounded p-4 text-xs text-slate-400 space-y-1">
                    <p class="font-medium text-slate-300">Format Excel yang didukung:</p>
                    <p>— Setiap transaksi terdiri dari 2 baris: baris debit lalu baris kredit</p>
                    <p>— Nama akun debit di kolom C, nilai debit di kolom F</p>
                    <p>— Nama akun kredit di kolom D baris berikutnya</p>
                    <p>— Nama bulan (Januari, Februari, dst) sebagai penanda periode</p>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded transition-colors">
                    Upload & Preview Mapping
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
