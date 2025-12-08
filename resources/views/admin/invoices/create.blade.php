@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Buat Invoice Baru</h1>
            @if($usahas->count() > 1)
            <select id="usahaSelect" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" onchange="changeUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.invoices.store', ['usaha_id' => $currentUsaha?->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">{{ session('error') }}</div>
                    @endif

                    @if(!$currentUsaha)
                        <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">Pilih usaha terlebih dahulu</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Invoice <span class="text-red-400">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="nomor_invoice" id="nomor_invoice" value="{{ old('nomor_invoice') }}" class="flex-1 px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nomor_invoice') border-red-500 @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                <button type="button" id="generateBtn" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition" {{ !$currentUsaha ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                            @error('nomor_invoice')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-slate-400 text-xs mt-1">Format: INV/YYYYMMDD/0001</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Transaksi <span class="text-red-400">*</span></label>
                            <select name="transaksi_id" id="transaksi_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('transaksi_id') border-red-500 @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                <option value="">Pilih Transaksi</option>
                                @foreach($transaksis as $transaksi)
                                    <option value="{{ $transaksi->id }}" data-jumlah="{{ $transaksi->jumlah }}" {{ old('transaksi_id') == $transaksi->id ? 'selected' : '' }}>
                                        {{ $transaksi->pelanggan ? $transaksi->pelanggan->nama : $transaksi->supplier->nama }} - Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaksi_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Jatuh Tempo <span class="text-red-400">*</span></label>
                            <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_jatuh_tempo') border-red-500 @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                            @error('tanggal_jatuh_tempo')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Jumlah Pajak</label>
                            <div class="flex">
                                <span class="px-4 py-2 bg-slate-700 border border-slate-600 border-r-0 text-slate-400 text-sm rounded-l-lg">Rp</span>
                                <input type="number" name="jumlah_pajak" id="jumlah_pajak" value="{{ old('jumlah_pajak', 0) }}" class="flex-1 px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-r-lg focus:outline-none focus:border-blue-500" min="0" step="0.01" {{ !$currentUsaha ? 'disabled' : '' }}>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Status Invoice <span class="text-red-400">*</span></label>
                            <select name="status_invoice" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                <option value="pending" {{ old('status_invoice') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('status_invoice') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('status_invoice') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="overdue" {{ old('status_invoice') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Terms Pembayaran</label>
                            <textarea name="terms_pembayaran" rows="2" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" {{ !$currentUsaha ? 'disabled' : '' }}>{{ old('terms_pembayaran') }}</textarea>
                            <p class="text-slate-400 text-xs mt-1">Contoh: Pembayaran dalam 30 hari</p>
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

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.invoices.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition" {{ !$currentUsaha ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.invoices.create") }}?usaha_id=' + usahaId;
}

document.addEventListener('DOMContentLoaded', function() {
    const transaksiSelect = document.getElementById('transaksi_id');
    const jumlahPajakInput = document.getElementById('jumlah_pajak');
    const generateBtn = document.getElementById('generateBtn');
    const currentUsahaId = {{ $currentUsaha?->id ?? 'null' }};

    generateBtn.addEventListener('click', function() {
        if (!currentUsahaId) return;

        fetch('/invoices/generate-nomor?usaha_id=' + currentUsahaId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('nomor_invoice').value = data.nomor_invoice;
            })
            .catch(error => console.error('Error:', error));
    });

    function updateRingkasan() {
        const selectedOption = transaksiSelect.options[transaksiSelect.selectedIndex];
        const subtotal = parseFloat(selectedOption.dataset.jumlah) || 0;
        const pajak = parseFloat(jumlahPajakInput.value) || 0;
        const total = subtotal + pajak;

        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('pajak').textContent = 'Rp ' + pajak.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    if (transaksiSelect) {
        transaksiSelect.addEventListener('change', updateRingkasan);
        jumlahPajakInput.addEventListener('input', updateRingkasan);
        updateRingkasan();
    }
});
</script>
@endsection
