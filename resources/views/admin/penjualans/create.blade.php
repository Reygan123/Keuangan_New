@extends('layouts.admin.app')

@section('content')
    <div class="p-4 sm:p-6 max-w-6xl mx-auto">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Tambah Penjualan</h1>

        <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4 mb-6">
            <form method="GET" action="{{ route('admin.penjualans.create') }}" class="flex flex-col sm:flex-row gap-3">
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
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4 sm:p-6">
                <form action="{{ route('admin.penjualans.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                        <div>
                            <label class="block text-slate-300 text-xs font-semibold uppercase mb-2">Label Transaksi</label>
                            <select name="label_id"
                                class="w-full bg-slate-700/50 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                                @foreach ($labels as $l)
                                    <option value="{{ $l->id }}">{{ $l->name ?? $l->nama_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-300 text-xs font-semibold uppercase mb-2">Pelanggan</label>
                            <select name="pelanggan_id"
                                class="w-full bg-slate-700/50 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach ($pelanggans as $p)
                                    <option value="{{ $p->id }}">{{ $p->name ?? $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-300 text-xs font-semibold uppercase mb-2">Akun Pembayaran</label>
                            <select name="akun_payment_id"
                                class="w-full bg-slate-700/50 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                                @foreach ($akuns as $a)
                                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-300 text-xs font-semibold uppercase mb-2">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                                class="w-full bg-slate-700/50 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                        </div>
                        <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                    </div>

                    <div class="mb-6">
                        <h3 class="text-slate-300 text-xs font-semibold uppercase mb-3">Daftar Produk Terjual</h3>
                        <div class="bg-slate-900/30 rounded-lg border border-slate-700/60 overflow-x-auto">
                            <table class="min-w-full text-sm" id="produk-rows-table">
                                <thead class="bg-slate-900/50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-slate-300 text-xs font-semibold">Produk</th>
                                        <th class="px-3 py-2 text-left text-slate-300 text-xs font-semibold">Qty</th>
                                        <th class="px-3 py-2 text-left text-slate-300 text-xs font-semibold">Harga Satuan
                                        </th>
                                        <th class="px-3 py-2 text-center text-slate-300 text-xs font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="produk-rows" class="divide-y divide-slate-700/60">
                                    <tr class="produk-row bg-slate-800/20 hover:bg-slate-800/40 transition">
                                        <td class="px-3 py-2">
                                            <select name="product_id[]"
                                                class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-xs focus:outline-none focus:border-blue-500">
                                                @foreach ($products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->nama ?? $prod->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="kuantitas[]" step="0.01" min="0"
                                                value="1"
                                                class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-xs input-qty focus:outline-none focus:border-blue-500">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="harga_satuan[]" step="0.01" min="0"
                                                value="0"
                                                class="w-full rounded-lg bg-slate-700/50 border border-slate-600/50 text-white px-3 py-2 text-xs input-harga focus:outline-none focus:border-blue-500">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button"
                                                class="btn-remove-row px-2 py-1 bg-red-600/80 hover:bg-red-600 text-white rounded text-xs transition">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <button type="button" id="btn-add-row"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                                Tambah Produk
                            </button>
                            <div class="text-right">
                                <p class="text-slate-400 text-xs">Total:</p>
                                <p id="total-display" class="text-xl sm:text-2xl font-bold text-white">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-slate-300 text-xs font-semibold uppercase mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="2"
                            class="w-full bg-slate-700/50 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                            Simpan
                        </button>
                        <a href="{{ route('admin.penjualans.index') }}"
                            class="bg-slate-700 hover:bg-slate-600 text-white font-semibold px-6 py-2 rounded-lg text-sm text-center transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function formatRupiah(num) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID', {
                        maximumFractionDigits: 0
                    }).format(num);
                }

                function calculateTotal() {
                    let total = 0;
                    document.querySelectorAll('#produk-rows tr.produk-row').forEach(function(row) {
                        const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
                        const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
                        total += qty * harga;
                    });
                    document.getElementById('total-display').innerText = formatRupiah(total);
                }

                function attachRowEvents(row) {
                    row.querySelectorAll('.input-qty, .input-harga').forEach(input => {
                        input.addEventListener('input', calculateTotal);
                    });
                    const btn = row.querySelector('.btn-remove-row');
                    btn.addEventListener('click', function() {
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

                document.getElementById('btn-add-row').addEventListener('click', function() {
                    const tbody = document.getElementById('produk-rows');
                    const first = tbody.querySelector('tr.produk-row');
                    const clone = first.cloneNode(true);
                    clone.querySelectorAll('input').forEach(i => i.value = i.classList.contains('input-qty') ?
                        '1' : '0');
                    clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                    tbody.appendChild(clone);
                    attachRowEvents(clone);
                    calculateTotal();
                });

                document.querySelectorAll('#produk-rows tr.produk-row').forEach(r => attachRowEvents(r));
                calculateTotal();
            });
        </script>
    @endpush
@endsection
