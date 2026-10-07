@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    
    <!-- 1. Apple-Style Header & Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-rose-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Rekapitulasi Pajak</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-semibold text-[11px] ring-1 ring-rose-500/10">
                            Masa Pajak {{ \Carbon\Carbon::parse($bulan . '-01')->format('F Y') }}
                        </span>
                        @php
                            $currentUsaha = $usahas->firstWhere('id', $usahaSelected);
                        @endphp
                        @if($currentUsaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $currentUsaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form action="{{ route('admin.laporan.pajak') }}" method="GET" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition active:scale-[0.98] cursor-pointer">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            @endif

            <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition cursor-pointer">

            <div class="relative">
                <select name="jenis_pajak_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition active:scale-[0.98] cursor-pointer">
                    <option value="">Semua Pajak</option>
                    @foreach($jenisPajaks as $jp)
                    <option value="{{ $jp->id }}" {{ request('jenis_pajak_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition active:scale-[0.98] cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="BELUM_DISETOR" {{ request('status') == 'BELUM_DISETOR' ? 'selected' : '' }}>Belum Disetor</option>
                    <option value="SUDAH_DISETOR" {{ request('status') == 'SUDAH_DISETOR' ? 'selected' : '' }}>Sudah Disetor</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filter</span>
            </button>

            <a href="{{ route('admin.laporan.pajak.export', ['usaha_id' => $usahaSelected, 'bulan' => $bulan]) }}"
                class="bg-white border border-slate-200/80 text-slate-700 hover:text-slate-900 hover:bg-slate-50 active:scale-95 text-xs font-semibold px-3 py-2 rounded-xl transition shadow-2xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Ekspor CSV</span>
            </a>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-semibold rounded-2xl p-4 flex justify-between items-center shadow-xs">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="text-emerald-600 hover:text-emerald-900 font-bold p-1" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <!-- 2. Ringkasan Eksekutif (3 Kartu KPI Apple Widgets) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Pajak Periode Ini -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pajak Terutang</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2v14a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Masa {{ \Carbon\Carbon::parse($bulan . '-01')->format('M Y') }}</span>
                <span class="text-slate-400">Total Akumulasi</span>
            </div>
        </div>

        <!-- Belum Disetor -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Belum Disetor (Kewajiban)</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $totalBelumDisetor > 0 ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-500/15' : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' }}">
                    {{ $totalBelumDisetor > 0 ? 'Perlu Disetor' : 'Lunas' }}
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold {{ $totalBelumDisetor > 0 ? 'text-amber-600' : 'text-slate-900' }} tracking-tight leading-none mt-1">
                Rp {{ number_format($totalBelumDisetor, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>SPT Masa Pending</span>
                <span class="font-semibold text-amber-600">Jatuh Tempo</span>
            </div>
        </div>

        <!-- Sudah Disetor -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sudah Disetor (Kas Negara)</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-emerald-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-emerald-600 tracking-tight leading-none mt-1">
                Rp {{ number_format($totalKeseluruhan - $totalBelumDisetor, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>NTPN / BPN Valid</span>
                <span class="font-semibold text-emerald-600">Selesai</span>
            </div>
        </div>
    </div>

    <!-- 3. Ringkasan Per Jenis Pajak -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 tracking-tight">Ringkasan per Jenis Pajak</h3>
                <p class="text-xs text-slate-500 mt-0.5">Rincian kewajiban PPh 21, PPh 23, PPh Final, dan PPN</p>
            </div>
            <span class="text-[11px] font-semibold text-slate-500 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                {{ count($rekapPerJenis) }} Jenis Pajak
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/80 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                        <th class="px-6 py-3.5">Jenis Pajak</th>
                        <th class="px-6 py-3.5 text-right">Total Kewajiban (Rp)</th>
                        <th class="px-6 py-3.5 text-right">Belum Disetor (Rp)</th>
                        <th class="px-6 py-3.5 text-right">Sudah Disetor (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($rekapPerJenis as $r)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>{{ $r->jenisPajak->nama ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ number_format($r->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-3.5 text-right font-medium font-mono {{ $r->belum_disetor > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                            {{ number_format($r->belum_disetor, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right text-emerald-600 font-semibold font-mono">
                            {{ number_format($r->total - $r->belum_disetor, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-xs text-slate-400">Tidak ada data pajak pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. Detail Transaksi Pajak & Setor Massal -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <form id="formSetorMassal" action="{{ route('admin.laporan.pajak.setor-massal') }}" method="POST">
            @csrf
            <input type="hidden" name="usaha_id" value="{{ $usahaSelected }}">

            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 tracking-tight">Detail Transaksi Pajak</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih transaksi yang belum disetor lalu tandai tanggal setor</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" name="tanggal_setor" required value="{{ date('Y-m-d') }}" class="bg-white border border-slate-200 text-slate-800 text-xs font-semibold px-3 py-1.5 rounded-xl shadow-2xs focus:outline-none focus:border-emerald-500">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs font-semibold px-3.5 py-1.5 rounded-xl shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Tandai Disetor</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/80 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                            <th class="px-5 py-3 w-10 text-center">
                                <input type="checkbox" onclick="toggleAll(this)" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Jenis Pajak</th>
                            <th class="px-5 py-3 text-right">DPP (IDR)</th>
                            <th class="px-5 py-3 text-right">Tarif</th>
                            <th class="px-5 py-3 text-right">Jumlah Pajak</th>
                            <th class="px-5 py-3 text-center">Status Setor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($transaksiPajaks as $tp)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3 text-center">
                                @if($tp->status === 'BELUM_DISETOR')
                                <input type="checkbox" name="ids[]" value="{{ $tp->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                @else
                                <span class="text-slate-300">&bull;</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-500 font-mono text-[11px]">{{ $tp->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-slate-800 font-medium">{{ $tp->jenisPajak->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-right text-slate-700 font-mono">{{ number_format($tp->dasar_pengenaan_pajak, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-slate-700 font-mono">{{ $tp->tarif_persen }}%</td>
                            <td class="px-5 py-3 text-right text-slate-900 font-bold font-mono">{{ number_format($tp->jumlah_pajak, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($tp->status === 'SUDAH_DISETOR')
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/20 text-[11px] px-2.5 py-0.5 rounded-full font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Disetor {{ $tp->tanggal_setor?->format('d/m/Y') }}
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 ring-1 ring-amber-500/20 text-[11px] px-2.5 py-0.5 rounded-full font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Belum Disetor
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-xs text-slate-400">Tidak ada transaksi pajak pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($transaksiPajaks->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $transaksiPajaks->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function toggleAll(source) {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = source.checked);
}
</script>
@endsection
