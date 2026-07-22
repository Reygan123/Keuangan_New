@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
        <h1 class="text-xl font-semibold text-slate-100">Edit Nota</h1>
        @if($usahas->count() > 1)
        <div class="flex items-center gap-2">
            <span class="text-slate-400 text-sm">Usaha:</span>
            <span class="text-slate-200 text-sm font-medium">{{ $currentUsaha->nama }}</span>
        </div>
        @endif
    </div>

    <form action="{{ route('admin.nota.update', $nota->id) }}" method="POST" class="space-y-4 max-w-md">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Transaksi <span class="text-red-400">*</span></label>
            <select name="transaksi_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required>
                @foreach($transaksis as $transaksi)
                <option value="{{ $transaksi->id }}" {{ $nota->transaksi_id == $transaksi->id ? 'selected' : '' }}>{{ $transaksi->keterangan ?? 'Transaksi' }} - {{ $transaksi->tanggal }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Nota <span class="text-red-400">*</span></label>
            <input type="text" name="nomor_nota" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ $nota->nomor_nota }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Jenis Nota <span class="text-red-400">*</span></label>
            <input type="text" name="jenis_nota" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ $nota->jenis_nota }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Tunai <span class="text-red-400">*</span></label>
            <select name="is_tunai" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required>
                <option value="1" {{ $nota->is_tunai ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !$nota->is_tunai ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 hover:bg-blue-600 text-slate-100 text-sm rounded transition">Update</button>
            <a href="{{ route('admin.nota.index', ['usaha_id' => $currentUsaha->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Batal</a>
        </div>
    </form>
</div>
@endsection
