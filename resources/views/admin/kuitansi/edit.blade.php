@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Edit Kuitansi</h1>
            @if($usahas->count() > 1)
            <div class="flex items-center gap-2">
                <span class="text-slate-400 text-sm">Usaha:</span>
                <span class="text-slate-200 text-sm font-medium">{{ $currentUsaha->nama }}</span>
            </div>
            @endif
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.kuitansi.update', $kuitansi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Transaksi <span class="text-red-400">*</span></label>
                        <select name="transaksi_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                            @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}" {{ $kuitansi->transaksi_id == $transaksi->id ? 'selected' : '' }}>{{ $transaksi->keterangan }} - {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Kuitansi <span class="text-red-400">*</span></label>
                        <input type="text" name="nomor_kuitansi" value="{{ $kuitansi->nomor_kuitansi }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Pembayaran <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_pembayaran" value="{{ $kuitansi->tanggal_pembayaran }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Metode Pembayaran <span class="text-red-400">*</span></label>
                        <input type="text" name="metode_pembayaran" value="{{ $kuitansi->metode_pembayaran }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Jumlah Dibayar <span class="text-red-400">*</span></label>
                        <input type="number" name="jumlah_dibayar" value="{{ $kuitansi->jumlah_dibayar }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tanda Tangan Penerima <span class="text-red-400">*</span></label>
                        <input type="text" name="tanda_tangan_penerima" value="{{ $kuitansi->tanda_tangan_penerima }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.kuitansi.index', ['usaha_id' => $currentUsaha->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
