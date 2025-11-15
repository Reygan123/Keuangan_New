@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-950 p-3 md:p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-50">Laporan Laba Rugi</h1>
                <p class="text-xs md:text-sm text-slate-400 mt-1">Hasil operasional perusahaan</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.laba_rugi') }}" class="flex flex-col sm:flex-row gap-2">
                <input type="date" name="start_date" value="{{ $start_date_str }}" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors">
                <input type="date" name="end_date" value="{{ $end_date_str }}" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-colors font-semibold">Filter</button>
            </form>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-slate-700 px-4 md:px-6 py-4 border-b border-slate-600">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-semibold text-slate-100">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm text-slate-300 mt-2">LAPORAN LABA RUGI</p>
                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($start_date_str)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date_str)->format('d M Y') }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="max-w-2xl mx-auto space-y-3">
                    <div class="bg-slate-750 border border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                            <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Penjualan Bersih</h3>
                        </div>
                        <div class="divide-y divide-slate-700">
                            @foreach($labaRugiItems['PENJUALAN_BERSIH'] as $pendapatan)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                <span class="text-slate-300">{{ $pendapatan['name'] }}</span>
                                <span class="text-slate-100 font-medium">{{ number_format($pendapatan['mutasi_bersih'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-700 flex justify-between text-xs font-semibold">
                                <span class="text-slate-200">Total</span>
                                <span class="text-slate-100">{{ number_format($totalPenjualanBersih, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-750 border border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                            <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">HPP</h3>
                        </div>
                        <div class="divide-y divide-slate-700">
                            @foreach($labaRugiItems['HPP'] as $hpp)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                <span class="text-slate-300">{{ $hpp['name'] }}</span>
                                <span class="text-red-400 font-medium">({{ number_format($hpp['mutasi_bersih'], 0, ',', '.') }})</span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-700 flex justify-between text-xs font-semibold">
                                <span class="text-slate-200">Total</span>
                                <span class="text-red-400">({{ number_format($totalHpp, 0, ',', '.') }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-950 border border-blue-800 rounded-lg px-4 py-3">
                        <div class="flex justify-between">
                            <span class="text-xs font-semibold text-blue-100">Laba Kotor</span>
                            <span class="text-sm font-bold text-blue-300">{{ number_format($labaKotor, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-750 border border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                            <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Beban Operasi</h3>
                        </div>
                        <div class="divide-y divide-slate-700">
                            @foreach($labaRugiItems['BEBAN_OPERASI'] as $beban)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                <span class="text-slate-300">{{ $beban['name'] }}</span>
                                <span class="text-red-400 font-medium">({{ number_format($beban['mutasi_bersih'], 0, ',', '.') }})</span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-700 flex justify-between text-xs font-semibold">
                                <span class="text-slate-200">Total</span>
                                <span class="text-red-400">({{ number_format($totalBebanOperasi, 0, ',', '.') }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-950 border border-indigo-800 rounded-lg px-4 py-3">
                        <div class="flex justify-between">
                            <span class="text-xs font-semibold text-indigo-100">Laba Operasi</span>
                            <span class="text-sm font-bold text-indigo-300">{{ number_format($labaOperasi, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-750 border border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                            <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Pendapatan & Beban Lain</h3>
                        </div>
                        <div class="divide-y divide-slate-700">
                            @foreach($labaRugiItems['PENDAPATAN_BEBAN_LAIN'] as $item)
                            <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                <span class="text-slate-300">{{ $item['name'] }}</span>
                                <span class="{{ $item['klasifikasi'] == 'PENDAPATAN' ? 'text-green-400' : 'text-red-400' }} font-medium">
                                    {{ $item['klasifikasi'] == 'PENDAPATAN' ? '+' : '-' }} {{ number_format($item['mutasi_bersih'], 0, ',', '.') }}
                                </span>
                            </div>
                            @endforeach
                            <div class="px-4 py-2 bg-slate-700 flex justify-between text-xs font-semibold">
                                <span class="text-slate-200">Neto</span>
                                <span class="text-slate-100">{{ number_format($pendapatanBebanLainBersih, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-950 border border-green-800 rounded-lg px-4 py-3">
                        <div class="flex justify-between">
                            <span class="text-xs font-semibold text-green-100">LABA BERSIH</span>
                            <span class="text-sm font-bold text-green-300">{{ number_format($labaBersih, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
