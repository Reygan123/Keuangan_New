@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Buat Kuitansi Baru</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Buat kuitansi untuk transaksi</p>
        </div>
        @if($usahas->count() > 1)
        <select id="usahaSelect" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]" onchange="changeUsaha()">
            @foreach($usahas as $usahaItem)
            <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
            @endforeach
        </select>
        @endif
    </div>

    @if(!$currentUsaha)
        <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium mb-6">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>Pilih usaha terlebih dahulu</span>
        </div>
    @endif

    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <form action="{{ route('admin.kuitansi.store', ['usaha_id' => $currentUsaha?->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
            
            <div class="p-6 space-y-6">
                @if($errors->any())
                    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <div>
                            @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Transaksi <span class="text-[#E11D48]">*</span></label>
                        <select name="transaksi_id" class="w-full appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer" required {{ !$currentUsaha ? 'disabled' : '' }}>
                            <option value="">Pilih Transaksi</option>
                            @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}">{{ $transaksi->keterangan }} - {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nomor Kuitansi <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nomor_kuitansi" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required {{ !$currentUsaha ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Pembayaran <span class="text-[#E11D48]">*</span></label>
                        <input type="date" name="tanggal_pembayaran" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required {{ !$currentUsaha ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Metode Pembayaran <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="metode_pembayaran" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required {{ !$currentUsaha ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jumlah Dibayar <span class="text-[#E11D48]">*</span></label>
                        <div class="flex">
                            <span class="px-4 py-2.5 bg-[#F1F5F9] border border-[#EEF2F6] border-r-0 text-[#64748B] text-[13px] rounded-l-xl font-medium">Rp</span>
                            <input type="number" name="jumlah_dibayar" class="flex-1 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-r-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" min="0" step="0.01" required {{ !$currentUsaha ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanda Tangan Penerima <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="tanda_tangan_penerima" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required {{ !$currentUsaha ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC] flex justify-end gap-3">
                <a href="{{ route('admin.kuitansi.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]" {{ !$currentUsaha ? 'disabled' : '' }}>
                    Simpan Kuitansi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.kuitansi.create") }}?usaha_id=' + usahaId;
}
</script>
@endsection
