@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
        <h1 class="text-xl font-semibold text-slate-100">Tambah Nota</h1>
        @if($usahas->count() > 1)
        <select id="usahaSelect" class="px-3 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded focus:outline-none focus:border-slate-600" onchange="changeUsaha()">
            @foreach($usahas as $usahaItem)
            <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
            @endforeach
        </select>
        @endif
    </div>

    @if(!$currentUsaha)
    <div class="mb-4 p-3 bg-red-500 bg-opacity-20 text-red-300 text-sm rounded border border-red-500 border-opacity-30">Pilih usaha terlebih dahulu</div>
    @endif

    <form action="{{ route('admin.nota.store', ['usaha_id' => $currentUsaha?->id]) }}" method="POST" class="space-y-4 max-w-md">
        @csrf
        <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Transaksi <span class="text-red-400">*</span></label>
            <select name="transaksi_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required {{ !$currentUsaha ? 'disabled' : '' }}>
                <option value="">Pilih Transaksi</option>
                @foreach($transaksis as $transaksi)
                <option value="{{ $transaksi->id }}">{{ $transaksi->keterangan ?? 'Transaksi' }} - {{ $transaksi->tanggal }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Nota <span class="text-red-400">*</span></label>
            <input type="text" name="nomor_nota" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required {{ !$currentUsaha ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Jenis Nota <span class="text-red-400">*</span></label>
            <input type="text" name="jenis_nota" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required {{ !$currentUsaha ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Tunai <span class="text-red-400">*</span></label>
            <select name="is_tunai" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required {{ !$currentUsaha ? 'disabled' : '' }}>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </select>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 hover:bg-blue-600 text-slate-100 text-sm rounded transition" {{ !$currentUsaha ? 'disabled' : '' }}>Simpan</button>
            <a href="{{ route('admin.nota.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Batal</a>
        </div>
    </form>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.nota.create") }}?usaha_id=' + usahaId;
}
</script>
@endsection
