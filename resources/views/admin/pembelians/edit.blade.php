@extends('layouts.admin.app')

@section('content')
    <div class="p-4 sm:p-6 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Edit Pembelian #{{ $pembelian->id }}</h1>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-blue-50 border border-blue-200/80 rounded-xl p-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-medium">Usaha</p>
                    <p class="text-slate-900 font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pembelians.update', $pembelian->id) }}" method="POST" id="form-pembelian"
            class="space-y-4">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm shadow-sm">
                    <strong class="block mb-2 font-semibold">Error Validasi!</strong>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            @if (!Str::startsWith($error, 'kuantitas.') && !Str::startsWith($error, 'harga_satuan.'))
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-slate-700 block mb-2 text-xs font-semibold uppercase tracking-wider">Label</label>
                        <select name="label_id"
                            class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            @foreach ($labels as $l)
                                <option value="{{ $l->id }}" @selected($pembelian->label_id == $l->id)>{{ $l->nama_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-slate-700 block mb-2 text-xs font-semibold uppercase tracking-wider">Supplier</label>
                        <select name="supplier_id"
                            class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" @selected($pembelian->supplier_id == $s->id)>{{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-slate-700 block mb-2 text-xs font-semibold uppercase tracking-wider">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $pembelian->tanggal }}"
                            class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>
                    <div>
                        <label class="text-slate-700 block mb-2 text-xs font-semibold uppercase tracking-wider">Akun Pembayaran</label>
                        <select name="akun_payment_id"
                            class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            @foreach ($akuns as $a)
                                <option value="{{ $a->id }}" @selected($pembelian->akun_payment_id == $a->id)>{{ $a->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Detail Produk</h2>
                    <button type="button" id="btn-add-row"
                        class="px-3.5 py-1.5 bg-[#005198] hover:bg-[#003d73] text-white rounded-xl text-xs font-semibold transition-all shadow-sm">Tambah</button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-3 py-2.5 text-left text-slate-600 font-semibold uppercase tracking-wider">Produk</th>
                                <th class="px-3 py-2.5 text-left text-slate-600 font-semibold uppercase tracking-wider w-24">Qty</th>
                                <th class="px-3 py-2.5 text-left text-slate-600 font-semibold uppercase tracking-wider w-32">Harga Satuan</th>
                                <th class="px-3 py-2.5 text-center text-slate-600 font-semibold uppercase tracking-wider w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="produk-rows" class="divide-y divide-slate-100 bg-white">
                            @foreach ($pembelian->detailProduks as $detail)
                                <tr class="produk-row">
                                    <td class="px-3 py-2">
                                        <select name="product_id[]"
                                            class="w-full bg-white border border-slate-300 text-slate-900 px-2.5 py-1.5 rounded-lg text-xs focus:outline-none focus:border-blue-600">
                                            @foreach ($products as $p)
                                                <option value="{{ $p->id }}" @selected($detail->product_id == $p->id)>
                                                    {{ $p->nama ?? $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2"><input type="number" name="kuantitas[]" step="0.01"
                                            min="0" value="{{ $detail->kuantitas }}"
                                            class="w-full bg-white border border-slate-300 text-slate-900 px-2.5 py-1.5 rounded-lg text-xs input-qty focus:outline-none focus:border-blue-600">
                                    </td>
                                    <td class="px-3 py-2"><input type="number" name="harga_satuan[]" step="0.01"
                                            min="0" value="{{ $detail->harga_satuan }}"
                                            class="w-full bg-white border border-slate-300 text-slate-900 px-2.5 py-1.5 rounded-lg text-xs input-harga focus:outline-none focus:border-blue-600">
                                    </td>
                                    <td class="px-3 py-2 text-center"><button type="button"
                                            class="btn-remove-row px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-semibold transition-all">X</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-end items-center gap-3">
                    <span class="text-slate-600 text-sm font-medium">Total:</span>
                    <span id="total-display" class="text-lg font-bold text-slate-900">Rp
                        {{ number_format($pembelian->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5">
                <label class="text-slate-700 block mb-2 text-xs font-semibold uppercase tracking-wider">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    rows="2">{{ $pembelian->keterangan }}</textarea>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm shadow-sm transition-all">Perbarui</button>
                <a href="{{ route('admin.pembelians.index') }}"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm border border-slate-200 transition-all">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatRupiah(n) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(n);
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
                clone.querySelectorAll('input').forEach(i => i.value = i.classList.contains('input-qty') ? '1' :
                    '0');
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