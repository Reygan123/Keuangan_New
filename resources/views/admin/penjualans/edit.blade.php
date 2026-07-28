@extends('layouts.admin.app')

@section('content')
    <div class="p-4 sm:p-6 max-w-6xl mx-auto bg-[#F5F7FA] font-sans">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#0A192F] mb-6">Edit Penjualan</h1>

        <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-[#EAF3FB] border border-[#005198]/20 rounded-xl p-3">
                    <svg class="w-5 h-5 text-[#005198]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-[#94A3B8] text-xs">Usaha</p>
                    <p class="text-[#0A192F] font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 sm:p-6">
            <form action="{{ route('admin.penjualans.update', $penjualan) }}" method="POST" id="form-penjualan">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-[#334155] text-xs font-semibold uppercase mb-2">Label Transaksi</label>
                        <select name="label_id"
                            class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-sm focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 transition">
                            @foreach ($labels as $l)
                                <option value="{{ $l->id }}" @selected($penjualan->label_id == $l->id)>
                                    {{ $l->name ?? $l->nama_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[#334155] text-xs font-semibold uppercase mb-2">Pelanggan</label>
                        <select name="pelanggan_id"
                            class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-sm focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 transition">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($pelanggans as $p)
                                <option value="{{ $p->id }}" @selected($penjualan->pelanggan_id == $p->id)>{{ $p->name ?? $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[#334155] text-xs font-semibold uppercase mb-2">Akun Pembayaran</label>
                        <select name="akun_payment_id"
                            class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-sm focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 transition">
                            @foreach ($akuns as $a)
                                <option value="{{ $a->id }}" @selected($penjualan->akun_payment_id == $a->id)>{{ $a->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[#334155] text-xs font-semibold uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $penjualan->tanggal }}"
                            class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-sm focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 transition">
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-[#334155] text-xs font-semibold uppercase mb-3">Daftar Produk Terjual</h3>
                    <div class="bg-[#F8FAFC] rounded-xl border border-[#EEF2F6] overflow-x-auto">
                        <table class="min-w-full text-sm" id="produk-rows-table">
                            <thead class="bg-white border-b border-[#EEF2F6]">
                                <tr>
                                    <th class="px-3 py-2 text-left text-[#334155] text-xs font-semibold">Produk</th>
                                    <th class="px-3 py-2 text-left text-[#334155] text-xs font-semibold">Qty</th>
                                    <th class="px-3 py-2 text-left text-[#334155] text-xs font-semibold">Harga Satuan</th>
                                    <th class="px-3 py-2 text-center text-[#334155] text-xs font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="produk-rows" class="divide-y divide-[#EEF2F6]">
                                @foreach ($penjualan->detailProduks as $detail)
                                    <tr class="produk-row bg-white hover:bg-[#F8FAFC] transition">
                                        <td class="px-3 py-2">
                                            <select name="product_id[]"
                                                class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-xs focus:outline-none focus:border-[#005198]">
                                                @foreach ($products as $prod)
                                                    <option value="{{ $prod->id }}" @selected($detail->product_id == $prod->id)>
                                                        {{ $prod->nama ?? $prod->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="kuantitas[]" step="0.01" min="0"
                                                value="{{ $detail->kuantitas }}"
                                                class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-xs input-qty focus:outline-none focus:border-[#005198]">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="harga_satuan[]" step="0.01" min="0"
                                                value="{{ $detail->harga_satuan }}"
                                                class="w-full rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] px-3 py-2 text-xs input-harga focus:outline-none focus:border-[#005198]">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button"
                                                class="btn-remove-row px-2 py-1 bg-[#FEF2F2] hover:bg-[#FFE4E6] text-[#E11D48] rounded text-xs font-semibold transition">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 flex justify-between items-center">
                        <button type="button" id="btn-add-row"
                            class="bg-[#005198] hover:bg-[#003d73] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                            Tambah Produk
                        </button>
                        <div class="text-right">
                            <p class="text-[#94A3B8] text-xs">Total:</p>
                            <p id="total-display" class="text-xl sm:text-2xl font-bold text-[#0A192F]">Rp
                                {{ number_format($penjualan->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-[#334155] text-xs font-semibold uppercase mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full bg-white border border-[#E2E8F0] rounded-lg text-[#0F172A] text-sm px-3 py-2 focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 transition">{{ $penjualan->keterangan }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button type="submit"
                        class="bg-[#059669] hover:bg-[#047857] text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                        Perbarui
                    </button>
                    <a href="{{ route('admin.penjualans.index') }}"
                        class="bg-white border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#334155] font-semibold px-6 py-2 rounded-lg text-sm text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
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