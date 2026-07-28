@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Laporan Laba Rugi</h1>
                <p class="text-slate-500 text-sm mt-1">Hasil operasional perusahaan</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.laba_rugi') }}" class="flex flex-col sm:flex-row gap-2.5">
                @if($usahas && $usahas->count() > 0)
                <select name="usaha_id" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                    @foreach($usahas as $usahaItem)
                    <option value="{{ $usahaItem->id }}" {{ $usahaSelected == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                    @endforeach
                </select>
                @endif
                <input type="date" name="start_date" value="{{ $start_date_str }}" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <input type="date" name="end_date" value="{{ $end_date_str }}" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <button type="submit" class="bg-[#005198] hover:bg-[#003d73] text-white text-sm px-4 py-2 rounded-xl transition-all font-semibold shadow-sm">Filter</button>
            </form>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-4 md:px-6 py-4 border-b border-slate-200">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-bold text-slate-900">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm font-semibold text-slate-600 tracking-wide mt-1">LAPORAN LABA RUGI</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($start_date_str)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date_str)->format('d M Y') }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="max-w-2xl mx-auto space-y-3">
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200/80">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Penjualan Bersih</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($labaRugiItems['PENJUALAN_BERSIH'] as $pendapatan)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                <span class="text-slate-700">{{ $pendapatan['name'] }}</span>
                                <span class="text-slate-900 font-medium">{{ number_format($pendapatan['mutasi_bersih'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-100/60 flex justify-between text-xs font-semibold">
                                <span class="text-slate-800">Total</span>
                                <span class="text-slate-900">{{ number_format($totalPenjualanBersih, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200/80">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide">HPP</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($labaRugiItems['HPP'] as $hpp)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                <span class="text-slate-700">{{ $hpp['name'] }}</span>
                                <span class="text-rose-600 font-medium">({{ number_format($hpp['mutasi_bersih'], 0, ',', '.') }})</span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-100/60 flex justify-between text-xs font-semibold">
                                <span class="text-slate-800">Total</span>
                                <span class="text-rose-600">({{ number_format($totalHpp, 0, ',', '.') }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50/80 border border-blue-200/80 rounded-xl px-4 py-3 shadow-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-blue-900">Laba Kotor</span>
                            <span class="text-sm font-bold text-blue-700">{{ number_format($labaKotor, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200/80">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Beban Operasi</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($labaRugiItems['BEBAN_OPERASI'] as $beban)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                <span class="text-slate-700">{{ $beban['name'] }}</span>
                                <span class="text-rose-600 font-medium">({{ number_format($beban['mutasi_bersih'], 0, ',', '.') }})</span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-100/60 flex justify-between text-xs font-semibold">
                                <span class="text-slate-800">Total</span>
                                <span class="text-rose-600">({{ number_format($totalBebanOperasi, 0, ',', '.') }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-50/80 border border-indigo-200/80 rounded-xl px-4 py-3 shadow-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-indigo-900">Laba Operasi</span>
                            <span class="text-sm font-bold text-indigo-700">{{ number_format($labaOperasi, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200/80">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Pendapatan & Beban Lain</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($labaRugiItems['PENDAPATAN_BEBAN_LAIN'] as $item)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                <span class="text-slate-700">{{ $item['name'] }}</span>
                                <span class="{{ $item['klasifikasi'] == 'PENDAPATAN' ? 'text-emerald-600' : 'text-rose-600' }} font-medium">
                                    {{ $item['klasifikasi'] == 'PENDAPATAN' ? '+' : '-' }} {{ number_format($item['mutasi_bersih'], 0, ',', '.') }}
                                </span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-100/60 flex justify-between text-xs font-semibold">
                                <span class="text-slate-800">Neto</span>
                                <span class="text-slate-900">{{ number_format($pendapatanBebanLainBersih, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-50/80 border border-emerald-200/80 rounded-xl px-4 py-3 shadow-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-emerald-900">LABA BERSIH</span>
                            <span class="text-sm font-bold text-emerald-700">{{ number_format($labaBersih, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
