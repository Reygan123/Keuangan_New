@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    
    <!-- 1. Apple-Style Header & Filter Toolbar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pt-1 pb-2">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold flex-shrink-0 shadow-2xs ring-1 ring-cyan-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Laporan Arus Kas</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-cyan-50 text-cyan-700 font-semibold text-[11px] ring-1 ring-cyan-500/10">
                            Periode 1 Jan - 31 Des {{ $tahun }}
                        </span>
                        @if($usaha)
                        <span class="text-xs text-slate-500 font-medium">&bull; <span class="text-slate-800 font-semibold">{{ $usaha->nama }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls (Pill Dropdowns with Instant Feedback) -->
        <form method="GET" action="{{ route('admin.laporan.arus_kas') }}" class="flex flex-wrap items-center gap-2">
            @if($usahas && $usahas->count() > 0)
            <div class="relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition active:scale-[0.98] cursor-pointer">
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
                <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200/80 text-slate-800 text-xs font-semibold px-3 py-2 pr-8 rounded-xl shadow-2xs hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition active:scale-[0.98] cursor-pointer">
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
        <!-- Saldo Awal Kas -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Saldo Awal Kas & Bank</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($saldoAwalKas, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Posisi 1 Januari {{ $tahun }}</span>
                <span class="text-slate-400">Kas Awal</span>
            </div>
        </div>

        <!-- Total Arus Kas Bersih -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Arus Kas Bersih (Net)</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $totalArusKasBersih >= 0 ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/15' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-500/15' }}">
                    {{ $totalArusKasBersih >= 0 ? '+ Surplus Kas' : '- Defisit Kas' }}
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold {{ $totalArusKasBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }} tracking-tight leading-none mt-1">
                Rp {{ number_format($totalArusKasBersih, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Pergerakan Net Tahun Ini</span>
                <span class="font-semibold {{ $totalArusKasBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $totalArusKasBersih >= 0 ? 'Kas Bertambah' : 'Kas Berkurang' }}
                </span>
            </div>
        </div>

        <!-- Saldo Akhir Kas -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Saldo Akhir Kas & Bank</span>
                <span class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center flex-shrink-0 shadow-2xs ring-1 ring-cyan-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
            </div>
            <div class="text-[24px] sm:text-[26px] font-bold text-slate-900 tracking-tight leading-none mt-1">
                Rp {{ number_format($saldoAkhirKas, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Kas: Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                <span>Bank: Rp {{ number_format($saldoBank, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- 3. Tiga Pilar Arus Kas (Operasi, Investasi, Pendanaan) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Aktivitas Operasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Aktivitas Operasi</h3>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md">Bisnis Inti</span>
                </div>
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Penerimaan Kas</span>
                        <span class="font-semibold text-emerald-600">+ Rp {{ number_format($arusKas['OPERASI']['penerimaan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600 pb-2 border-b border-slate-100">
                        <span>Pengeluaran Kas</span>
                        <span class="font-semibold text-rose-600">- Rp {{ number_format($arusKas['OPERASI']['pengeluaran'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="pt-3 flex justify-between items-center">
                <span class="text-xs font-medium text-slate-700">Arus Kas Operasi Net</span>
                <span class="text-sm font-bold {{ $arusKasBersihOperasi >= 0 ? 'text-blue-600' : 'text-rose-600' }}">
                    Rp {{ number_format($arusKasBersihOperasi, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Aktivitas Investasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Aktivitas Investasi</h3>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md">Aset Tetap</span>
                </div>
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Penerimaan Kas</span>
                        <span class="font-semibold text-emerald-600">+ Rp {{ number_format($arusKas['INVESTASI']['penerimaan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600 pb-2 border-b border-slate-100">
                        <span>Pengeluaran Kas</span>
                        <span class="font-semibold text-rose-600">- Rp {{ number_format($arusKas['INVESTASI']['pengeluaran'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="pt-3 flex justify-between items-center">
                <span class="text-xs font-medium text-slate-700">Arus Kas Investasi Net</span>
                <span class="text-sm font-bold {{ $arusKasBersihInvestasi >= 0 ? 'text-amber-600' : 'text-rose-600' }}">
                    Rp {{ number_format($arusKasBersihInvestasi, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Aktivitas Pendanaan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Aktivitas Pendanaan</h3>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md">Modal & Utang</span>
                </div>
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Penerimaan Kas</span>
                        <span class="font-semibold text-emerald-600">+ Rp {{ number_format($arusKas['PENDANAAN']['penerimaan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600 pb-2 border-b border-slate-100">
                        <span>Pengeluaran Kas</span>
                        <span class="font-semibold text-rose-600">- Rp {{ number_format($arusKas['PENDANAAN']['pengeluaran'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="pt-3 flex justify-between items-center">
                <span class="text-xs font-medium text-slate-700">Arus Kas Pendanaan Net</span>
                <span class="text-sm font-bold {{ $arusKasBersihPendanaan >= 0 ? 'text-purple-600' : 'text-rose-600' }}">
                    Rp {{ number_format($arusKasBersihPendanaan, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- 4. Apple Document Sheet: Format Standar Akuntansi Laporan Arus Kas -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <!-- Document Header (Kop Laporan) -->
        <div class="px-6 py-6 border-b border-slate-100 bg-slate-50/50 text-center">
            <span class="text-[11px] font-bold tracking-widest text-indigo-600 uppercase">Laporan Keuangan Resmi</span>
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $usaha ? $usaha->nama : 'Semua Unit Usaha' }}</h2>
            <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mt-0.5">Laporan Arus Kas (Metode Langsung)</p>
            <p class="text-[11px] text-slate-400 font-medium mt-1">Untuk Periode yang Berakhir pada 31 Desember {{ $tahun }}</p>
        </div>

        <div class="p-6 md:p-8 space-y-8">
            <!-- I. AKTIVITAS OPERASI -->
            <div class="space-y-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-200 text-slate-900 font-bold text-sm">
                    <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                    <span>I. ARUS KAS DARI AKTIVITAS OPERASI</span>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between items-center pl-4">
                        <span class="text-slate-600">Penerimaan kas dari pelanggan & operasi usaha</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($arusKas['OPERASI']['penerimaan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center pl-4">
                        <span class="text-slate-600">Pengeluaran kas untuk beban operasional & pemasok</span>
                        <span class="font-semibold text-slate-900">(Rp {{ number_format($arusKas['OPERASI']['pengeluaran'], 0, ',', '.') }})</span>
                    </div>
                    <div class="py-3 flex justify-between items-center bg-slate-50/70 px-4 rounded-xl mt-1 font-semibold">
                        <span class="text-slate-800">Arus Kas Bersih yang Diperoleh dari (Digunakan untuk) Aktivitas Operasi</span>
                        <span class="font-bold {{ $arusKasBersihOperasi >= 0 ? 'text-blue-700' : 'text-rose-600' }}">
                            Rp {{ number_format($arusKasBersihOperasi, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- II. AKTIVITAS INVESTASI -->
            <div class="space-y-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-200 text-slate-900 font-bold text-sm">
                    <span class="w-1.5 h-4 bg-amber-500 rounded-full"></span>
                    <span>II. ARUS KAS DARI AKTIVITAS INVESTASI</span>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between items-center pl-4">
                        <span class="text-slate-600">Penerimaan dari pelepasan / penjualan aset tetap</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($arusKas['INVESTASI']['penerimaan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center pl-4">
                        <span class="text-slate-600">Pengeluaran kas untuk perolehan aset tetap & investasi</span>
                        <span class="font-semibold text-slate-900">(Rp {{ number_format($arusKas['INVESTASI']['pengeluaran'], 0, ',', '.') }})</span>
                    </div>
                    <div class="py-3 flex justify-between items-center bg-slate-50/70 px-4 rounded-xl mt-1 font-semibold">
                        <span class="text-slate-800">Arus Kas Bersih yang Diperoleh dari (Digunakan untuk) Aktivitas Investasi</span>
                        <span class="font-bold {{ $arusKasBersihInvestasi >= 0 ? 'text-amber-700' : 'text-rose-600' }}">
                            Rp {{ number_format($arusKasBersihInvestasi, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- III. AKTIVITAS PENDANAAN -->
            <div class="space-y-2">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-200 text-slate-900 font-bold text-sm">
                    <span class="w-1.5 h-4 bg-purple-600 rounded-full"></span>
                    <span>III. ARUS KAS DARI AKTIVITAS PENDANAAN</span>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between items-center pl-4">
                        <span class="text-slate-600">Penerimaan kas dari setoran modal & pinjaman pendanaan</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($arusKas['PENDANAAN']['penerimaan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center pl-4">
                        <span class="text-slate-600">Pengeluaran kas untuk penarikan modal / prive & pelunasan kewajiban panjang</span>
                        <span class="font-semibold text-slate-900">(Rp {{ number_format($arusKas['PENDANAAN']['pengeluaran'], 0, ',', '.') }})</span>
                    </div>
                    <div class="py-3 flex justify-between items-center bg-slate-50/70 px-4 rounded-xl mt-1 font-semibold">
                        <span class="text-slate-800">Arus Kas Bersih yang Diperoleh dari (Digunakan untuk) Aktivitas Pendanaan</span>
                        <span class="font-bold {{ $arusKasBersihPendanaan >= 0 ? 'text-purple-700' : 'text-rose-600' }}">
                            Rp {{ number_format($arusKasBersihPendanaan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- REKONSILIASI AKHIR KAS (GRAND SUMMARY) -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 rounded-2xl p-5 border border-slate-200/80 space-y-3">
                <div class="flex justify-between items-center text-xs font-semibold text-slate-700">
                    <span>Kenaikan (Penurunan) Kas Bersih Tahun Berjalan</span>
                    <span class="font-bold {{ $totalArusKasBersih >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                        Rp {{ number_format($totalArusKasBersih, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs text-slate-600 pb-3 border-b border-slate-200">
                    <span>Saldo Kas & Bank pada Awal Periode (1 Jan {{ $tahun }})</span>
                    <span class="font-semibold text-slate-900">Rp {{ number_format($saldoAwalKas, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <div>
                        <span class="text-sm font-bold text-slate-900 block">Saldo Kas & Bank pada Akhir Periode (31 Des {{ $tahun }})</span>
                        <span class="text-[11px] text-slate-500 font-medium">Terverifikasi sesuai rekonsiliasi buku kas & rekening bank</span>
                    </div>
                    <div class="text-right">
                        <span class="text-base sm:text-lg font-bold text-indigo-700 tracking-tight">
                            Rp {{ number_format($saldoAkhirKas, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Daftar Rincian Riwayat Transaksi Kas Per Aktivitas -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-slate-900 tracking-tight">Log Transaksi Mutasi Kas</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar buku jurnal kas masuk dan keluar yang membentuk arus kas</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Penerimaan
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Pengeluaran
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            @php
                $configs = [
                    'OPERASI' => ['label' => 'Operasional', 'color' => 'blue', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200/50'],
                    'INVESTASI' => ['label' => 'Investasi', 'color' => 'amber', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200/50'],
                    'PENDANAAN' => ['label' => 'Pendanaan', 'color' => 'purple', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200/50'],
                ];
            @endphp

            @foreach($configs as $tipe => $cfg)
            <div class="flex flex-col">
                <div class="px-5 py-3 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">{{ $cfg['label'] }}</span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white border border-slate-200 text-slate-600 shadow-2xs">
                        {{ count($arusKas[$tipe]['transaksi']) }} Mutasi
                    </span>
                </div>
                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                    @forelse($arusKas[$tipe]['transaksi'] as $t)
                    <div class="px-5 py-3 text-xs hover:bg-slate-50/80 transition flex items-start justify-between gap-3">
                        <div class="space-y-0.5 min-w-0 flex-1">
                            <span class="text-[11px] font-medium text-slate-400 block">{{ $t['tanggal'] }}</span>
                            <p class="font-medium text-slate-800 line-clamp-2 leading-snug">{{ $t['deskripsi'] }}</p>
                        </div>
                        <div class="text-right flex-shrink-0 pt-0.5">
                            <span class="font-bold inline-block {{ $t['tipe'] == 'penerimaan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t['tipe'] == 'penerimaan' ? '+' : '-' }} Rp {{ number_format($t['jumlah'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-10 text-center">
                        <p class="text-xs text-slate-400 font-medium">Tidak ada transaksi {{ strtolower($cfg['label']) }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
