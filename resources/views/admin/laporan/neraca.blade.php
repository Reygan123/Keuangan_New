@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    
    <!-- 1. Apple-Style Header & Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-amber-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Laporan Neraca</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-[11px] ring-1 ring-indigo-500/10">
                            Per 31 Desember {{ $tahun }}
                        </span>
                        @if($usaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $usaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form method="GET" action="{{ route('admin.laporan.neraca') }}" class="flex flex-wrap items-center gap-2">
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

            <div class="relative">
                <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition active:scale-[0.98] cursor-pointer">
                    @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                    <option value="{{ $i }}" @selected($tahun == $i)>Tahun {{ $i }}</option>
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

    <!-- 2. Ringkasan Eksekutif (3 Kartu KPI Apple Widgets) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Aset -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Aset (Aktiva)</span>
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-indigo-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($totalAset, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Aset Lancar: Rp {{ number_format($totalAsetLancar, 0, ',', '.') }}</span>
                <span>Tetap: Rp {{ number_format($totalAsetTetapNeto, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Total Pasiva -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kewajiban & Ekuitas (Pasiva)</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-purple-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Utang: Rp {{ number_format($totalKewajiban, 0, ',', '.') }}</span>
                <span>Modal: Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Status Keseimbangan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Keseimbangan</span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $statusSeimbang == 'Seimbang' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-500/15' }}">
                    {{ $statusSeimbang }}
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold tracking-tight leading-none mt-1 {{ $statusSeimbang == 'Seimbang' ? 'text-emerald-600' : 'text-rose-600' }}">
                @if($statusSeimbang == 'Seimbang')
                    Rp 0 (Klop)
                @else
                    Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Selisih Aktiva - Pasiva</span>
                <span class="font-semibold {{ $statusSeimbang == 'Seimbang' ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $statusSeimbang == 'Seimbang' ? '100% Presisi' : 'Perlu Penyesuaian' }}
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Dokumen Laporan Resmi 2-Kolom (Apple Document Sheet) -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        
        <!-- Sheet Header -->
        <div class="p-6 border-b border-slate-200/60 text-center bg-slate-50/40">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ $usaha->nama }}</h2>
            <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Laporan Posisi Keuangan (Neraca)</p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Per 31 Desember {{ $tahun }} (Standar SAK EMKM)</p>
        </div>

        <!-- 2-Column Statement Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-200/60">
            
            <!-- SISI KIRI: ASET (AKTIVA) -->
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/70">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Aset (Aktiva)</h3>
                    <span class="text-xs font-medium text-slate-400">IDR</span>
                </div>

                <!-- 1. Aset Lancar -->
                <div class="space-y-1.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aset Lancar</p>
                    <div class="divide-y divide-slate-100 rounded-xl bg-slate-50/50 border border-slate-100 overflow-hidden text-xs">
                        @forelse($neracaItems['ASET_LANCAR'] as $aset)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>{{ $aset['name'] }}</span>
                            <span class="font-medium text-slate-900">{{ number_format($aset['saldo_akhir'], 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-2 text-slate-400 italic">Tidak ada akun aset lancar</div>
                        @endforelse
                        <div class="px-4 py-2 bg-slate-100/70 flex justify-between font-semibold text-slate-900">
                            <span>Subtotal Aset Lancar</span>
                            <span>Rp {{ number_format($totalAsetLancar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Aset Tetap Bruto -->
                <div class="space-y-1.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aset Tetap (Bruto)</p>
                    <div class="divide-y divide-slate-100 rounded-xl bg-slate-50/50 border border-slate-100 overflow-hidden text-xs">
                        @forelse($neracaItems['ASET_TETAP_BRUTO'] as $aset)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>{{ $aset['name'] }}</span>
                            <span class="font-medium text-slate-900">{{ number_format($aset['saldo_akhir'], 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-2 text-slate-400 italic">Tidak ada akun aset tetap</div>
                        @endforelse
                        <div class="px-4 py-2 bg-slate-100/70 flex justify-between font-semibold text-slate-900">
                            <span>Subtotal Aset Tetap Bruto</span>
                            <span>Rp {{ number_format($totalAsetTetapBruto, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Akumulasi Penyusutan -->
                <div class="space-y-1.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Akumulasi Penyusutan</p>
                    <div class="divide-y divide-slate-100 rounded-xl bg-slate-50/50 border border-slate-100 overflow-hidden text-xs">
                        @forelse($neracaItems['AKUMULASI_PENYUSUTAN'] as $akumulasi)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>{{ $akumulasi['name'] }}</span>
                            <span class="font-medium text-rose-600">({{ number_format(abs($akumulasi['saldo_akhir']), 0, ',', '.') }})</span>
                        </div>
                        @empty
                        <div class="px-4 py-2 text-slate-400 italic">Tidak ada akumulasi penyusutan</div>
                        @endforelse
                        <div class="px-4 py-2 bg-slate-100/70 flex justify-between font-semibold text-slate-900">
                            <span>Subtotal Akumulasi Penyusutan</span>
                            <span class="text-rose-600 font-bold">({{ number_format(abs($totalAkumulasiPenyusutan), 0, ',', '.') }})</span>
                        </div>
                    </div>
                </div>

                <!-- Nilai Buku Aset Tetap (Neto) -->
                <div class="px-4 py-2.5 rounded-xl bg-blue-50/70 border border-blue-100/80 flex justify-between items-center text-xs font-semibold text-blue-900">
                    <span>Total Aset Tetap (Neto)</span>
                    <span class="font-bold text-blue-700">Rp {{ number_format($totalAsetTetapNeto, 0, ',', '.') }}</span>
                </div>

                <!-- TOTAL ASET HIGHLIGHT ROW -->
                <div class="px-5 py-3.5 rounded-xl bg-emerald-50/80 border border-emerald-100/90 flex justify-between items-center">
                    <span class="text-xs font-bold text-emerald-950 uppercase tracking-wide">TOTAL ASET (AKTIVA)</span>
                    <span class="text-base font-bold text-emerald-700">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- SISI KANAN: KEWAJIBAN & EKUITAS (PASIVA) -->
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/70">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Kewajiban & Ekuitas (Pasiva)</h3>
                    <span class="text-xs font-medium text-slate-400">IDR</span>
                </div>

                <!-- 1. Kewajiban Lancar -->
                <div class="space-y-1.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kewajiban Lancar</p>
                    <div class="divide-y divide-slate-100 rounded-xl bg-slate-50/50 border border-slate-100 overflow-hidden text-xs">
                        @forelse($neracaItems['KEWAJIBAN_LANCAR'] as $kewajiban)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>{{ $kewajiban['name'] }}</span>
                            <span class="font-medium text-slate-900">{{ number_format($kewajiban['saldo_akhir'], 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-2 text-slate-400 italic">Tidak ada kewajiban lancar</div>
                        @endforelse
                        <div class="px-4 py-2 bg-slate-100/70 flex justify-between font-semibold text-slate-900">
                            <span>Subtotal Kewajiban Lancar</span>
                            <span>Rp {{ number_format($totalKewajibanLancar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Kewajiban Jangka Panjang -->
                <div class="space-y-1.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kewajiban Jangka Panjang</p>
                    <div class="divide-y divide-slate-100 rounded-xl bg-slate-50/50 border border-slate-100 overflow-hidden text-xs">
                        @forelse($neracaItems['KEWAJIBAN_PANJANG'] as $kewajiban)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>{{ $kewajiban['name'] }}</span>
                            <span class="font-medium text-slate-900">{{ number_format($kewajiban['saldo_akhir'], 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-2 text-slate-400 italic">Tidak ada kewajiban jangka panjang</div>
                        @endforelse
                        <div class="px-4 py-2 bg-slate-100/70 flex justify-between font-semibold text-slate-900">
                            <span>Subtotal Kewajiban Panjang</span>
                            <span>Rp {{ number_format($totalKewajibanPanjang, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Kewajiban -->
                <div class="px-4 py-2 rounded-xl bg-amber-50/70 border border-amber-100/80 flex justify-between items-center text-xs font-semibold text-amber-900">
                    <span>Total Kewajiban (Utang)</span>
                    <span class="font-bold text-amber-800">Rp {{ number_format($totalKewajiban, 0, ',', '.') }}</span>
                </div>

                <!-- 3. Ekuitas (Modal) -->
                <div class="space-y-1.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ekuitas (Modal)</p>
                    <div class="divide-y divide-slate-100 rounded-xl bg-slate-50/50 border border-slate-100 overflow-hidden text-xs">
                        @forelse($neracaItems['EKUITAS'] as $ekuitas)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>{{ $ekuitas['name'] }}</span>
                            <span class="font-medium text-slate-900">{{ number_format($ekuitas['saldo_akhir'], 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-2 text-slate-400 italic">Tidak ada akun modal awal</div>
                        @endforelse
                        @if(isset($labaDitahanSebelumnya) && abs($labaDitahanSebelumnya) > 0.01)
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>Laba Ditahan (s/d Tahun Lalu)</span>
                            <span class="font-medium {{ $labaDitahanSebelumnya >= 0 ? 'text-slate-900' : 'text-rose-600' }}">{{ number_format($labaDitahanSebelumnya, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="px-4 py-2 flex justify-between text-slate-700 hover:bg-slate-100/50 transition-colors">
                            <span>Laba Tahun Berjalan</span>
                            <span class="font-medium {{ $labaBersih >= 0 ? 'text-slate-900' : 'text-rose-600' }}">{{ number_format($labaBersih, 0, ',', '.') }}</span>
                        </div>
                        <div class="px-4 py-2 bg-slate-100/70 flex justify-between font-semibold text-slate-900">
                            <span>Subtotal Ekuitas</span>
                            <span>Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- TOTAL PASIVA HIGHLIGHT ROW -->
                <div class="px-5 py-3.5 rounded-xl bg-purple-50/80 border border-purple-100/90 flex justify-between items-center">
                    <span class="text-xs font-bold text-purple-950 uppercase tracking-wide">TOTAL KEWAJIBAN & EKUITAS</span>
                    <span class="text-base font-bold text-purple-700">Rp {{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
