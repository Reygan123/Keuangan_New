@extends('layouts.admin.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Edit Kategori HPP</h1>
            <p class="text-[14px] text-[#64748B] mt-1">{{ $kategoriHpp->name }}</p>
        </div>
        <a href="{{ route('admin.kategori_hpps.index') }}" class="flex items-center gap-2 text-[#64748B] hover:text-[#0F172A] transition-colors text-[13px] font-medium bg-white px-4 py-2 rounded-xl border border-[#EEF2F6] shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="{{ route('admin.kategori_hpps.update', $kategoriHpp) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(isset($usahas) && count($usahas) > 1)
                    <div class="md:col-span-2">
                        <label for="usaha_id" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                        <select name="usaha_id" id="usaha_id" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer @error('usaha_id') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                            <option value="">-- Pilih Usaha --</option>
                            @foreach ($usahas as $usaha)
                                <option value="{{ $usaha->id }}" {{ old('usaha_id', $kategoriHpp->usaha_id) == $usaha->id ? 'selected' : '' }}>
                                    {{ $usaha->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('usaha_id')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @elseif(isset($usahas) && count($usahas) > 0)
                    <input type="hidden" name="usaha_id" value="{{ $usahas->first()->id ?? $kategoriHpp->usaha_id }}">
                    @endif

                    <div class="md:col-span-2">
                        <label for="name" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kategori HPP <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $kategoriHpp->name) }}" required placeholder="Contoh: Makanan Berat"
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all @error('name') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                        @error('name')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="kategori" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kategori <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $kategoriHpp->kategori) }}" required placeholder="Contoh: FOOD_MAIN"
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all @error('kategori') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                        <p class="text-[#64748B] text-[12px] mt-1.5">Digunakan untuk pengelompokan internal.</p>
                        @error('kategori')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-[#EEF2F6] flex justify-end gap-3 mt-6">
                    <a href="{{ route('admin.kategori_hpps.index') }}" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
