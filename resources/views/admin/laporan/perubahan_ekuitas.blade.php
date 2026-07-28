@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-4xl mx-auto">
    <div class="flex flex-col md:items-center gap-4 mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-slate-900">Laporan Perubahan Ekuitas</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $usaha->nama ?? '-' }}</p>
        </div>
        <form action="{{ route('admin.laporan.perubahan_ekuitas') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5">
            <select name="usaha_id" class="bg-white border border-slate-300 text-slate-900 rounded-xl text-sm px-3 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                @foreach($usahas as $u)
                <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $bulan_awal }}" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <span class="text-slate-500 text-xs font-medium">s/d</span>
                <input type="date" name="end_date" value="{{ $bulan_akhir }}" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <button type="submit" class="bg-[#005198] hover:bg-[#003d73] text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition-all">Filter</button>
        </form>
    </div>

    @php
        $subCeo = $modalAwalCeo + $setoranCeo;
        $subCto = $modalAwalCto + $setoranCto;
        $subBersama = $labaDitahan + $labaTahunBerjalan;
        $subTotal = $subCeo + $subCto + $subBersama;

        $priveTotal = $dividen + $priveCeo + $priveCto;

        $akhirCeo = $subCeo - $priveCeo;
        $akhirCto = $subCto - $priveCto;
        $akhirBersama = $subBersama - $dividen;
        $akhirTotal = $akhirCeo + $akhirCto + $akhirBersama;

        $fmt = fn($v) => ($v < 0 ? '(' . number_format(abs($v), 0, ',', '.') . ')' : number_format($v, 0, ',', '.'));
    @endphp

    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">CEO</th>
                        <th class="px-6 py-4 text-right">CTO</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Modal Awal Periode</td>
                        <td class="px-6 py-3.5 text-right text-slate-800 font-medium">{{ $fmt($modalAwalCeo) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-800 font-medium">{{ $fmt($modalAwalCto) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold">{{ $fmt($modalAwalCeo + $modalAwalCto) }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Setoran Tambahan Modal</td>
                        <td class="px-6 py-3.5 text-right text-slate-800 font-medium">{{ $fmt($setoranCeo) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-800 font-medium">{{ $fmt($setoranCto) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold">{{ $fmt($setoranCeo + $setoranCto) }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Laba Ditahan</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-900 font-semibold">{{ $fmt($labaDitahan) }}</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Laba (Rugi) Tahun Berjalan</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right font-semibold {{ $labaTahunBerjalan >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $fmt($labaTahunBerjalan) }}
                        </td>
                    </tr>

                    <tr class="bg-slate-50 border-y border-slate-200">
                        <td class="px-6 py-3.5 text-sm font-semibold text-slate-900">Sub Total</td>
                        <td class="px-6 py-3.5 text-sm text-right font-semibold text-slate-900">{{ $fmt($subCeo) }}</td>
                        <td class="px-6 py-3.5 text-sm text-right font-semibold text-slate-900">{{ $fmt($subCto) }}</td>
                        <td class="px-6 py-3.5 text-sm text-right font-bold text-slate-900">{{ $fmt($subTotal) }}</td>
                    </tr>

                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Dividen</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-slate-400">-</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-medium">({{ number_format($dividen, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5 text-slate-800 font-medium">Prive Pemilik</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-medium">({{ number_format($priveCeo, 0, ',', '.') }})</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-medium">({{ number_format($priveCto, 0, ',', '.') }})</td>
                        <td class="px-6 py-3.5 text-right text-rose-600 font-semibold">({{ number_format($priveCeo + $priveCto, 0, ',', '.') }})</td>
                    </tr>

                    <tr class="border-t-2 border-slate-200 bg-slate-50/50">
                        <td class="px-6 py-4 text-base font-bold text-slate-900">Modal Akhir Periode</td>
                        <td class="px-6 py-4 text-base text-right font-bold text-blue-700">{{ $fmt($akhirCeo) }}</td>
                        <td class="px-6 py-4 text-base text-right font-bold text-blue-700">{{ $fmt($akhirCto) }}</td>
                        <td class="px-6 py-4 text-base text-right font-bold text-blue-700">{{ $fmt($akhirTotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
