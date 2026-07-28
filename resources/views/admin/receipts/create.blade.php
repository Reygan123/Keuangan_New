@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Buat Receipt Baru</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Buat receipt untuk transaksi</p>
        </div>
        @if($usahas->count() > 1)
        <select id="usahaSelect" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]" onchange="changeUsaha()">
            @foreach($usahas as $usahaItem)
            <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
            @endforeach
        </select>
        @endif
    </div>

    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <form action="{{ route('admin.receipts.store', ['usaha_id' => $currentUsaha?->id]) }}" method="POST" id="receiptForm">
            @csrf
            <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
            <div class="p-6 space-y-6">
                @if(session('error'))
                    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <div>
                            @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                        </div>
                    </div>
                @endif

                @if(!$currentUsaha)
                    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>Pilih usaha terlebih dahulu</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">No Receipt <span class="text-[#E11D48]">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" name="nomor_receipt" id="nomor_receipt" value="{{ old('nomor_receipt') }}" class="flex-1 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required {{ !$currentUsaha ? 'disabled' : '' }}>
                            <button type="button" id="generateBtn" class="px-4 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] text-[13px] font-semibold rounded-xl transition-colors border border-[#EEF2F6]" {{ !$currentUsaha ? 'disabled' : '' }}>
                                Generate
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Transaksi <span class="text-[#E11D48]">*</span></label>
                        <select id="transaksi_id" name="transaksi_id" class="w-full appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer" required {{ !$currentUsaha ? 'disabled' : '' }}>
                            <option value="">Pilih Transaksi</option>
                            @foreach($transaksis as $transaksi)
                                <option value="{{ $transaksi->id }}" data-jumlah="{{ $transaksi->jumlah }}">
                                    @if($transaksi->pelanggan) {{ $transaksi->pelanggan->nama }} @elseif($transaksi->supplier) {{ $transaksi->supplier->nama }} @else Umum @endif - Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Total</label>
                        <div class="flex">
                            <span class="px-4 py-2.5 bg-[#F1F5F9] border border-[#EEF2F6] border-r-0 text-[#64748B] text-[13px] rounded-l-xl font-medium">Rp</span>
                            <input type="text" id="total_transaksi" class="flex-1 bg-[#F1F5F9] border border-[#EEF2F6] text-[#64748B] rounded-r-xl px-4 py-2.5 text-[13px] font-medium" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jumlah Dibayar <span class="text-[#E11D48]">*</span></label>
                        <div class="flex">
                            <span class="px-4 py-2.5 bg-[#F1F5F9] border border-[#EEF2F6] border-r-0 text-[#64748B] text-[13px] rounded-l-xl font-medium">Rp</span>
                            <input type="number" id="jumlah_dibayar" name="jumlah_dibayar" value="{{ old('jumlah_dibayar', 0) }}" class="flex-1 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-r-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" min="0" step="0.01" required {{ !$currentUsaha ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kembalian</label>
                        <div class="flex">
                            <span class="px-4 py-2.5 bg-[#F1F5F9] border border-[#EEF2F6] border-r-0 text-[#64748B] text-[13px] rounded-l-xl font-medium">Rp</span>
                            <input type="number" id="kembalian" name="kembalian" class="flex-1 bg-[#F1F5F9] border border-[#EEF2F6] text-[#64748B] rounded-r-xl px-4 py-2.5 text-[13px] font-medium" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Mesin Kasir</label>
                        <input type="text" name="mesin_kasir_id" value="{{ old('mesin_kasir_id') }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="KASIR-001" {{ !$currentUsaha ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC] flex justify-end gap-3">
                <a href="{{ route('admin.receipts.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]" {{ !$currentUsaha ? 'disabled' : '' }}>
                    Simpan Receipt
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.receipts.create") }}?usaha_id=' + usahaId;
}

document.getElementById('generateBtn').addEventListener('click', function() {
    const currentUsahaId = {{ $currentUsaha?->id ?? 'null' }};
    if (!currentUsahaId) return;

    fetch('/receipts/generate-nomor?usaha_id=' + currentUsahaId).then(r => r.json()).then(d => {
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
    const total = parseFloat(opt?.dataset?.jumlah) || 0;
    const dibayar = parseFloat(document.getElementById('jumlah_dibayar').value) || 0;
    const kembalian = Math.max(0, dibayar - total);
    document.getElementById('kembalian').value = kembalian.toFixed(2);
}

if (!document.getElementById('nomor_receipt').value && {{ $currentUsaha?->id ? 'true' : 'false' }}) {
    document.getElementById('generateBtn').click();
}
</script>
@endsection
