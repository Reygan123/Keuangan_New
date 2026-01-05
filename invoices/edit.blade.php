@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Edit Invoice</h1>
            @if($usahas->count() > 1)
            <div class="flex items-center gap-2">
                <span class="text-slate-400 text-sm">Usaha:</span>
                <span class="text-slate-200 text-sm font-medium">{{ $currentUsaha->nama }}</span>
            </div>
            @endif
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Invoice <span class="text-red-400">*</span></label>
                            <input type="text" name="nomor_invoice" value="{{ old('nomor_invoice', $invoice->nomor_invoice) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nomor_invoice') border-red-500 @enderror" required>
                            @error('nomor_invoice')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Transaksi</label>
                            <select name="transaksi_id" id="transaksi_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('transaksi_id') border-red-500 @enderror">
                                <option value="">Pilih Transaksi (Opsional)</option>
                                @foreach($transaksis as $transaksi)
                                    <option value="{{ $transaksi->id }}" data-jumlah="{{ $transaksi->jumlah }}" {{ old('transaksi_id', $invoice->transaksi_id) == $transaksi->id ? 'selected' : '' }}>
                                        {{ $transaksi->pelanggan ? $transaksi->pelanggan->nama : $transaksi->supplier->nama }} - Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaksi_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div id="additionalFields" class="{{ $invoice->transaksi_id ? 'hidden' : '' }} md:col-span-2 space-y-4 bg-slate-700/50 p-4 rounded-lg">
                            <h3 class="text-sm font-semibold text-slate-300">Informasi Pembayaran</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">To (Client Name) <span class="text-red-400">*</span></label>
                                    <input type="text" name="to_client_name" id="to_client_name" value="{{ old('to_client_name', $invoice->to_client_name) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Bank</label>
                                    <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank', $invoice->nama_bank) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Rekening</label>
                                    <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $invoice->nomor_rekening) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div id="invoiceItemsSection" class="{{ $invoice->transaksi_id ? 'hidden' : '' }} md:col-span-2">
                            <h3 class="text-sm font-semibold text-slate-300 mb-3">Invoice Items</h3>
                            <div class="space-y-4">
                                <div id="itemsContainer">
                                    @if($invoice->invoiceItems->count() > 0)
                                        @foreach($invoice->invoiceItems as $index => $item)
                                            <div class="item-row grid grid-cols-12 gap-3 mb-3">
                                                <div class="col-span-6">
                                                    <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" placeholder="Description" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
                                                </div>
                                                <div class="col-span-2">
                                                    <input type="number" name="items[{{ $index }}][qty]" value="{{ $item->qty }}" placeholder="Qty" min="1" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
                                                </div>
                                                <div class="col-span-3">
                                                    <input type="number" name="items[{{ $index }}][harga]" value="{{ $item->harga }}" placeholder="Harga" min="0" step="0.01" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
                                                </div>
                                                <div class="col-span-1 flex items-center justify-center">
                                                    <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="item-row grid grid-cols-12 gap-3 mb-3">
                                            <div class="col-span-6">
                                                <input type="text" name="items[0][description]" placeholder="Description" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
                                            </div>
                                            <div class="col-span-2">
                                                <input type="number" name="items[0][qty]" placeholder="Qty" min="1" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
                                            </div>
                                            <div class="col-span-3">
                                                <input type="number" name="items[0][harga]" placeholder="Harga" min="0" step="0.01" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
                                            </div>
                                            <div class="col-span-1 flex items-center justify-center">
                                                <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" onclick="addItem()" class="flex items-center gap-2 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Item
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Jatuh Tempo <span class="text-red-400">*</span></label>
                            <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $invoice->tanggal_jatuh_tempo) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_jatuh_tempo') border-red-500 @enderror" required>
                            @error('tanggal_jatuh_tempo')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Jumlah Pajak</label>
                            <div class="flex">
                                <span class="px-4 py-2 bg-slate-700 border border-slate-600 border-r-0 text-slate-400 text-sm rounded-l-lg">Rp</span>
                                <input type="number" name="jumlah_pajak" id="jumlah_pajak" value="{{ old('jumlah_pajak', $invoice->jumlah_pajak ?? 0) }}" class="flex-1 px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-r-lg focus:outline-none focus:border-blue-500" min="0" step="0.01">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Status Invoice <span class="text-red-400">*</span></label>
                            <select name="status_invoice" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required>
                                <option value="pending" {{ old('status_invoice', $invoice->status_invoice) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('status_invoice', $invoice->status_invoice) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('status_invoice', $invoice->status_invoice) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="overdue" {{ old('status_invoice', $invoice->status_invoice) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Terms Pembayaran</label>
                            <textarea name="terms_pembayaran" rows="2" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">{{ old('terms_pembayaran', $invoice->terms_pembayaran) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-slate-300 mb-3">Ringkasan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-slate-400 text-xs mb-1">Subtotal</p>
                                <p id="subtotal" class="text-lg font-semibold text-slate-200">Rp 0</p>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs mb-1">Pajak</p>
                                <p id="pajak" class="text-lg font-semibold text-slate-200">Rp 0</p>
                            </div>
                            <div class="col-span-2 border-t border-slate-600 pt-3">
                                <p class="text-slate-400 text-xs mb-1">Total</p>
                                <p id="total" class="text-xl font-bold text-blue-400">Rp 0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end flex-wrap">
                    <a href="{{ route('admin.invoices.index', ['usaha_id' => $currentUsaha->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let itemCount = {{ $invoice->invoiceItems->count() ?: 1 }};

function toggleAdditionalFields() {
    const transaksiSelect = document.getElementById('transaksi_id');
    const additionalFields = document.getElementById('additionalFields');
    const invoiceItemsSection = document.getElementById('invoiceItemsSection');
    const toClientName = document.getElementById('to_client_name');

    if (transaksiSelect.value === '') {
        additionalFields.classList.remove('hidden');
        invoiceItemsSection.classList.remove('hidden');
        toClientName.required = true;
    } else {
        additionalFields.classList.add('hidden');
        invoiceItemsSection.classList.add('hidden');
        toClientName.required = false;
    }
    updateRingkasan();
}

function addItem() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row grid grid-cols-12 gap-3 mb-3';
    newRow.innerHTML = `
        <div class="col-span-6">
            <input type="text" name="items[${itemCount}][description]" placeholder="Description" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemCount}][qty]" placeholder="Qty" min="1" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${itemCount}][harga]" placeholder="Harga" min="0" step="0.01" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg">
        </div>
        <div class="col-span-1 flex items-center justify-center">
            <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    container.appendChild(newRow);
    itemCount++;

    const inputs = newRow.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', updateRingkasan);
    });
}

function removeItem(button) {
    const row = button.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        row.remove();
        updateRingkasan();
    }
}

function calculateItemsTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
        const harga = parseFloat(row.querySelector('input[name*="[harga]"]').value) || 0;
        total += qty * harga;
    });
    return total;
}

function updateRingkasan() {
    const transaksiSelect = document.getElementById('transaksi_id');
    const jumlahPajakInput = document.getElementById('jumlah_pajak');

    let subtotal = 0;

    if (transaksiSelect.value === '') {
        subtotal = calculateItemsTotal();
    } else {
        const selectedOption = transaksiSelect.options[transaksiSelect.selectedIndex];
        subtotal = parseFloat(selectedOption.dataset.jumlah) || 0;
    }

    const pajak = parseFloat(jumlahPajakInput.value) || 0;
    const total = subtotal + pajak;

    document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('pajak').textContent = 'Rp ' + pajak.toLocaleString('id-ID');
    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', function() {
    const transaksiSelect = document.getElementById('transaksi_id');
    const jumlahPajakInput = document.getElementById('jumlah_pajak');

    if (transaksiSelect) {
        transaksiSelect.addEventListener('change', toggleAdditionalFields);
    }

    if (jumlahPajakInput) {
        jumlahPajakInput.addEventListener('input', updateRingkasan);
    }

    document.querySelectorAll('#itemsContainer input').forEach(input => {
        input.addEventListener('input', updateRingkasan);
    });

    updateRingkasan();
});
</script>
@endsection
