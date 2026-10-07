@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    
    <!-- 1. Apple-Style Header & Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-indigo-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Laporan Laba Rugi</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-[11px] ring-1 ring-indigo-500/10">
                            {{ \Carbon\Carbon::parse($start_date_str)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date_str)->format('d M Y') }}
                        </span>
                        @if($usaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $usaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form method="GET" action="{{ route('admin.laporan.laba_rugi') }}" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition active:scale-[0.98] cursor-pointer">
                    @foreach($usahas as $usahaItem)
                    <option value="{{ $usahaItem->id }}" {{ $usahaSelected == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            @endif

            <input type="date" name="start_date" value="{{ $start_date_str }}" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-medium px-2.5 py-2 rounded-xl shadow-2xs focus:outline-none focus:border-indigo-500">
            <span class="text-xs text-slate-400">-</span>
            <input type="date" name="end_date" value="{{ $end_date_str }}" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-medium px-2.5 py-2 rounded-xl shadow-2xs focus:outline-none focus:border-indigo-500">

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
        <!-- Penjualan Bersih -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Penjualan Bersih</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-emerald-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($totalPenjualanBersih, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Gross Margin</span>
                <span class="font-semibold text-slate-700">{{ $totalPenjualanBersih > 0 ? round(($labaKotor / $totalPenjualanBersih) * 100, 1) : 0 }}%</span>
            </div>
        </div>

        <!-- Total Beban -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Beban (HPP + Operasi)</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-rose-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($totalHpp + $totalBebanOperasi, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>HPP: Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                <span>Operasi: Rp {{ number_format($totalBebanOperasi, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Laba Bersih -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laba Bersih</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $labaBersih >= 0 ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-500/15' }}">
                    {{ $labaBersih >= 0 ? 'Surplus' : 'Defisit' }}
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold tracking-tight leading-none mt-1 {{ $labaBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                Rp {{ number_format($labaBersih, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Net Margin</span>
                <span class="font-semibold {{ $labaBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $totalPenjualanBersih > 0 ? round(($labaBersih / $totalPenjualanBersih) * 100, 1) : 0 }}%
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Dokumen Laporan Resmi (Apple Document Sheet) -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        
        <!-- Sheet Header -->
        <div class="p-6 border-b border-slate-200/60 text-center bg-slate-50/40">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ $usaha->nama }}</h2>
            <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Laporan Laba Rugi Komprehensif</p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Periode: {{ \Carbon\Carbon::parse($start_date_str)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($end_date_str)->format('d F Y') }}</p>
        </div>

        <!-- Statement Content -->
        <div class="divide-y divide-slate-100/90 text-xs">
            
            <!-- I. PENJUALAN BERSIH -->
            <div>
                <div class="px-6 py-2.5 bg-slate-50/70 border-b border-slate-100 flex justify-between items-center font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                    <span>I. Pendapatan & Penjualan Bersih</span>
                    <span class="text-slate-400">Nominal (IDR)</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($labaRugiItems['PENJUALAN_BERSIH'] as $pendapatan)
                    <div class="px-6 py-2.5 flex justify-between text-slate-700 hover:bg-slate-50/60 transition-colors">
                        <span class="pl-2">{{ $pendapatan['name'] }}</span>
                        <span class="font-medium text-slate-900">{{ number_format($pendapatan['mutasi_bersih'], 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="px-6 py-3 text-slate-400 italic pl-2">Tidak ada data pendapatan</div>
                    @endforelse
                </div>
                <div class="px-6 py-2.5 bg-slate-50/40 flex justify-between font-semibold text-slate-900 border-t border-slate-100">
                    <span>Total Penjualan Bersih</span>
                    <span>Rp {{ number_format($totalPenjualanBersih, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- II. HPP -->
            <div>
                <div class="px-6 py-2.5 bg-slate-50/70 border-b border-slate-100 flex justify-between items-center font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                    <span>II. Harga Pokok Penjualan (HPP)</span>
                    <span class="text-slate-400">Nominal (IDR)</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($labaRugiItems['HPP'] as $hpp)
                    <div class="px-6 py-2.5 flex justify-between text-slate-700 hover:bg-slate-50/60 transition-colors">
                        <span class="pl-2">{{ $hpp['name'] }}</span>
                        <span class="font-medium text-rose-600">({{ number_format($hpp['mutasi_bersih'], 0, ',', '.') }})</span>
                    </div>
                    @empty
                    <div class="px-6 py-3 text-slate-400 italic pl-2">Tidak ada data HPP</div>
                    @endforelse
                </div>
                <div class="px-6 py-2.5 bg-slate-50/40 flex justify-between font-semibold text-slate-900 border-t border-slate-100">
                    <span>Total HPP</span>
                    <span class="text-rose-600 font-bold">({{ number_format($totalHpp, 0, ',', '.') }})</span>
                </div>
            </div>

            <!-- LABA KOTOR HIGHLIGHT ROW -->
            <div class="px-6 py-3.5 bg-blue-50/60 flex justify-between items-center text-xs font-bold text-blue-900 border-y border-blue-100/80">
                <span class="uppercase tracking-wide">Laba Kotor (Gross Profit)</span>
                <span class="text-sm font-bold text-blue-700">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
            </div>

            <!-- III. BEBAN OPERASI -->
            <div>
                <div class="px-6 py-2.5 bg-slate-50/70 border-b border-slate-100 flex justify-between items-center font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                    <span>III. Beban Operasional</span>
                    <span class="text-slate-400">Nominal (IDR)</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($labaRugiItems['BEBAN_OPERASI'] as $beban)
                    <div class="px-6 py-2.5 flex justify-between text-slate-700 hover:bg-slate-50/60 transition-colors">
                        <span class="pl-2">{{ $beban['name'] }}</span>
                        <span class="font-medium text-rose-600">({{ number_format($beban['mutasi_bersih'], 0, ',', '.') }})</span>
                    </div>
                    @empty
                    <div class="px-6 py-3 text-slate-400 italic pl-2">Tidak ada data beban operasi</div>
                    @endforelse
                </div>
                <div class="px-6 py-2.5 bg-slate-50/40 flex justify-between font-semibold text-slate-900 border-t border-slate-100">
                    <span>Total Beban Operasional</span>
                    <span class="text-rose-600 font-bold">({{ number_format($totalBebanOperasi, 0, ',', '.') }})</span>
                </div>
            </div>

            <!-- LABA OPERASI HIGHLIGHT ROW -->
            <div class="px-6 py-3.5 bg-indigo-50/60 flex justify-between items-center text-xs font-bold text-indigo-900 border-y border-indigo-100/80">
                <span class="uppercase tracking-wide">Laba Operasi (Operating Profit)</span>
                <span class="text-sm font-bold text-indigo-700">Rp {{ number_format($labaOperasi, 0, ',', '.') }}</span>
            </div>

            <!-- IV. PENDAPATAN & BEBAN LAIN -->
            <div>
                <div class="px-6 py-2.5 bg-slate-50/70 border-b border-slate-100 flex justify-between items-center font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                    <span>IV. Pendapatan & Beban Lain-Lain</span>
                    <span class="text-slate-400">Nominal (IDR)</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($labaRugiItems['PENDAPATAN_BEBAN_LAIN'] as $item)
                    <div class="px-6 py-2.5 flex justify-between text-slate-700 hover:bg-slate-50/60 transition-colors">
                        <span class="pl-2">{{ $item['name'] }}</span>
                        <span class="{{ $item['klasifikasi'] == 'PENDAPATAN' ? 'text-emerald-600' : 'text-rose-600' }} font-medium">
                            {{ $item['klasifikasi'] == 'PENDAPATAN' ? '+' : '-' }} {{ number_format($item['mutasi_bersih'], 0, ',', '.') }}
                        </span>
                    </div>
                    @empty
                    <div class="px-6 py-3 text-slate-400 italic pl-2">Tidak ada pendapatan/beban non-operasional</div>
                    @endforelse
                </div>
                <div class="px-6 py-2.5 bg-slate-50/40 flex justify-between font-semibold text-slate-900 border-t border-slate-100">
                    <span>Total Non-Operasional (Neto)</span>
                    <span class="{{ $pendapatanBebanLainBersih >= 0 ? 'text-slate-900' : 'text-rose-600' }}">
                        Rp {{ number_format($pendapatanBebanLainBersih, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- LABA BERSIH HIGHLIGHT ROW -->
            <div class="px-6 py-4.5 {{ $labaBersih >= 0 ? 'bg-emerald-50/70 border-emerald-100 text-emerald-950' : 'bg-rose-50/70 border-rose-100 text-rose-950' }} flex justify-between items-center border-t-2">
                <div>
                    <span class="text-sm font-bold uppercase tracking-wide">Laba Bersih Tahun Berjalan (Net Income)</span>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Setelah memperhitungkan seluruh pendapatan dan beban</p>
                </div>
                <span class="text-base sm:text-lg font-bold {{ $labaBersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </span>
            </div>

        </div>

    </div>

</div>
@endsection
