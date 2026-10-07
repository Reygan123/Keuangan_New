@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">

    <!-- 1. Apple-Style Header & Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-purple-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Laporan Perubahan Ekuitas (V2)</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 font-semibold text-[11px] ring-1 ring-purple-500/10">
                            {{ \Carbon\Carbon::parse($bulan_awal)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($bulan_akhir)->translatedFormat('d M Y') }}
                        </span>
                        @if($usaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $usaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <form action="{{ route('admin.laporan.perubahan_ekuitas_v2') }}" method="GET" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition active:scale-[0.98] cursor-pointer">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ (string) $usahaSelected === (string) $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            @endif

            <input type="date" name="start_date" value="{{ $bulan_awal }}" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-medium px-2.5 py-2 rounded-xl shadow-2xs focus:outline-none focus:border-purple-500">
            <span class="text-xs text-slate-400">-</span>
            <input type="date" name="end_date" value="{{ $bulan_akhir }}" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-medium px-2.5 py-2 rounded-xl shadow-2xs focus:outline-none focus:border-purple-500">

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filter</span>
            </button>

            <button type="button" onclick="window.print()" class="bg-white border border-slate-200/80 text-slate-700 hover:text-slate-900 hover:bg-slate-50 active:scale-95 text-xs font-semibold px-3 py-2 rounded-xl transition shadow-2xs flex items-center gap-1.5" title="Cetak Laporan">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span class="hidden sm:inline">Cetak</span>
            </button>
        </form>
    </div>

    <!-- 2. Ringkasan Eksekutif (3 Kartu KPI Apple Widgets) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Modal Awal (Hasil 1) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modal Awal (Hasil 1)</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($hasil1, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Modal Mitra (CTO + CEO)</span>
                <span class="text-slate-400">Saldo Awal</span>
            </div>
        </div>

        <!-- Penambahan Periode (Hasil 2) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Penambahan Periode (Hasil 2)</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $hasil2 >= 0 ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-500/15' }}">
                    {{ $hasil2 >= 0 ? '+ Penambahan' : '- Pengurangan' }}
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold {{ $hasil2 >= 0 ? 'text-emerald-600' : 'text-rose-600' }} tracking-tight leading-none mt-1">
                Rp {{ number_format($hasil2, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Setoran + Laba Ditahan + Laba Berjalan</span>
                <span class="font-semibold {{ $hasil2 >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $hasil2 >= 0 ? 'Surplus' : 'Defisit' }}</span>
            </div>
        </div>

        <!-- Modal Akhir -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modal Akhir</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-purple-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-indigo-700 tracking-tight leading-none mt-1">
                Rp {{ number_format($modalAkhir, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Per {{ \Carbon\Carbon::parse($bulan_akhir)->translatedFormat('d M Y') }}</span>
                <span class="font-semibold text-indigo-600">Ekuitas Bersih</span>
            </div>
        </div>
    </div>

    <!-- 3. Apple Document Sheet: Rincian Perubahan Ekuitas -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <!-- Document Header (Kop Laporan) -->
        <div class="px-6 py-6 border-b border-slate-100 bg-slate-50/50 text-center">
            <span class="text-[11px] font-bold tracking-widest text-indigo-600 uppercase">Laporan Keuangan Resmi</span>
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $usaha->nama ?? '-' }}</h2>
            <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mt-0.5">Rincian Perubahan Ekuitas</p>
            <p class="text-[11px] text-slate-400 font-medium mt-1">
                Periode {{ \Carbon\Carbon::parse($bulan_awal)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($bulan_akhir)->translatedFormat('d F Y') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <tbody class="divide-y divide-slate-100">
                    {{-- SECTION 1: MODAL AWAL --}}
                    <tr class="bg-slate-50/70">
                        <td colspan="2" class="px-6 py-2.5">
                            <span class="text-slate-700 font-bold text-xs uppercase tracking-wider">I. Modal Awal</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">1. Modal Awal CTO</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono font-medium">{{ number_format($modalAwalCTO, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">2. Modal Awal CEO</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono font-medium">{{ number_format($modalAwalCEO, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-slate-50/90 border-y border-slate-200 font-semibold">
                        <td class="px-6 py-3">
                            <span class="text-slate-900">Hasil 1</span>
                            <span class="text-slate-400 text-xs ml-1">(1 + 2)</span>
                        </td>
                        <td class="px-6 py-3 text-right font-mono font-bold text-slate-900">
                            {{ number_format($hasil1, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- SECTION 2: SETORAN & LABA --}}
                    <tr class="bg-slate-50/70">
                        <td colspan="2" class="px-6 py-2.5">
                            <span class="text-slate-700 font-bold text-xs uppercase tracking-wider">II. Setoran Tambahan Modal &amp; Laba Ditahan</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">3. Setoran Tambahan Modal CEO</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono font-medium">{{ number_format($setoranCEO, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">4. Setoran Tambahan Modal CTO</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono font-medium">{{ number_format($setoranCTO, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">5. Laba Ditahan</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono font-medium">{{ number_format($labaDitahan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">6. Laba/Rugi Tahun Berjalan</td>
                        <td class="px-6 py-3 text-right font-mono font-medium {{ $labaTahunBerjalan >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ number_format($labaTahunBerjalan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-slate-50/90 border-y border-slate-200 font-semibold">
                        <td class="px-6 py-3">
                            <span class="text-slate-900">Hasil 2</span>
                            <span class="text-slate-400 text-xs ml-1">(3 + 4 + 5 + 6)</span>
                        </td>
                        <td class="px-6 py-3 text-right font-mono font-bold text-emerald-700">
                            {{ number_format($hasil2, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- SECTION 3: DIVIDEN & PRIVE --}}
                    <tr class="bg-slate-50/70">
                        <td colspan="2" class="px-6 py-2.5">
                            <span class="text-slate-700 font-bold text-xs uppercase tracking-wider">III. Dividen &amp; Prive</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">7. Dividen</td>
                        <td class="px-6 py-3 text-right text-rose-600 font-mono">({{ number_format($dividen, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">8. Prive CEO</td>
                        <td class="px-6 py-3 text-right text-rose-600 font-mono">({{ number_format($priveCEO, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3 pl-10 text-slate-700">9. Prive CTO</td>
                        <td class="px-6 py-3 text-right text-rose-600 font-mono">({{ number_format($priveCTO, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="bg-slate-50/90 border-y border-slate-200 font-semibold">
                        <td class="px-6 py-3">
                            <span class="text-slate-900">Hasil 3</span>
                            <span class="text-slate-400 text-xs ml-1">(7 + 8 + 9)</span>
                        </td>
                        <td class="px-6 py-3 text-right font-mono font-bold text-rose-600">
                            ({{ number_format($hasil3, 0, ',', '.') }})
                        </td>
                    </tr>

                    {{-- TOTAL & MODAL AKHIR --}}
                    <tr class="bg-slate-100/60 font-semibold">
                        <td class="px-6 py-3.5">
                            <span class="text-slate-900">Total Akumulasi</span>
                            <span class="text-slate-400 text-xs ml-1">(Hasil 1 + Hasil 2)</span>
                        </td>
                        <td class="px-6 py-3.5 text-right font-mono font-bold text-slate-900">
                            {{ number_format($total, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-gradient-to-r from-purple-50/50 via-slate-50 to-purple-50/30 border-t-2 border-slate-300">
                        <td class="px-6 py-5">
                            <span class="text-slate-900 font-bold text-base">Modal Akhir</span>
                            <span class="text-slate-400 text-xs ml-1">(Total − Hasil 3)</span>
                        </td>
                        <td class="px-6 py-5 text-right font-mono font-bold text-lg text-indigo-700">
                            {{ number_format($modalAkhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footnote -->
    <div class="flex items-start gap-2 text-xs text-slate-400 px-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p>
            Modal Awal &amp; Laba Ditahan dihitung dari saldo sebelum tanggal mulai filter.
            Setoran, Dividen, dan Prive dihitung dari mutasi transaksi selama rentang tanggal yang dipilih.
        </p>
    </div>
</div>
@endsection