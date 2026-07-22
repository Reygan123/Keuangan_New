@extends('layouts.admin.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="flex flex-col md:items-center gap-4 mb-8">
        <div class=text-center>
            <h1 class="text-2xl font-bold text-white">Laporan Perubahan Ekuitas</h1>
            <p class="text-slate-400 text-sm">{{ $usaha->nama ?? '-' }}</p>
        </div>
        <form action="{{ route('admin.laporan.perubahan_ekuitas') }}" method="GET" class="flex items-center gap-3">
            <select name="usaha_id" class="bg-slate-800 border-slate-700 text-white rounded-lg text-sm px-3 py-2">
                @foreach($usahas as $u)
                <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
            <input type="date" name="start_date" value="{{ $bulan_awal }}" class="bg-slate-800 border-slate-700 text-white rounded-lg text-sm">
            <span class="text-slate-500">s/d</span>
            <input type="date" name="end_date" value="{{ $bulan_akhir }}" class="bg-slate-800 border-slate-700 text-white rounded-lg text-sm">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
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

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-800">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300">Keterangan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300 text-right">CEO</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300 text-right">CTO</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-300">Modal Awal Periode</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($modalAwalCeo) }}</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($modalAwalCto) }}</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($modalAwalCeo + $modalAwalCto) }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-300">Setoran Tambahan Modal</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($setoranCeo) }}</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($setoranCto) }}</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($setoranCeo + $setoranCto) }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-300">Laba Ditahan</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-500">-</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-500">-</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-200">{{ $fmt($labaDitahan) }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-300">Laba (Rugi) Tahun Berjalan</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-500">-</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-500">-</td>
                        <td class="px-6 py-3 text-sm text-right {{ $labaTahunBerjalan >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $fmt($labaTahunBerjalan) }}
                        </td>
                    </tr>

                    <tr class="bg-slate-800/40 border-y border-slate-700">
                        <td class="px-6 py-3 text-sm font-semibold text-white">Sub Total</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-white">{{ $fmt($subCeo) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-white">{{ $fmt($subCto) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-white">{{ $fmt($subTotal) }}</td>
                    </tr>

                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-300">Dividen</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-500">-</td>
                        <td class="px-6 py-3 text-sm text-right text-slate-500">-</td>
                        <td class="px-6 py-3 text-sm text-right text-rose-400">({{ number_format($dividen, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-300">Prive Pemilik</td>
                        <td class="px-6 py-3 text-sm text-right text-rose-400">({{ number_format($priveCeo, 0, ',', '.') }})</td>
                        <td class="px-6 py-3 text-sm text-right text-rose-400">({{ number_format($priveCto, 0, ',', '.') }})</td>
                        <td class="px-6 py-3 text-sm text-right text-rose-400">({{ number_format($priveCeo + $priveCto, 0, ',', '.') }})</td>
                    </tr>

                    <tr class="border-t-2 border-slate-700">
                        <td class="px-6 py-4 text-base font-bold text-white">Modal Akhir Periode</td>
                        <td class="px-6 py-4 text-base text-right font-bold text-blue-400">{{ $fmt($akhirCeo) }}</td>
                        <td class="px-6 py-4 text-base text-right font-bold text-blue-400">{{ $fmt($akhirCto) }}</td>
                        <td class="px-6 py-4 text-base text-right font-bold text-blue-400">{{ $fmt($akhirTotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection