@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Buat Kuitansi Baru</h1>
            @if($usahas->count() > 1)
            <select id="usahaSelect" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" onchange="changeUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
        </div>

        @if(!$currentUsaha)
        <div class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">Pilih usaha terlebih dahulu</div>
        @endif

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.kuitansi.store', ['usaha_id' => $currentUsaha?->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Transaksi <span class="text-red-400">*</span></label>
                        <select name="transaksi_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" >
                            <option value="">Pilih Transaksi</option>
                            @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}">{{ $transaksi->keterangan }} - {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Kuitansi <span class="text-red-400">*</span></label>
                        <input type="text" name="nomor_kuitansi" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Pembayaran <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_pembayaran" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Metode Pembayaran <span class="text-red-400">*</span></label>
                        <input type="text" name="metode_pembayaran" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Jumlah Dibayar <span class="text-red-400">*</span></label>
                        <input type="number" name="jumlah_dibayar" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tanda Tangan Penerima <span class="text-red-400">*</span></label>
                        <input type="text" name="tanda_tangan_penerima" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" >
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.kuitansi.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition" {{ !$currentUsaha ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.kuitansi.create") }}?usaha_id=' + usahaId;
}
</script>
@endsection
