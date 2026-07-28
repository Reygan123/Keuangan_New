@extends('layouts.admin.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Edit Produk: {{ $product->nama }}</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Perbarui informasi produk di bawah ini</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-[#64748B] hover:text-[#0F172A] transition-colors text-[13px] font-medium bg-white px-4 py-2 rounded-xl border border-[#EEF2F6] shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Usaha Selection -->
                    @if(isset($usahas) && count($usahas) > 1)
                    <div class="md:col-span-2">
                        <label for="usaha_id" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                        <select name="usaha_id" id="usaha_id" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer @error('usaha_id') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                            <option value="">-- Pilih Usaha --</option>
                            @foreach ($usahas as $usaha)
                                <option value="{{ $usaha->id }}" {{ old('usaha_id', $product->usaha_id) == $usaha->id ? 'selected' : '' }}>
                                    {{ $usaha->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('usaha_id')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @elseif(isset($usahas) && count($usahas) > 0)
                    <input type="hidden" name="usaha_id" id="usaha_id" value="{{ $usahas->first()->id ?? '' }}">
                    @endif

                    <!-- Nama Produk -->
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Produk <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $product->nama) }}" required placeholder="Contoh: Kopi Susu"
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all @error('nama') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                        @error('nama')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori HPP -->
                    <div>
                        <label for="kategori_hpp_id" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kategori HPP <span class="text-[#E11D48]">*</span></label>
                        <select name="kategori_hpp_id" id="kategori_hpp_id" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer @error('kategori_hpp_id') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @if(isset($kategoriHpps))
                            @foreach ($kategoriHpps as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_hpp_id', $product->kategori_hpp_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('kategori_hpp_id')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan Unit -->
                    <div>
                        <label for="satuan_unit" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Satuan Unit <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="satuan_unit" id="satuan_unit" value="{{ old('satuan_unit', $product->satuan_unit) }}" required placeholder="Contoh: Pcs, Kg, Box"
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all @error('satuan_unit') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                        @error('satuan_unit')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Akun Pendapatan -->
                    <div>
                        <label for="akun_pendapatan_id" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Pendapatan <span class="text-[#E11D48]">*</span></label>
                        <select name="akun_pendapatan_id" id="akun_pendapatan_id" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer @error('akun_pendapatan_id') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                            <option value="">-- Pilih Akun --</option>
                            @if(isset($akunPendapatan))
                            @foreach ($akunPendapatan as $akun)
                                <option value="{{ $akun->id }}" {{ old('akun_pendapatan_id', $product->akun_pendapatan_id) == $akun->id ? 'selected' : '' }}>
                                    {{ $akun->name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('akun_pendapatan_id')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Akun HPP -->
                    <div>
                        <label for="akun_hpp_id" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Beban Pokok <span class="text-[#E11D48]">*</span></label>
                        <select name="akun_hpp_id" id="akun_hpp_id" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer @error('akun_hpp_id') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                            <option value="">-- Pilih Akun --</option>
                            @if(isset($akunHpps))
                            @foreach ($akunHpps as $akun)
                                <option value="{{ $akun->id }}" {{ old('akun_hpp_id', $product->akun_hpp_id) == $akun->id ? 'selected' : '' }}>
                                    {{ $akun->name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('akun_hpp_id')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Akun Persediaan -->
                    <div class="md:col-span-2">
                        <label for="akun_persediaan_id" class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Persediaan <span class="text-[#E11D48]">*</span></label>
                        <select name="akun_persediaan_id" id="akun_persediaan_id" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer @error('akun_persediaan_id') border-[#F43F5E] focus:border-[#F43F5E] focus:ring-[#FFE4E6] @enderror">
                            <option value="">-- Pilih Akun --</option>
                            @if(isset($akunPersediaan))
                            @foreach ($akunPersediaan as $akun)
                                <option value="{{ $akun->id }}" {{ old('akun_persediaan_id', $product->akun_persediaan_id) == $akun->id ? 'selected' : '' }}>
                                    {{ $akun->name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('akun_persediaan_id')
                            <p class="text-[#F43F5E] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-[#EEF2F6] flex justify-end gap-3 mt-6">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const usahaSelect = document.getElementById('usaha_id');
    const kategoriSelect = document.getElementById('kategori_hpp_id');

    if (usahaSelect) {
        usahaSelect.addEventListener('change', function() {
            const usahaId = this.value;

            if (!usahaId) {
                kategoriSelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                return;
            }

            fetch(`/admin/kategori-hpp-by-usaha-product?usaha_id=${usahaId}`)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">-- Pilih Kategori --</option>';

                    data.forEach(kategori => {
                        const selected = kategori.id == '{{ old("kategori_hpp_id", $product->kategori_hpp_id) }}' ? 'selected' : '';
                        options += `<option value="${kategori.id}" ${selected}>${kategori.name}</option>`;
                    });

                    kategoriSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Trigger on load to fetch categories if usaha is pre-selected, unless we want to keep current selections intact from server side
        // But since we are showing the edit form, the server already populated the options in the loop above.
        // So we don't strictly need to trigger it on load, otherwise it might overwrite if the network call is slow and reset user's state.
        // However, if the user changes it, the event listener will fire.
    }
});
</script>
@endsection
