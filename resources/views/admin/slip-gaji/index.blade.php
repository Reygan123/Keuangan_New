@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Slip Gaji</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Daftar slip gaji yang sudah dibuat.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <form action="{{ route('admin.slip-gaji.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                <select name="usaha_id" onchange="this.form.submit()"
                    class="w-full sm:w-auto bg-white border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] transition-all cursor-pointer shadow-[0_2px_8px_rgba(15,23,42,0.02)]">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
                <input type="month" name="periode" value="{{ request('periode') }}" onchange="this.form.submit()"
                    class="w-full sm:w-auto bg-white border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] transition-all cursor-pointer shadow-[0_2px_8px_rgba(15,23,42,0.02)]">
            </form>
            <a href="{{ route('admin.slip-gaji.create', ['usaha_id' => $usahaSelected]) }}"
                class="w-full sm:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2.5 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px] whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Slip Gaji</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white border border-[#EEF2F6] rounded-[20px] overflow-hidden shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Nomor Slip</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Take Home Pay</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @forelse($slipGajis as $s)
                    <tr class="hover:bg-[#F8FAFC]/50 transition-colors">
                        <td class="px-6 py-4 text-[13px] font-mono text-[#334155]">{{ $s->nomor_slip }}</td>
                        <td class="px-6 py-4 text-[13px] font-semibold text-[#0F172A]">{{ $s->karyawan->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B]">{{ \Carbon\Carbon::parse($s->periode)->translatedFormat('F Y') }}</td>
                        <td class="px-6 py-4 text-[13px] font-bold text-[#10B981] text-right">
                            Rp {{ number_format($s->take_home_pay, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.slip-gaji.preview-pdf', $s->id) }}" target="_blank" 
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#F8FAFC] text-[#3B41E3] hover:bg-[#EEF2FF] transition-colors border border-[#EEF2F6] hover:border-[#C7D2FE]" title="Preview">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.slip-gaji.download-pdf', $s->id) }}" 
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#ECFDF5] text-[#10B981] hover:bg-[#D1FAE5] transition-colors border border-[#A7F3D0] hover:border-[#6EE7B7]" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                                <a href="{{ route('admin.slip-gaji.edit', $s->id) }}" 
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#FFFBEB] text-[#D97706] hover:bg-[#FEF3C7] transition-colors border border-[#FDE68A] hover:border-[#FCD34D]" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.slip-gaji.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus slip gaji ini?')" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#FEF2F2] text-[#E11D48] hover:bg-[#FEE2E2] transition-colors border border-[#FECDD3]" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-[#F8FAFC] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-[#EEF2F6]">
                                <svg class="w-8 h-8 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-[16px] font-bold text-[#0F172A] mb-1">Belum Ada Slip Gaji</h3>
                            <p class="text-[13px] text-[#64748B]">Klik "Buat Slip Gaji" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($slipGajis->hasPages())
        <div class="px-6 py-4 border-t border-[#EEF2F6]">
            {{ $slipGajis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
