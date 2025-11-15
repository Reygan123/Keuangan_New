@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Edit Pembelian #{{ $pembelian->id }}</h1>

    <form action="{{ route('admin.pembelians.update', $pembelian->id) }}" method="POST" id="form-pembelian" class="space-y-4">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="bg-red-900/30 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm">
                <strong class="block mb-2">Error Validasi!</strong>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        @if(!Str::startsWith($error, 'kuantitas.') && !Str::startsWith($error, 'harga_satuan.'))
                            <li>{{ $error }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-slate-300 block mb-2 text-sm font-medium">Label</label>
                    <select name="label_id" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        @foreach($labels as $l)
                            <option value="{{ $l->id }}" @selected($pembelian->label_id == $l->id)>{{ $l->nama_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-slate-300 block mb-2 text-sm font-medium">Supplier</label>
                    <select name="supplier_id" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected($pembelian->supplier_id == $s->id)>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-slate-300 block mb-2 text-sm font-medium">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $pembelian->tanggal }}" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="text-slate-300 block mb-2 text-sm font-medium">Akun Pembayaran</label>
                    <select name="akun_payment_id" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        @foreach($akuns as $a)
                            <option value="{{ $a->id }}" @selected($pembelian->akun_payment_id == $a->id)>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-sm font-semibold text-white uppercase">Detail Produk</h2>
                <button type="button" id="btn-add-row" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">Tambah</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-slate-300">Produk</th>
                            <th class="px-3 py-2 text-left text-slate-300 w-24">Qty</th>
                            <th class="px-3 py-2 text-left text-slate-300 w-32">Harga Satuan</th>
                            <th class="px-3 py-2 text-center text-slate-300 w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="produk-rows" class="divide-y divide-slate-700">
                        @foreach($pembelian->detailProduks as $detail)
                        <tr class="produk-row">
                            <td class="px-3 py-2">
                                <select name="product_id[]" class="w-full bg-slate-700/50 border border-slate-700 text-white px-2 py-1 rounded text-xs">
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" @selected($detail->product_id == $p->id)>{{ $p->nama ?? $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-2"><input type="number" name="kuantitas[]" step="0.01" min="0" value="{{ $detail->kuantitas }}" class="w-full bg-slate-700/50 border border-slate-700 text-white px-2 py-1 rounded text-xs input-qty"></td>
                            <td class="px-3 py-2"><input type="number" name="harga_satuan[]" step="0.01" min="0" value="{{ $detail->harga_satuan }}" class="w-full bg-slate-700/50 border border-slate-700 text-white px-2 py-1 rounded text-xs input-harga"></td>
                            <td class="px-3 py-2 text-center"><button type="button" class="btn-remove-row px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">X</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-end items-center gap-3">
                <span class="text-slate-300 text-sm">Total:</span>
                <span id="total-display" class="text-lg font-semibold text-white">Rp {{ number_format($pembelian->jumlah, 0, ',', '.') }}</span>
            </div>
        </div>

        <div>
            <label class="text-slate-300 block mb-2 text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500" rows="2">{{ $pembelian->keterangan }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm">Perbarui</button>
            <a href="{{ route('admin.pembelians.index') }}" class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function formatRupiah(n) {
        return new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', minimumFractionDigits: 0}).format(n);
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('#produk-rows tr.produk-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            total += qty * harga;
        });
        document.getElementById('total-display').textContent = formatRupiah(total);
    }

    function addRow() {
        const tbody = document.getElementById('produk-rows');
        const first = tbody.querySelector('tr.produk-row');
        const clone = first.cloneNode(true);
        clone.querySelectorAll('input').forEach(i => i.value = i.classList.contains('input-qty') ? '1' : '0');
        clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
        tbody.appendChild(clone);
        attachRowEvents(clone);
        calculateTotal();
    }

    function attachRowEvents(row) {
        row.querySelectorAll('.input-qty, .input-harga').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
        row.querySelector('.btn-remove-row').addEventListener('click', function() {
            const tbody = document.getElementById('produk-rows');
            if (tbody.querySelectorAll('tr.produk-row').length > 1) {
                row.remove();
            } else {
                row.querySelector('.input-qty').value = '1';
                row.querySelector('.input-harga').value = '0';
            }
            calculateTotal();
        });
    }

    document.getElementById('btn-add-row').addEventListener('click', addRow);
    document.querySelectorAll('#produk-rows tr.produk-row').forEach(attachRowEvents);
    calculateTotal();
});
</script>
@endsection
