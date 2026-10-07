@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    
    <!-- 1. Apple-Style Header & Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-emerald-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Neraca Lajur</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-[11px] ring-1 ring-emerald-500/10">
                            Kertas Kerja 10 Kolom &bull; Periode 31 Des {{ $tahun }}
                        </span>
                        @if($usaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $usaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form method="GET" action="{{ route('admin.laporan.neraca_lajur') }}" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition active:scale-[0.98] cursor-pointer">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            @endif

            <div class="relative">
                <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition active:scale-[0.98] cursor-pointer">
                    @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

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

    <!-- 2. Apple Numbers Document Sheet (Worksheet 10 Kolom) -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <!-- Document Header (Kop Laporan) -->
        <div class="px-6 py-6 border-b border-slate-100 bg-slate-50/50 text-center">
            <span class="text-[11px] font-bold tracking-widest text-emerald-600 uppercase">Kertas Kerja Akuntansi</span>
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $usaha ? $usaha->nama : '-' }}</h2>
            <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mt-0.5">Neraca Lajur (10 Kolom)</p>
            <p class="text-[11px] text-slate-400 font-medium mt-1">Per 31 Desember {{ $tahun }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-700 text-[11px] font-bold">
                        <th rowspan="2" class="px-3 py-3 text-center border-r border-slate-200/70 w-20">Kode</th>
                        <th rowspan="2" class="px-4 py-3 text-left border-r border-slate-200/70 min-w-[220px]">Nama Akun</th>
                        <th colspan="2" class="px-3 py-2 text-center border-r border-slate-200/70 bg-slate-100/50">Neraca Saldo</th>
                        <th colspan="2" class="px-3 py-2 text-center border-r border-slate-200/70 bg-amber-50/40 text-amber-900">Jurnal Penyesuaian</th>
                        <th colspan="2" class="px-3 py-2 text-center border-r border-slate-200/70 bg-slate-100/50">NS Disesuaikan</th>
                        <th colspan="2" class="px-3 py-2 text-center border-r border-slate-200/70 bg-indigo-50/40 text-indigo-900">Laba Rugi</th>
                        <th colspan="2" class="px-3 py-2 text-center bg-emerald-50/40 text-emerald-900">Neraca</th>
                    </tr>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px]">Debit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px]">Kredit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px] bg-amber-50/20">Debit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px] bg-amber-50/20">Kredit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px]">Debit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px]">Kredit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px] bg-indigo-50/20">Debit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px] bg-indigo-50/20">Kredit</th>
                        <th class="px-3 py-2 text-right border-r border-slate-200/70 min-w-[105px] bg-emerald-50/20">Debit</th>
                        <th class="px-3 py-2 text-right min-w-[105px] bg-emerald-50/20">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($rows as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 py-2 text-center text-slate-500 border-r border-slate-100 font-mono text-[11px]">{{ $row['kode'] }}</td>
                        <td class="px-4 py-2 text-slate-800 border-r border-slate-100 font-medium">{{ $row['name'] }}</td>
                        <td class="px-3 py-2 text-right text-slate-700 border-r border-slate-100 font-mono">{{ $row['ns_debit'] > 0 ? number_format($row['ns_debit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-slate-700 border-r border-slate-100 font-mono">{{ $row['ns_kredit'] > 0 ? number_format($row['ns_kredit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-amber-700 border-r border-slate-100 font-mono bg-amber-50/10">{{ $row['jp_debit'] > 0 ? number_format($row['jp_debit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-amber-700 border-r border-slate-100 font-mono bg-amber-50/10">{{ $row['jp_kredit'] > 0 ? number_format($row['jp_kredit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-slate-700 border-r border-slate-100 font-mono">{{ $row['nssd'] > 0 ? number_format($row['nssd'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-slate-700 border-r border-slate-100 font-mono">{{ $row['nssk'] > 0 ? number_format($row['nssk'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-indigo-700 border-r border-slate-100 font-mono bg-indigo-50/10">{{ $row['lr_debit'] > 0 ? number_format($row['lr_debit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-indigo-700 border-r border-slate-100 font-mono bg-indigo-50/10">{{ $row['lr_kredit'] > 0 ? number_format($row['lr_kredit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-emerald-700 border-r border-slate-100 font-mono bg-emerald-50/10">{{ $row['n_debit'] > 0 ? number_format($row['n_debit'], 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-emerald-700 font-mono bg-emerald-50/10">{{ $row['n_kredit'] > 0 ? number_format($row['n_kredit'], 0, ',', '.') : '-' }}</td>
                    </tr>
                    @endforeach

                    <!-- Baris Subtotal Jumlah Sebelum Penyesuaian Laba Rugi -->
                    <tr class="bg-slate-50/80 border-t-2 border-slate-200 font-semibold text-slate-900">
                        <td colspan="2" class="px-4 py-3 text-slate-900 border-r border-slate-200 uppercase text-[11px] tracking-wide">Jumlah Saldo</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono">{{ number_format($totals['ns_debit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono">{{ number_format($totals['ns_kredit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono text-amber-800">{{ number_format($totals['jp_debit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono text-amber-800">{{ number_format($totals['jp_kredit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono">{{ number_format($totals['nssd'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono">{{ number_format($totals['nssk'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono text-indigo-800">{{ number_format($totals['lr_debit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono text-indigo-800">{{ number_format($totals['lr_kredit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono text-emerald-800">{{ number_format($totals['n_debit'], 0, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right font-mono text-emerald-800">{{ number_format($totals['n_kredit'], 0, ',', '.') }}</td>
                    </tr>

                    <!-- Baris Laba / Rugi Bersih Usaha -->
                    @if($labaUsaha != 0)
                    <tr class="bg-white border-t border-slate-200 font-medium">
                        <td colspan="2" class="px-4 py-3 text-slate-900 border-r border-slate-200 flex items-center justify-between">
                            <span class="font-bold {{ $labaUsaha >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $labaUsaha >= 0 ? 'Laba Bersih Tahun Berjalan' : 'Rugi Bersih Tahun Berjalan' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $labaUsaha >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ $labaUsaha >= 0 ? 'Surplus' : 'Defisit' }}
                            </span>
                        </td>
                        <td colspan="6" class="border-r border-slate-200 bg-slate-50/20"></td>
                        {{-- Laba Rugi: Laba di Debit, Rugi di Kredit --}}
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono font-bold text-indigo-700">
                            {{ $labaUsaha > 0 ? number_format($labaUsaha, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono font-bold text-rose-600">
                            {{ $labaUsaha < 0 ? number_format(abs($labaUsaha), 0, ',', '.') : '-' }}
                        </td>
                        {{-- Neraca: Rugi di Debit, Laba di Kredit --}}
                        <td class="px-3 py-3 text-right border-r border-slate-200 font-mono font-bold text-rose-600">
                            {{ $labaUsaha < 0 ? number_format(abs($labaUsaha), 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-3 py-3 text-right font-mono font-bold text-emerald-700">
                            {{ $labaUsaha > 0 ? number_format($labaUsaha, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @endif

                    <!-- Baris Grand Total Seimbang (Double Underline Accounting Style) -->
                    <tr class="bg-gradient-to-r from-slate-100 via-slate-50 to-slate-100 border-t-2 border-slate-300 font-bold text-slate-900">
                        <td colspan="2" class="px-4 py-3.5 text-slate-900 border-r border-slate-200 uppercase text-xs tracking-wider">
                            Total Seimbang (Balance)
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-slate-900">
                            {{ number_format($totals['ns_debit'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-slate-900">
                            {{ number_format($totals['ns_kredit'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-amber-900">
                            {{ number_format($totals['jp_debit'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-amber-900">
                            {{ number_format($totals['jp_kredit'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-slate-900">
                            {{ number_format($totals['nssd'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-slate-900">
                            {{ number_format($totals['nssk'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-indigo-900">
                            {{ number_format(max($totals['lr_debit'], $totals['lr_kredit']), 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-indigo-900">
                            {{ number_format(max($totals['lr_debit'], $totals['lr_kredit']), 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right border-r border-slate-200 font-mono font-bold text-emerald-900">
                            {{ number_format(max($totals['n_debit'], $totals['n_kredit']), 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right font-mono font-bold text-emerald-900">
                            {{ number_format(max($totals['n_debit'], $totals['n_kredit']), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
