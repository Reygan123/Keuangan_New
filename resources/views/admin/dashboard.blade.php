@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    
    <!-- 1. Apple-Style Header & Floating Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-indigo-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Dashboard Keuangan</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-[11px] ring-1 ring-indigo-500/10">
                            {{ $periodeLabel }}
                        </span>
                        @if($currentUsaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $currentUsaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form method="GET" action="{{ route('admin.dashboard') }}" x-data="{ mode: '{{ $mode }}' }" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition active:scale-[0.98] cursor-pointer">
                    @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            @endif

            <!-- Mode Selector -->
            <div class="relative">
                <select name="mode" x-model="mode" @change="if(mode === 'year' || mode === 'all_time') $nextTick(() => $el.form.submit())" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition active:scale-[0.98] cursor-pointer">
                    <option value="year">Tahun Buku</option>
                    <option value="month">Per Bulan</option>
                    <option value="custom">Rentang Tanggal</option>
                    <option value="all_time">Semua Periode</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Tahun Selector -->
            <div x-show="mode === 'year' || mode === 'month'" class="relative">
                <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-7 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition active:scale-[0.98] cursor-pointer">
                    @forelse($availableYears as $thn)
                    <option value="{{ $thn }}" {{ $selectedYear == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @empty
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                    @endforelse
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Bulan Selector -->
            <div x-show="mode === 'month'" class="relative" x-cloak>
                <select name="bulan" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-7 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition active:scale-[0.98] cursor-pointer">
                    @for($b = 1; $b <= 12; $b++)
                    <option value="{{ $b }}" {{ ($selectedMonth ?? date('n')) == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(2000, $b, 1)->translatedFormat('M') }}
                    </option>
                    @endfor
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Custom Date Inputs -->
            <div x-show="mode === 'custom'" class="flex items-center gap-1.5" x-cloak>
                <input type="date" name="start_date" value="{{ $startDateStr }}" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-medium px-2.5 py-1.5 rounded-xl shadow-2xs focus:outline-none focus:border-indigo-500">
                <span class="text-xs text-slate-400">-</span>
                <input type="date" name="end_date" value="{{ $endDateStr }}" class="bg-white border border-slate-200/80 text-slate-800 text-xs font-medium px-2.5 py-1.5 rounded-xl shadow-2xs focus:outline-none focus:border-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-semibold px-3 py-1.5 rounded-xl transition shadow-xs">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Ringkasan Eksekutif (4 Kartu Finansial Apple Widgets) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Pendapatan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-emerald-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($labaRugi['totalPendapatan'], 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Penjualan Bersih</span>
                <span class="font-semibold text-slate-700">Rp {{ number_format($labaRugi['penjualanBersih'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 2: Pengeluaran -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Beban</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-rose-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($labaRugi['totalBeban'], 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Beban Operasional</span>
                <span class="font-semibold text-slate-700">Rp {{ number_format($labaRugi['totalBebanOperasi'] ?? $labaRugi['bebanOperasional'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 3: Laba Bersih -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laba / Rugi Bersih</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $labaRugi['labaBersih'] >= 0 ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-500/15' }}">
                    {{ $labaRugi['labaBersih'] >= 0 ? 'Surplus' : 'Defisit' }}
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold tracking-tight leading-none mt-1 {{ $labaRugi['labaBersih'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                Rp {{ number_format($labaRugi['labaBersih'], 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Net Margin</span>
                <span class="font-semibold {{ $labaRugi['netProfitMargin'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $labaRugi['netProfitMargin'] }}%</span>
            </div>
        </div>

        <!-- Card 4: Kas & Bank -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kas & Bank Siap Pakai</span>
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-indigo-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-indigo-600 tracking-tight leading-none mt-1">
                Rp {{ number_format($arusKas['totalKasBank'], 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Kas: Rp {{ number_format($arusKas['saldoKas'], 0, ',', '.') }}</span>
                <span>Bank: Rp {{ number_format($arusKas['saldoBank'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- 3. HERO CHART AREA & ALOKASI BEBAN (Apple Segmented Spline & Donut) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ activeTab: 'laba_rugi' }">
        
        <!-- KOLOM 1 & 2: The Hero Chart (Dengan Segmented Switcher Apple) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Tren Performa Finansial</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Perkembangan tren keuangan bulanan periode aktif</p>
                </div>
                
                <!-- Apple Segmented Control -->
                <div class="inline-flex bg-slate-100/90 p-0.5 rounded-xl text-xs font-semibold">
                    <button 
                        type="button"
                        @click="activeTab = 'laba_rugi'; renderHeroChart();"
                        :class="activeTab === 'laba_rugi' ? 'bg-white text-slate-900 shadow-2xs font-semibold' : 'text-slate-500 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-[10px] transition-all duration-150 active:scale-95 cursor-pointer">
                        Laba Rugi
                    </button>
                    <button 
                        type="button"
                        @click="activeTab = 'arus_kas'; renderHeroChart();"
                        :class="activeTab === 'arus_kas' ? 'bg-white text-slate-900 shadow-2xs font-semibold' : 'text-slate-500 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-[10px] transition-all duration-150 active:scale-95 cursor-pointer">
                        Arus Kas
                    </button>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="w-full">
                <div id="apexHeroChart" class="min-h-[310px]"></div>
            </div>

            <!-- Footer Legend Ringkas -->
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs text-slate-500 font-medium">
                <div class="flex items-center gap-4">
                    <template x-if="activeTab === 'laba_rugi'">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Pendapatan</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Beban</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Laba Bersih</span>
                        </div>
                    </template>
                    <template x-if="activeTab === 'arus_kas'">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Kas Masuk</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Kas Keluar</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Net Kas</span>
                        </div>
                    </template>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">
                    Satuan: IDR Rupiah
                </div>
            </div>
        </div>

        <!-- KOLOM 3: Komposisi Beban Pengeluaran (Donut Bersih dengan Center Total) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="mb-2">
                <h2 class="text-base font-bold text-slate-900 tracking-tight">Alokasi Beban</h2>
                <p class="text-xs text-slate-400 mt-0.5">Top pos pengeluaran dalam periode ini</p>
            </div>

            @if($topBeban->count() > 0)
            <div class="relative py-2 flex items-center justify-center">
                <div id="apexDonutBeban" class="w-full"></div>
            </div>

            <!-- List Beban Terbesar -->
            <div class="space-y-2 border-t border-slate-100 pt-3">
                @php
                    $colors = ['#4F46E5', '#06B6D4', '#F59E0B', '#EC4899', '#8B5CF6', '#10B981'];
                    $totalTop = $topBeban->sum('total');
                @endphp
                @foreach($topBeban->take(4) as $idx => $b)
                <div class="flex justify-between items-center text-xs">
                    <div class="flex items-center gap-2 truncate pr-2">
                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $colors[$idx % count($colors)] }}"></span>
                        <span class="text-slate-700 font-medium truncate" title="{{ $b->name }}">{{ $b->name }}</span>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="font-semibold text-slate-900">Rp {{ number_format($b->total, 0, ',', '.') }}</span>
                        @if($totalTop > 0)
                        <span class="text-[10px] text-slate-400 ml-1">({{ round(($b->total / $totalTop) * 100) }}%)</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-64 flex flex-col items-center justify-center text-slate-400 text-xs text-center p-4">
                <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Belum ada transaksi beban yang dicatat pada periode ini.
            </div>
            @endif
        </div>
    </div>

    <!-- 4. POSISI STRUKTUR KEUANGAN & MUTASI TERKINI (Clean Two-Column Layout) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI (1 Kolom): Posisi Keuangan & Ekuitas -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Posisi Neraca</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kekayaan, kewajiban & modal</p>
                    </div>
                    <div>
                        @if($neraca['isSeimbang'])
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 ring-1 ring-emerald-500/15 px-2.5 py-0.5 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            Seimbang
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-700 bg-rose-50 ring-1 ring-rose-500/15 px-2 py-0.5 rounded-full" title="Selisih Rp {{ number_format(abs($neraca['selisih']), 0, ',', '.') }}">
                            Selisih
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Visual Bar Ringkas Proporsi Neraca -->
                <div class="space-y-3.5 text-xs">
                    <div>
                        <div class="flex justify-between text-slate-600 mb-1">
                            <span class="font-medium">Total Aset (Aktiva)</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($neraca['totalAset'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-slate-600 mb-1">
                            <span class="font-medium">Total Kewajiban (Utang)</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($neraca['totalKewajiban'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            @php
                                $totalPassiva = max(1, abs($neraca['totalPassiva']));
                                $wKewajiban = min(100, max(0, round((abs($neraca['totalKewajiban']) / $totalPassiva) * 100)));
                            @endphp
                            <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $wKewajiban }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-slate-600 mb-1">
                            <span class="font-medium">Total Ekuitas (Modal & Laba)</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($neraca['totalEkuitas'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            @php
                                $wEkuitas = min(100, max(0, round((abs($neraca['totalEkuitas']) / $totalPassiva) * 100)));
                            @endphp
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $wEkuitas }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Ekuitas -->
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Laba Ditahan Sebelumnya</span>
                        <span class="font-semibold {{ $neraca['labaDitahanSebelumnya'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            Rp {{ number_format($neraca['labaDitahanSebelumnya'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Laba Periode Berjalan</span>
                        <span class="font-semibold {{ $neraca['labaTahunBerjalan'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            Rp {{ number_format($neraca['labaTahunBerjalan'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('admin.laporan.neraca', ['usaha_id' => $usahaSelected]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 active:scale-95 transition-all">
                    Buka Laporan Neraca &rarr;
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN (2 Kolom): Mutasi Jurnal Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col justify-between">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Mutasi Pembukuan Terkini</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Catatan jurnal umum terbaru di sistem</p>
                </div>
                <a href="{{ route('admin.laporan.jurnal_umum') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 active:scale-95 transition-all">
                    Semua Jurnal &rarr;
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="px-5 py-2.5">Tanggal</th>
                            <th class="px-5 py-2.5">Akun</th>
                            <th class="px-5 py-2.5">Keterangan</th>
                            <th class="px-5 py-2.5 text-right">Debit</th>
                            <th class="px-5 py-2.5 text-right">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jurnalTerakhir as $jurnal)
                        <tr class="hover:bg-slate-50/60 transition-colors duration-150">
                            <td class="px-5 py-2.5 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-2.5 font-medium text-slate-800 truncate max-w-[150px]">
                                {{ $jurnal->akun->name ?? '-' }}
                            </td>
                            <td class="px-5 py-2.5 text-slate-500 truncate max-w-[180px]" title="{{ $jurnal->deskripsi }}">
                                {{ $jurnal->deskripsi ?: '-' }}
                            </td>
                            <td class="px-5 py-2.5 text-right font-medium text-slate-900 whitespace-nowrap">
                                @if($jurnal->debit > 0)
                                    Rp {{ number_format($jurnal->debit, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right font-medium text-slate-900 whitespace-nowrap">
                                @if($jurnal->kredit > 0)
                                    Rp {{ number_format($jurnal->kredit, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-slate-400">
                                Belum ada mutasi jurnal pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-slate-50/40 border-t border-slate-100 text-center">
                <span class="text-[11px] text-slate-400">Menampilkan 8 entri jurnal terbaru</span>
            </div>
        </div>
    </div>

    <!-- 5. Pintasan Navigasi 6 Laporan Resmi (Apple Control Center Tiles) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Akses Modul Laporan Resmi</h3>
            <span class="text-[11px] text-slate-400 font-medium">Standar Akuntansi Keuangan</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('admin.laporan.laba_rugi', ['usaha_id' => $usahaSelected]) }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-indigo-100 hover:bg-slate-50/60 active:scale-[0.98] transition-all duration-150 group flex flex-col justify-between">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Laba Rugi</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Pendapatan & HPP</span>
            </a>
            <a href="{{ route('admin.laporan.neraca', ['usaha_id' => $usahaSelected]) }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-indigo-100 hover:bg-slate-50/60 active:scale-[0.98] transition-all duration-150 group flex flex-col justify-between">
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-2 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Neraca</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Aset & Kewajiban</span>
            </a>
            <a href="{{ route('admin.laporan.arus_kas', ['usaha_id' => $usahaSelected]) }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-indigo-100 hover:bg-slate-50/60 active:scale-[0.98] transition-all duration-150 group flex flex-col justify-between">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Arus Kas</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Aliran Kas Riil</span>
            </a>
            <a href="{{ route('admin.laporan.perubahan_ekuitas', ['usaha_id' => $usahaSelected]) }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-indigo-100 hover:bg-slate-50/60 active:scale-[0.98] transition-all duration-150 group flex flex-col justify-between">
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mb-2 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Perubahan Ekuitas</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Mutasi Modal</span>
            </a>
            <a href="{{ route('admin.laporan.buku_kas', ['usaha_id' => $usahaSelected]) }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-indigo-200 hover:bg-slate-50/60 active:scale-[0.98] transition-all duration-150 group flex flex-col justify-between">
                <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center mb-2 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Buku Kas & Bank</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Rekap Saldo Bank</span>
            </a>
            <a href="{{ route('admin.laporan.neraca_lajur', ['usaha_id' => $usahaSelected]) }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-indigo-100 hover:bg-slate-50/60 active:scale-[0.98] transition-all duration-150 group flex flex-col justify-between">
                <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center mb-2 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600">Neraca Lajur</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Kertas Kerja 10 Kolom</span>
            </a>
        </div>
    </div>

</div>

<!-- ApexCharts Engine (Apple-Style Spline Curve & Clean Visuals) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Format angka singkat (Jt, M)
function formatRupiahSingkat(val) {
    if (Math.abs(val) >= 1000000000) {
        return (val / 1000000000).toFixed(1) + ' M';
    } else if (Math.abs(val) >= 1000000) {
        return (val / 1000000).toFixed(1) + ' Jt';
    } else if (Math.abs(val) >= 1000) {
        return (val / 1000).toFixed(0) + ' Rb';
    }
    return new Intl.NumberFormat('id-ID').format(val);
}

// Format Rupiah Full
function formatRupiahFull(val) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
}

let heroChartInstance = null;

// Data dari Backend
const labelsBulan = @json(array_column($grafikTren, 'bulan'));
const dataPendapatan = @json(array_column($grafikTren, 'pendapatan'));
const dataBeban = @json(array_column($grafikTren, 'beban'));
const dataLabaBersih = @json(array_column($grafikTren, 'laba_bersih'));

const dataKasMasuk = @json(array_column($grafikArusKas, 'kas_masuk'));
const dataKasKeluar = @json(array_column($grafikArusKas, 'kas_keluar'));
const dataNetKas = @json(array_column($grafikArusKas, 'net_kas'));

function renderHeroChart() {
    const el = document.getElementById('apexHeroChart');
    if (!el) return;

    // Baca state tab aktif
    const container = el.closest('[x-data]');
    const activeTab = (container && container._x_dataStack) ? container._x_dataStack[0].activeTab : 'laba_rugi';

    let series = [];
    let colors = [];

    if (activeTab === 'laba_rugi') {
        series = [
            { name: 'Pendapatan', type: 'area', data: dataPendapatan },
            { name: 'Beban', type: 'area', data: dataBeban },
            { name: 'Laba Bersih', type: 'line', data: dataLabaBersih }
        ];
        colors = ['#4F46E5', '#F43F5E', '#10B981'];
    } else {
        series = [
            { name: 'Kas Masuk', type: 'area', data: dataKasMasuk },
            { name: 'Kas Keluar', type: 'area', data: dataKasKeluar },
            { name: 'Net Kas', type: 'line', data: dataNetKas }
        ];
        colors = ['#10B981', '#F43F5E', '#4F46E5'];
    }

    const options = {
        series: series,
        chart: {
            height: 310,
            type: 'line',
            fontFamily: "'Sora', -apple-system, BlinkMacSystemFont, sans-serif",
            toolbar: { show: false },
            zoom: { enabled: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 400
            }
        },
        colors: colors,
        stroke: {
            curve: 'smooth',
            width: [2, 2, 2.5],
            dashArray: [0, 0, 0]
        },
        fill: {
            type: ['gradient', 'gradient', 'solid'],
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                opacityFrom: 0.18,
                opacityTo: 0.02,
                stops: [0, 90, 100]
            }
        },
        markers: {
            size: 0,
            hover: { size: 5 }
        },
        grid: {
            borderColor: '#F1F5F9',
            strokeDashArray: 3,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
            padding: { top: 0, right: 10, bottom: 0, left: 10 }
        },
        xaxis: {
            categories: labelsBulan,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#94A3B8', fontSize: '11px', fontWeight: 500 }
            }
        },
        yaxis: {
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#94A3B8', fontSize: '11px', fontWeight: 500 },
                formatter: formatRupiahSingkat
            }
        },
        legend: { show: false },
        tooltip: {
            theme: 'light',
            shared: true,
            intersect: false,
            y: {
                formatter: formatRupiahFull
            }
        }
    };

    if (heroChartInstance) {
        heroChartInstance.destroy();
    }
    heroChartInstance = new ApexCharts(el, options);
    heroChartInstance.render();
}

// 2. Render Donut Chart Komposisi Beban
function renderDonutChart() {
    const el = document.getElementById('apexDonutBeban');
    if (!el) return;

    const bebanData = @json($topBeban);
    if (!bebanData || bebanData.length === 0) return;

    const seriesData = bebanData.map(b => parseFloat(b.total));
    const labelsData = bebanData.map(b => b.name);
    const totalBebanNominal = seriesData.reduce((a, b) => a + b, 0);

    const options = {
        series: seriesData,
        labels: labelsData,
        chart: {
            type: 'donut',
            height: 230,
            fontFamily: "'Sora', -apple-system, BlinkMacSystemFont, sans-serif"
        },
        colors: ['#4F46E5', '#06B6D4', '#F59E0B', '#EC4899', '#8B5CF6', '#10B981'],
        stroke: { width: 0 },
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '76%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '11px',
                            color: '#94A3B8',
                            offsetY: -4
                        },
                        value: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: 700,
                            color: '#0F172A',
                            offsetY: 2,
                            formatter: formatRupiahSingkat
                        },
                        total: {
                            show: true,
                            label: 'Total Top Beban',
                            fontSize: '10px',
                            color: '#64748B',
                            formatter: () => formatRupiahSingkat(totalBebanNominal)
                        }
                    }
                }
            }
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: formatRupiahFull
            }
        }
    };

    const donut = new ApexCharts(el, options);
    donut.render();
}

document.addEventListener('DOMContentLoaded', function() {
    renderHeroChart();
    renderDonutChart();
});
</script>
@endsection