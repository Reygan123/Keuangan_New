@extends('layouts.admin.app')

@section('content')
    <div class="p-4 sm:p-6 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold text-white mb-6">Tambah Pembelian</h1>

        <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4 mb-6">
            <form method="GET" action="{{ route('admin.pembelians.create') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-slate-400 text-xs font-semibold mb-1">Pilih Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()"
                        class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach ($usahas as $usaha)
                            <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>
                                {{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            @if ($selectedUsahaId)
                <p class="text-slate-300 text-xs mt-2">Transaksi akan dicatat untuk usaha: <span
                        class="font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
            @endif
        </div>

        @if (!$selectedUsahaId)
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-8 text-center">
                <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk membuat transaksi</p>
            </div>
        @else
            <form action="{{ route('admin.pembelians.store') }}" method="POST" id="form-pembelian" class="space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-900/30 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm">
                        <strong class="block mb-2">Error Validasi!</strong>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                @if (!Str::startsWith($error, 'kuantitas.') && !Str::startsWith($error, 'harga_satuan.'))
                                    <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                        <div>
                            <label class="text-slate-300 block mb-2 text-sm font-medium">Label</label>
                            <select name="label_id"
                                class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                                @foreach ($labels as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-slate-300 block mb-2 text-sm font-medium">Supplier</label>
                            <select name="supplier_id"
                                class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-slate-300 block mb-2 text-sm font-medium">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                                class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="text-slate-300 block mb-2 text-sm font-medium">Akun Pembayaran</label>
                            <select name="akun_payment_id"
                                class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                                @foreach ($akuns as $a)
                                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-sm font-semibold text-white uppercase">Detail Produk</h2>
                        <button type="button" id="btn-add-row"
                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">Tambah</button>
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
                                <tr class="produk-row">
                                    <td class="px-3 py-2">
                                        <select name="product_id[]"
                                            class="w-full bg-slate-700/50 border border-slate-700 text-white px-2 py-1 rounded text-xs">
                                            @foreach ($products as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama ?? $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2"><input type="number" name="kuantitas[]" step="0.01"
                                            min="0" value="1"
                                            class="w-full bg-slate-700/50 border border-slate-700 text-white px-2 py-1 rounded text-xs input-qty">
                                    </td>
                                    <td class="px-3 py-2"><input type="number" name="harga_satuan[]" step="0.01"
                                            min="0" value="0"
                                            class="w-full bg-slate-700/50 border border-slate-700 text-white px-2 py-1 rounded text-xs input-harga">
                                    </td>
                                    <td class="px-3 py-2 text-center"><button type="button"
                                            class="btn-remove-row px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">X</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 flex justify-end items-center gap-3">
                        <span class="text-slate-300 text-sm">Total:</span>
                        <span id="total-display" class="text-lg font-semibold text-white">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label class="text-slate-300 block mb-2 text-sm font-medium">Keterangan</label>
                    <textarea name="keterangan"
                        class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500"
                        rows="2"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm">Simpan</button>
                    <a href="{{ route('admin.pembelians.index') }}"
                        class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm">Batal</a>
                </div>
            </form>
        @endif
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
                row.querySelector('.input-qty').addEventListener('input', calculateTotal);
                row.querySelector('.input-harga').addEventListener('input', calculateTotal);
                row.querySelector('.btn-remove-row').addEventListener('click', function() {
                    const tbody = document.getElementById('produk-rows');
                    if (tbody.querySelectorAll('tr.produk-row').length > 1) {
                        row.remove();
                        calculateTotal();
                    }
                });
            }

            document.getElementById('btn-add-row').addEventListener('click', addRow);
            document.querySelectorAll('#produk-rows tr.produk-row').forEach(attachRowEvents);

            calculateTotal();
        });
    </script>
@endsection
