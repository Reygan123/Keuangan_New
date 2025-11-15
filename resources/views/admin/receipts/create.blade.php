@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <h1 class="text-xl font-semibold text-slate-100 mb-5">Tambah Receipt</h1>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-500 bg-opacity-20 text-red-300 text-sm rounded border border-red-500 border-opacity-30">
            @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
        </div>
    @endif

    <form action="{{ route('admin.receipts.store') }}" method="POST" id="receiptForm" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">No Receipt <span class="text-red-400">*</span></label>
                <div class="flex gap-2">
                    <input type="text" name="nomor_receipt" id="nomor_receipt" class="flex-1 bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ old('nomor_receipt') }}" required>
                    <button type="button" id="generateBtn" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Generate</button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Transaksi <span class="text-red-400">*</span></label>
                <select id="transaksi_id" name="transaksi_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" required>
                    <option value="">Pilih Transaksi</option>
                    @foreach($transaksis as $transaksi)
                        <option value="{{ $transaksi->id }}" data-jumlah="{{ $transaksi->jumlah }}">
                            @if($transaksi->pelanggan) {{ $transaksi->pelanggan->nama }} @elseif($transaksi->supplier) {{ $transaksi->supplier->nama }} @else Umum @endif - Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Total</label>
                <input type="text" id="total_transaksi" class="w-full bg-slate-600 border border-slate-500 text-slate-100 text-sm px-3 py-2 rounded" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Jumlah Dibayar <span class="text-red-400">*</span></label>
                <input type="number" id="jumlah_dibayar" name="jumlah_dibayar" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ old('jumlah_dibayar', 0) }}" min="0" step="0.01" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Kembalian</label>
                <input type="number" id="kembalian" name="kembalian" class="w-full bg-slate-600 border border-slate-500 text-slate-100 text-sm px-3 py-2 rounded" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Mesin Kasir</label>
                <input type="text" name="mesin_kasir_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500" value="{{ old('mesin_kasir_id') }}" placeholder="KASIR-001">
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 hover:bg-blue-600 text-slate-100 text-sm rounded transition">Simpan</button>
            <a href="{{ route('admin.receipts.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('generateBtn').addEventListener('click', function() {
    fetch('/receipts/generate-nomor').then(r => r.json()).then(d => {
        document.getElementById('nomor_receipt').value = d.nomor_receipt;
    });
});

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

if (!document.getElementById('nomor_receipt').value) {
    document.getElementById('generateBtn').click();
}
</script>
@endsection
