@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Biaya HPP Tambahan</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola daftar biaya HPP tambahan Anda</p>
        </div>
        <a href="{{ route('admin.kategori_hpp_tambahan.create') }}" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Biaya</span>
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <form action="{{ route('admin.kategori_hpp_tambahan.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                @if(isset($usahas) && count($usahas) > 1)
                <select name="usaha_id" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">Semua Usaha</option>
                    @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                @endif
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama biaya..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white rounded-xl text-[13px] font-semibold transition-colors duration-200">
                    Cari
                </button>
                <a href="{{ route('admin.kategori_hpp_tambahan.index') }}" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] rounded-xl text-[13px] font-semibold transition-colors duration-200 border border-[#EEF2F6] flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Nama Biaya</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Kategori HPP</th>
                        <th class="hidden md:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Biaya/Unit</th>
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Akun Biaya</th>
                        @if(isset($usahas) && count($usahas) > 1)
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @forelse ($kategoriHppTambahans as $item)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0F172A] text-[13px]">{{ $item->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[13px] font-medium text-[#64748B]">{{ $item->kategoriHpp->name ?? 'N/A' }}</div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B] text-right">
                                Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $item->akunBiaya->name ?? 'N/A' }}
                            </td>
                            @if(isset($usahas) && count($usahas) > 1)
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $item->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.kategori_hpp_tambahan.edit', $item) }}" class="px-3 py-1.5 bg-[#F8FAFC] hover:bg-[#EEF2FF] text-[#3B41E3] font-semibold rounded-lg transition-colors text-[12px] border border-[#EEF2F6] hover:border-[#C7D2FE]">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kategori_hpp_tambahan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus biaya ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-lg transition-colors text-[12px] border border-[#FECDD3]">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (isset($usahas) && count($usahas) > 1) ? 6 : 5 }}" class="px-6 py-8 text-center text-[13px] text-[#94A3B8]">Belum ada Biaya HPP Tambahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
