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
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Laporan Perubahan Ekuitas</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 font-semibold text-[11px] ring-1 ring-purple-500/10">
                            {{ \Carbon\Carbon::parse($bulan_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($bulan_akhir)->format('d M Y') }}
                        </span>
                        @if($usaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $usaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form method="GET" action="{{ route('admin.laporan.perubahan_ekuitas') }}" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition active:scale-[0.98] cursor-pointer">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
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

    @php
        $subCeo = ($modalAwalCeo ?? 0) + ($setoranCeo ?? 0);
        $subCto = ($modalAwalCto ?? 0) + ($setoranCto ?? 0);
        $subBersama = ($labaDitahan ?? 0) + ($labaTahunBerjalan ?? 0);
        $subTotal = $subCeo + $subCto + $subBersama;

        $priveTotal = ($dividen ?? 0) + ($priveCeo ?? 0) + ($priveCto ?? 0);

        $akhirCeo = $subCeo - ($priveCeo ?? 0);
        $akhirCto = $subCto - ($priveCto ?? 0);
        $akhirBersama = $subBersama - ($dividen ?? 0);
        $akhirTotal = $akhirCeo + $akhirCto + $akhirBersama;

        $fmt = fn($v) => ($v < 0 ? '(' . number_format(abs($v), 0, ',', '.') . ')' : number_format($v, 0, ',', '.'));

        $kpiModalAwal = ($hasCeoCto ?? false) ? (($modalAwalCeo ?? 0) + ($modalAwalCto ?? 0)) : ($totalModalAwalAkun ?? 0);
        $kpiLaba = ($hasCeoCto ?? false) ? ($labaTahunBerjalan ?? 0) : ($labaPeriodeBerjalan ?? 0);
        $kpiPrive = ($hasCeoCto ?? false) ? $priveTotal : ($totalPriveDividen ?? 0);
        $kpiModalAkhir = ($hasCeoCto ?? false) ? $akhirTotal : ($modalAkhir ?? 0);
    @endphp

    <!-- 2. Ringkasan Eksekutif (4 Kartu KPI Apple Widgets) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Modal Awal -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modal Awal Periode</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="text-[22px] sm:text-[24px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($kpiModalAwal, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Per {{ \Carbon\Carbon::parse($bulan_awal)->format('d M Y') }}</span>
                <span class="text-slate-400">Saldo Awal</span>
            </div>
        </div>

        <!-- Laba (Rugi) Periode Berjalan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laba Bersih Berjalan</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $kpiLaba >= 0 ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-500/15' }}">
                    {{ $kpiLaba >= 0 ? '+ Penambahan' : '- Pengurangan' }}
                </span>
            </div>
            <div class="text-[22px] sm:text-[24px] font-bold {{ $kpiLaba >= 0 ? 'text-emerald-600' : 'text-rose-600' }} tracking-tight leading-none mt-1">
                Rp {{ number_format($kpiLaba, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Dari Laba Rugi</span>
                <span class="font-semibold {{ $kpiLaba >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $kpiLaba >= 0 ? 'Surplus' : 'Defisit' }}</span>
            </div>
        </div>

        <!-- Penarikan Prive / Dividen -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Prive & Dividen</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-rose-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </span>
            </div>
            <div class="text-[22px] sm:text-[24px] font-bold text-rose-600 tracking-tight leading-none mt-1">
                Rp {{ number_format($kpiPrive, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Distribusi Pemilik</span>
                <span class="text-rose-600 font-medium">Pengurang</span>
            </div>
        </div>

        <!-- Modal Akhir -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modal Akhir Periode</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-purple-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
            </div>
            <div class="text-[22px] sm:text-[24px] font-bold text-indigo-700 tracking-tight leading-none mt-1">
                Rp {{ number_format($kpiModalAkhir, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Per {{ \Carbon\Carbon::parse($bulan_akhir)->format('d M Y') }}</span>
                <span class="font-semibold text-indigo-600">Ekuitas Bersih</span>
            </div>
        </div>
    </div>

    <!-- 3. Apple Document Sheet: Tabel Perubahan Ekuitas Resmi -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <!-- Document Header (Kop Laporan) -->
        <div class="px-6 py-6 border-b border-slate-100 bg-slate-50/50 text-center">
            <span class="text-[11px] font-bold tracking-widest text-indigo-600 uppercase">Laporan Keuangan Resmi</span>
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $usaha ? $usaha->nama : '-' }}</h2>
            <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mt-0.5">Laporan Perubahan Ekuitas</p>
            <p class="text-[11px] text-slate-400 font-medium mt-1">Periode {{ \Carbon\Carbon::parse($bulan_awal)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($bulan_akhir)->format('d F Y') }}</p>
        </div>

        @if($hasCeoCto ?? false)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/80 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                        <th class="px-6 py-3.5">Keterangan</th>
                        <th class="px-6 py-3.5 text-right">CEO</th>
                        <th class="px-6 py-3.5 text-right">CTO</th>
                        <th class="px-6 py-3.5 text-right">Total Ekuitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Modal Awal Periode</td>
                        <td class="px-6 py-3.5 text-right text-slate-700 font-mono">{{ $fmt($modalAwalCeo) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-700 font-mono">{{ $fmt($modalAwalCto) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ $fmt($modalAwalCeo + $modalAwalCto) }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Setoran Tambahan Modal</td>
                        <td class="px-6 py-3.5 text-right text-emerald-600 font-mono">{{ $setoranCeo > 0 ? '+ ' . $fmt($setoranCeo) : '-' }}</td>
                        <td class="px-6 py-3.5 text-right text-emerald-600 font-mono">{{ $setoranCto > 0 ? '+ ' . $fmt($setoranCto) : '-' }}</td>
                        <td class="px-6 py-3.5 text-right text-emerald-600 font-semibold font-mono">{{ ($setoranCeo + $setoranCto) > 0 ? '+ ' . $fmt($setoranCeo + $setoranCto) : '-' }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Laba Ditahan</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ $fmt($labaDitahan) }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Laba (Rugi) Periode Berjalan</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right font-semibold font-mono {{ $labaTahunBerjalan >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $fmt($labaTahunBerjalan) }}
                        </td>
                    </tr>

                    <tr class="bg-slate-50/80 border-y border-slate-200/80 font-semibold">
                        <td class="px-6 py-3 text-slate-900 text-xs uppercase tracking-wide">Sub Total</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono">{{ $fmt($subCeo) }}</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-mono">{{ $fmt($subCto) }}</td>
                        <td class="px-6 py-3 text-right text-slate-900 font-bold font-mono">{{ $fmt($subTotal) }}</td>
                    </tr>

                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Dividen</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-medium font-mono">({{ number_format($dividen, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Prive Pemilik</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-mono">({{ number_format($priveCeo, 0, ',', '.') }})</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-mono">({{ number_format($priveCto, 0, ',', '.') }})</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-semibold font-mono">({{ number_format($priveCeo + $priveCto, 0, ',', '.') }})</td>
                    </tr>

                    <tr class="bg-gradient-to-r from-purple-50/50 via-slate-50 to-purple-50/30 border-t-2 border-slate-300">
                        <td class="px-6 py-4 text-sm sm:text-base font-bold text-slate-900">Modal Akhir Periode</td>
                        <td class="px-6 py-4 text-sm sm:text-base text-right font-bold text-indigo-700 font-mono">{{ $fmt($akhirCeo) }}</td>
                        <td class="px-6 py-4 text-sm sm:text-base text-right font-bold text-indigo-700 font-mono">{{ $fmt($akhirCto) }}</td>
                        <td class="px-6 py-4 text-sm sm:text-base text-right font-bold text-indigo-700 font-mono">{{ $fmt($akhirTotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
        {{-- Tabel Universal untuk Usaha Umum / PT / CV --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/80 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                        <th class="px-6 py-3.5">Komponen Ekuitas</th>
                        <th class="px-6 py-3.5 text-right">Jumlah (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Modal Awal Periode</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ $fmt($totalModalAwalAkun ?? 0) }}</td>
                    </tr>
                    @if(abs($labaDitahanAwal ?? 0) > 0.01)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Laba Ditahan (Sebelum Periode)</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ $fmt($labaDitahanAwal ?? 0) }}</td>
                    </tr>
                    @endif
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Setoran / Penambahan Modal</td>
                        <td class="px-6 py-3.5 text-right text-emerald-600 font-semibold font-mono">+ {{ $fmt($totalSetoranModal ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Laba (Rugi) Periode Berjalan</td>
                        <td class="px-6 py-3.5 text-right font-semibold font-mono {{ ($labaPeriodeBerjalan ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $fmt($labaPeriodeBerjalan ?? 0) }}
                        </td>
                    </tr>
                    @if(($totalPriveDividen ?? 0) > 0.01)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Pengurangan (Prive / Dividen)</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-semibold font-mono">({{ number_format($totalPriveDividen ?? 0, 0, ',', '.') }})</td>
                    </tr>
                    @endif
                    <tr class="bg-gradient-to-r from-purple-50/50 via-slate-50 to-purple-50/30 border-t-2 border-slate-300">
                        <td class="px-6 py-4 text-sm sm:text-base font-bold text-slate-900">Modal Akhir Periode</td>
                        <td class="px-6 py-4 text-sm sm:text-base text-right font-bold text-indigo-700 font-mono">{{ $fmt($modalAkhir ?? 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- 4. Rincian per Akun Ekuitas --}}
    @if(isset($barisAkun) && count($barisAkun) > 0)
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-900 tracking-tight">Rincian Posisi Per Akun Ekuitas</h3>
            <p class="text-xs text-slate-500 mt-0.5">Saldo awal, penambahan, dan pengurangan akun modal pada buku besar</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 border-b border-slate-200/80 font-bold text-[11px] uppercase tracking-wide">
                        <th class="px-5 py-3">Kode Akun</th>
                        <th class="px-5 py-3">Nama Akun</th>
                        <th class="px-5 py-3 text-right">Saldo Awal</th>
                        <th class="px-5 py-3 text-right">Penambahan</th>
                        <th class="px-5 py-3 text-right">Pengurangan</th>
                        <th class="px-5 py-3 text-right">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($barisAkun as $b)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-3 font-mono text-slate-500 font-medium">{{ $b['kode'] }}</td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $b['name'] }}</td>
                        <td class="px-5 py-3 text-right font-mono text-slate-700">{{ number_format($b['saldo_awal'], 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right font-mono text-emerald-600 font-medium">{{ $b['penambahan'] > 0 ? number_format($b['penambahan'], 0, ',', '.') : '-' }}</td>
                        <td class="px-5 py-3 text-right font-mono text-rose-600 font-medium">{{ $b['pengurangan'] > 0 ? '(' . number_format($b['pengurangan'], 0, ',', '.') . ')' : '-' }}</td>
                        <td class="px-5 py-3 text-right font-mono font-semibold text-slate-900">{{ number_format($b['saldo_akhir'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
