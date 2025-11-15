@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-6 md:py-8">
        <div class="max-w-4xl mx-auto px-4 md:px-6 lg:px-8">

            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Laporan Perubahan Ekuitas</h1>
                <p class="text-xs md:text-sm text-slate-400">Dashboard / Laporan Keuangan / Perubahan Ekuitas</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-lg overflow-hidden">

                <div class="p-4 md:p-6 border-b border-slate-800">
                    <form action="{{ route('admin.laporan.perubahan_ekuitas') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                            <div>
                                <label for="start_date" class="block text-xs md:text-sm font-medium text-slate-300 mb-1.5">Periode Awal</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $bulan_awal }}" class="w-full px-3 py-2 text-xs md:text-sm bg-slate-800 border border-slate-700 text-white rounded focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                            </div>
                            <div>
                                <label for="end_date" class="block text-xs md:text-sm font-medium text-slate-300 mb-1.5">Periode Akhir</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $bulan_akhir }}" class="w-full px-3 py-2 text-xs md:text-sm bg-slate-800 border border-slate-700 text-white rounded focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                            </div>
                            <div class="md:col-span-2 lg:col-span-2 flex gap-2">
                                <button type="submit" class="flex-1 px-4 py-2 text-xs md:text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    Tampilkan
                                </button>
                                <a href="{{ route('admin.laporan.perubahan_ekuitas') }}" class="flex-1 px-4 py-2 text-xs md:text-sm font-medium bg-slate-800 text-slate-300 rounded hover:bg-slate-700 transition-colors">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-4 md:p-6">
                    <div class="mb-4 md:mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-1">Laporan Perubahan Ekuitas</h2>
                        <p class="text-xs md:text-sm text-slate-400">
                            Periode: {{ date('d F Y', strtotime($bulan_awal)) }} s/d {{ date('d F Y', strtotime($bulan_akhir)) }}
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs md:text-sm">
                            <thead>
                                <tr class="bg-slate-800/50 border-b border-slate-700">
                                    <th class="px-3 md:px-4 py-3 text-left font-semibold text-slate-300">Uraian</th>
                                    <th class="px-3 md:px-4 py-3 text-right font-semibold text-slate-300">Jumlah (IDR)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">

                                <tr class="bg-blue-900/20">
                                    <td class="px-3 md:px-4 py-2 md:py-3 font-bold text-blue-300">MODAL PEMILIK DI AWAL ({{ date('d F Y', strtotime($bulan_awal)) }})</td>
                                    <td class="px-3 md:px-4 py-2 md:py-3 text-right"></td>
                                </tr>
                                @foreach ($detailModalAwal as $item)
                                    <tr class="hover:bg-slate-800/30">
                                        <td class="px-3 md:px-4 py-1 md:py-2 pl-6 md:pl-8 text-slate-300">{{ $item['name'] }}</td>
                                        <td class="px-3 md:px-4 py-1 md:py-2 text-right text-slate-300">{{ number_format($item['value'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-slate-800/50">
                                    <td class="px-3 md:px-4 py-2 font-bold text-slate-300">TOTAL MODAL AWAL</td>
                                    <td class="px-3 md:px-4 py-2 text-right font-bold text-slate-200">{{ number_format($modalAwal, 2, ',', '.') }}</td>
                                </tr>

                                <tr class="bg-emerald-900/20">
                                    <td class="px-3 md:px-4 py-2 md:py-3 font-bold text-emerald-300">PENAMBAHAN/PENINGKATAN EKUITAS</td>
                                    <td class="px-3 md:px-4 py-2 md:py-3 text-right"></td>
                                </tr>
                                <tr class="hover:bg-slate-800/30">
                                    <td class="px-3 md:px-4 py-1 md:py-2 pl-6 md:pl-8 text-slate-300">Laba Tahun Berjalan</td>
                                    <td class="px-3 md:px-4 py-1 md:py-2 text-right text-emerald-400">{{ number_format($labaTahunBerjalan, 2, ',', '.') }}</td>
                                </tr>
                                @foreach ($detailPenambahan as $item)
                                    <tr class="hover:bg-slate-800/30">
                                        <td class="px-3 md:px-4 py-1 md:py-2 pl-6 md:pl-8 text-slate-300">{{ $item['name'] }}</td>
                                        <td class="px-3 md:px-4 py-1 md:py-2 text-right text-emerald-400">{{ number_format($item['value'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                <tr class="bg-red-900/20">
                                    <td class="px-3 md:px-4 py-2 md:py-3 font-bold text-red-300">PENGURANGAN EKUITAS</td>
                                    <td class="px-3 md:px-4 py-2 md:py-3 text-right"></td>
                                </tr>
                                @foreach ($detailPengurangan as $item)
                                    <tr class="hover:bg-slate-800/30">
                                        <td class="px-3 md:px-4 py-1 md:py-2 pl-6 md:pl-8 text-slate-300">{{ $item['name'] }}</td>
                                        <td class="px-3 md:px-4 py-1 md:py-2 text-right text-red-400">({{ number_format($item['value'], 2, ',', '.') }})</td>
                                    </tr>
                                @endforeach

                                <tr class="bg-slate-800/50 border-t-2 border-slate-700">
                                    <td class="px-3 md:px-4 py-2 md:py-3 font-bold text-slate-300">PENAMBAHAN/(PENGURANGAN) EKUITAS BERSIH</td>
                                    <td class="px-3 md:px-4 py-2 md:py-3 text-right font-bold @if ($perubahanBersih >= 0) text-emerald-400 @else text-red-400 @endif">
                                        {{ number_format($perubahanBersih, 2, ',', '.') }}
                                    </td>
                                </tr>

                                <tr class="bg-slate-800/70 border-t-4 border-double border-slate-600">
                                    <td class="px-3 md:px-4 py-3 font-bold text-slate-200">MODAL PEMILIK DI AKHIR ({{ date('d F Y', strtotime($bulan_akhir)) }})</td>
                                    <td class="px-3 md:px-4 py-3 text-right text-lg font-bold text-slate-100">{{ number_format($modalAkhir, 2, ',', '.') }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
