@extends('layouts.admin.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div>
        <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Buat Slip Gaji</h1>
        <p class="text-[14px] text-[#64748B] mt-1">Isi rincian pendapatan dan potongan karyawan.</p>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] text-[13px] font-medium">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.slip-gaji.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="usaha_id" value="{{ $usahaSelected }}">

        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
            <h2 class="text-[16px] font-bold text-[#0F172A] mb-5 border-b border-[#EEF2F6] pb-3">Informasi Dasar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Karyawan <span class="text-[#E11D48]">*</span></label>
                    <select name="karyawan_id" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawans as $k)
                        <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->posisi }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Periode <span class="text-[#E11D48]">*</span></label>
                    <input type="month" name="periode" required value="{{ old('periode', now()->format('Y-m')) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
            <h2 class="text-[16px] font-bold text-[#0F172A] mb-5 border-b border-[#EEF2F6] pb-3 text-[#10B981]">Pendapatan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Gaji Pokok <span class="text-[#E11D48]">*</span></label>
                    <input type="number" step="0.01" name="gaji_pokok" required value="{{ old('gaji_pokok', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tunjangan Jabatan</label>
                    <input type="number" step="0.01" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tunjangan Transport</label>
                    <input type="number" step="0.01" name="tunjangan_transport" value="{{ old('tunjangan_transport', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tunjangan Makan</label>
                    <input type="number" step="0.01" name="tunjangan_makan" value="{{ old('tunjangan_makan', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tunjangan Komunikasi</label>
                    <input type="number" step="0.01" name="tunjangan_komunikasi" value="{{ old('tunjangan_komunikasi', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tunjangan Lainnya</label>
                    <input type="number" step="0.01" name="tunjangan_lainnya" value="{{ old('tunjangan_lainnya', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Lembur</label>
                    <input type="number" step="0.01" name="lembur" value="{{ old('lembur', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Bonus</label>
                    <input type="number" step="0.01" name="bonus" value="{{ old('bonus', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Insentif</label>
                    <input type="number" step="0.01" name="insentif" value="{{ old('insentif', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
            <h2 class="text-[16px] font-bold text-[#0F172A] mb-5 border-b border-[#EEF2F6] pb-3 text-[#F43F5E]">Potongan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">PPh 21</label>
                    <input type="number" step="0.01" name="pph21" value="{{ old('pph21', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">BPJS Kesehatan</label>
                    <input type="number" step="0.01" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">BPJS JHT</label>
                    <input type="number" step="0.01" name="bpjs_jht" value="{{ old('bpjs_jht', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">BPJS JP</label>
                    <input type="number" step="0.01" name="bpjs_jp" value="{{ old('bpjs_jp', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Potongan Lainnya</label>
                    <input type="number" step="0.01" name="potongan_lainnya" value="{{ old('potongan_lainnya', 0) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Keterangan Potongan Lainnya</label>
                    <input type="text" name="keterangan_potongan_lainnya" value="{{ old('keterangan_potongan_lainnya') }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Misal: Cicilan Kasbon">
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
            <h2 class="text-[16px] font-bold text-[#0F172A] mb-5 border-b border-[#EEF2F6] pb-3">Tanda Tangan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tempat</label>
                    <input type="text" name="tempat_ttd" value="{{ old('tempat_ttd') }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Misal: Jakarta">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal_ttd" value="{{ old('tanggal_ttd', now()->format('Y-m-d')) }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Penandatangan</label>
                    <input type="text" name="nama_penandatangan" value="{{ old('nama_penandatangan') }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Misal: John Doe">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jabatan Penandatangan</label>
                    <input type="text" name="jabatan_penandatangan" value="{{ old('jabatan_penandatangan') }}"
                        class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Misal: HR Manager">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-[#EEF2F6]">
            <a href="{{ route('admin.slip-gaji.index') }}" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px] shadow-sm">Simpan Slip Gaji</button>
        </div>
    </form>
</div>
@endsection
