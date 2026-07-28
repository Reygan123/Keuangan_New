@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Edit Invoice</h1>
            <p class="text-[14px] text-[#64748B] mt-1">{{ $invoice->nomor_invoice }}</p>
        </div>
        @if($usahas->count() > 1)
        <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-xl px-4 py-2.5 flex items-center gap-2">
            <span class="text-[13px] text-[#64748B] font-medium">Usaha:</span>
            <span class="text-[13px] text-[#0F172A] font-bold">{{ $currentUsaha->nama }}</span>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                @if(session('error'))
                    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nomor Invoice <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nomor_invoice" value="{{ old('nomor_invoice', $invoice->nomor_invoice) }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all @error('nomor_invoice') border-[#F43F5E] @enderror" required>
                        @error('nomor_invoice')<p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Transaksi</label>
                        <select name="transaksi_id" id="transaksi_id" class="w-full appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer @error('transaksi_id') border-[#F43F5E] @enderror">
                            <option value="">Pilih Transaksi (Opsional)</option>
                            @foreach($transaksis as $transaksi)
                                <option value="{{ $transaksi->id }}" data-jumlah="{{ $transaksi->jumlah }}" {{ old('transaksi_id', $invoice->transaksi_id) == $transaksi->id ? 'selected' : '' }}>
                                    {{ $transaksi->pelanggan?->nama ?? $transaksi->supplier?->nama ?? 'Tanpa Relasi' }} - Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('transaksi_id')<p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div id="additionalFields" class="{{ $invoice->transaksi_id ? 'hidden' : '' }} md:col-span-2 space-y-4 bg-[#F8FAFC] border border-[#EEF2F6] p-5 rounded-[16px]">
                        <h3 class="text-[14px] font-bold text-[#0F172A]">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">To (Client Name) <span class="text-[#E11D48]">*</span></label>
                                <input type="text" name="to_client_name" id="to_client_name" value="{{ old('to_client_name', $invoice->to_client_name) }}" class="w-full bg-white border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] transition-all">
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Bank</label>
                                <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank', $invoice->nama_bank) }}" class="w-full bg-white border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] transition-all">
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nomor Rekening</label>
                                <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $invoice->nomor_rekening) }}" class="w-full bg-white border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] transition-all">
                            </div>
                        </div>
                    </div>

                    <div id="invoiceItemsSection" class="{{ $invoice->transaksi_id ? 'hidden' : '' }} md:col-span-2">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-[14px] font-bold text-[#0F172A]">Invoice Items</h3>
                        </div>
                        <div class="space-y-3">
                            <div id="itemsContainer">
                                @if($invoice->invoiceItems->count() > 0)
                                    @foreach($invoice->invoiceItems as $index => $item)
                                        <div class="item-row grid grid-cols-12 gap-3 mb-3">
                                            <div class="col-span-6">
                                                <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" placeholder="Description" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                                            </div>
                                            <div class="col-span-2">
                                                <input type="number" name="items[{{ $index }}][qty]" value="{{ $item->qty }}" placeholder="Qty" min="1" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                                            </div>
                                            <div class="col-span-3">
                                                <input type="number" name="items[{{ $index }}][harga]" value="{{ $item->harga }}" placeholder="Harga" min="0" step="0.01" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                                            </div>
                                            <div class="col-span-1 flex items-center justify-center">
                                                <button type="button" onclick="removeItem(this)" class="text-[#F43F5E] hover:text-[#E11D48] p-2 bg-[#FEF2F2] rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item-row grid grid-cols-12 gap-3 mb-3">
                                        <div class="col-span-6">
                                            <input type="text" name="items[0][description]" placeholder="Description" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                                        </div>
                                        <div class="col-span-2">
                                            <input type="number" name="items[0][qty]" placeholder="Qty" min="1" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                                        </div>
                                        <div class="col-span-3">
                                            <input type="number" name="items[0][harga]" placeholder="Harga" min="0" step="0.01" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                                        </div>
                                        <div class="col-span-1 flex items-center justify-center">
                                            <button type="button" onclick="removeItem(this)" class="text-[#F43F5E] hover:text-[#E11D48] p-2 bg-[#FEF2F2] rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addItem()" class="flex items-center gap-2 px-4 py-2 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] text-[13px] font-semibold rounded-xl transition-colors border border-[#EEF2F6]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Item
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Jatuh Tempo <span class="text-[#E11D48]">*</span></label>
                        <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $invoice->tanggal_jatuh_tempo) }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all @error('tanggal_jatuh_tempo') border-[#F43F5E] @enderror" required>
                        @error('tanggal_jatuh_tempo')<p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jumlah Pajak</label>
                        <div class="flex">
                            <span class="px-4 py-2.5 bg-[#F1F5F9] border border-[#EEF2F6] border-r-0 text-[#64748B] text-[13px] rounded-l-xl font-medium">Rp</span>
                            <input type="number" name="jumlah_pajak" id="jumlah_pajak" value="{{ old('jumlah_pajak', $invoice->jumlah_pajak ?? 0) }}" class="flex-1 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-r-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" min="0" step="0.01">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Status Invoice <span class="text-[#E11D48]">*</span></label>
                        <select name="status_invoice" class="w-full appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer" required>
                            <option value="pending" {{ old('status_invoice', $invoice->status_invoice) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status_invoice', $invoice->status_invoice) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('status_invoice', $invoice->status_invoice) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="overdue" {{ old('status_invoice', $invoice->status_invoice) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Terms Pembayaran</label>
                        <textarea name="terms_pembayaran" rows="2" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none">{{ old('terms_pembayaran', $invoice->terms_pembayaran) }}</textarea>
                    </div>
                </div>

                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[16px] p-5">
                    <h3 class="text-[14px] font-bold text-[#0F172A] mb-4">Ringkasan</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Subtotal</p>
                            <p id="subtotal" class="text-[18px] font-bold text-[#0F172A]">Rp 0</p>
                        </div>
                        <div>
                            <p class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Pajak</p>
                            <p id="pajak" class="text-[18px] font-bold text-[#0F172A]">Rp 0</p>
                        </div>
                        <div class="col-span-2 border-t border-[#EEF2F6] pt-4">
                            <p class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Total</p>
                            <p id="total" class="text-[24px] font-bold text-[#3B82F6]">Rp 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC] flex justify-end gap-3 flex-wrap">
                <a href="{{ route('admin.invoices.index', ['usaha_id' => $currentUsaha->id]) }}" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                    Update Invoice
                </button>
            </div>
        </form>
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
            <input type="text" name="items[${itemCount}][description]" placeholder="Description" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemCount}][qty]" placeholder="Qty" min="1" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${itemCount}][harga]" placeholder="Harga" min="0" step="0.01" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
        </div>
        <div class="col-span-1 flex items-center justify-center">
            <button type="button" onclick="removeItem(this)" class="text-[#F43F5E] hover:text-[#E11D48] p-2 bg-[#FEF2F2] rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
