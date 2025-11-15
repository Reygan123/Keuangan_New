@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <h1 class="text-xl font-semibold text-slate-100 mb-5">Edit Receipt</h1>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-500 bg-opacity-20 text-red-300 text-sm rounded border border-red-500 border-opacity-30">
            @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
        </div>
    @endif

    <form action="{{ route('admin.receipts.update', $receipt->id) }}" method="POST" id="receiptForm" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">No Receipt</label>
                <input type="text" name="nomor_receipt" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ old('nomor_receipt', $receipt->nomor_receipt) }}" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Transaksi <span class="text-red-400">*</span></label>
                <select id="transaksi_id" name="transaksi_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required>
                    <option value="">Pilih Transaksi</option>
                    @foreach($transaksis as $transaksi)
                        <option value="{{ $transaksi->id }}" data-jumlah="{{ $transaksi->jumlah }}" {{ old('transaksi_id', $receipt->transaksi_id) == $transaksi->id ? 'selected' : '' }}>
                            @if($transaksi->pelanggan) {{ $transaksi->pelanggan->nama }} @elseif($transaksi->supplier) {{ $transaksi->supplier->nama }} @else Umum @endif - Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Total</label>
                <input type="text" id="total_transaksi" class="w-full bg-slate-600 border border-slate-500 text-slate-100 text-sm px-3 py-2 rounded" value="{{ number_format($receipt->transaksi->jumlah, 0, ',', '.') }}" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Jumlah Dibayar <span class="text-red-400">*</span></label>
                <input type="number" id="jumlah_dibayar" name="jumlah_dibayar" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ old('jumlah_dibayar', $receipt->jumlah_dibayar) }}" min="0" step="0.01" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Kembalian</label>
                <input type="number" id="kembalian" name="kembalian" class="w-full bg-slate-600 border border-slate-500 text-slate-100 text-sm px-3 py-2 rounded" value="{{ old('kembalian', $receipt->kembalian) }}" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Mesin Kasir</label>
                <input type="text" name="mesin_kasir_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ old('mesin_kasir_id', $receipt->mesin_kasir_id) }}">
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 hover:bg-blue-600 text-slate-100 text-sm rounded transition">Update</button>
            <a href="{{ route('admin.receipts.show', $receipt->id) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Lihat Detail</a>
            <a href="{{ route('admin.receipts.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('transaksi_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const jumlah = parseFloat(opt.dataset.jumlah) || 0;
    document.getElementById('total_transaksi').value = new Intl.NumberFormat('id-ID').format(jumlah);
    hitung();
});

document.getElementById('jumlah_dibayar').addEventListener('input', hitung);

function hitung() {
    const opt = document.getElementById('transaksi_id').options[document.getElementById('transaksi_id').selectedIndex];
    const total = parseFloat(opt.dataset.jumlah) || 0;
    const dibayar = parseFloat(document.getElementById('jumlah_dibayar').value) || 0;
    const kembalian = Math.max(0, dibayar - total);
    document.getElementById('kembalian').value = kembalian.toFixed(2);
}

hitung();
</script>
@endsection
