@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Edit Kuitansi</h1>
            <p class="text-[14px] text-[#64748B] mt-1">{{ $kuitansi->nomor_kuitansi }}</p>
        </div>
        @if($usahas->count() > 1)
        <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-xl px-4 py-2.5 flex items-center gap-2">
            <span class="text-[13px] text-[#64748B] font-medium">Usaha:</span>
            <span class="text-[13px] text-[#0F172A] font-bold">{{ $currentUsaha->nama }}</span>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <form action="{{ route('admin.kuitansi.update', $kuitansi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
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
                        <select name="transaksi_id" class="w-full appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer" required>
                            @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}" {{ $kuitansi->transaksi_id == $transaksi->id ? 'selected' : '' }}>{{ $transaksi->keterangan }} - {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nomor Kuitansi <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nomor_kuitansi" value="{{ $kuitansi->nomor_kuitansi }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Pembayaran <span class="text-[#E11D48]">*</span></label>
                        <input type="date" name="tanggal_pembayaran" value="{{ $kuitansi->tanggal_pembayaran }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Metode Pembayaran <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="metode_pembayaran" value="{{ $kuitansi->metode_pembayaran }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jumlah Dibayar <span class="text-[#E11D48]">*</span></label>
                        <div class="flex">
                            <span class="px-4 py-2.5 bg-[#F1F5F9] border border-[#EEF2F6] border-r-0 text-[#64748B] text-[13px] rounded-l-xl font-medium">Rp</span>
                            <input type="number" name="jumlah_dibayar" value="{{ $kuitansi->jumlah_dibayar }}" class="flex-1 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-r-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanda Tangan Penerima <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="tanda_tangan_penerima" value="{{ $kuitansi->tanda_tangan_penerima }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC] flex justify-end gap-3 flex-wrap">
                <a href="{{ route('admin.kuitansi.index', ['usaha_id' => $currentUsaha->id]) }}" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                    Batal
                </a>
                <a href="{{ route('admin.kuitansi.show', $kuitansi->id) }}" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#2563EB] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                    Lihat Detail
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                    Update Kuitansi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
