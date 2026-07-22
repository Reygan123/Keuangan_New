@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-full mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-50 mb-1">Neraca Lajur</h1>
            <p class="text-slate-400 text-sm">Kertas kerja penyesuaian periode akuntansi</p>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 shadow-lg mb-6">
            <div class="p-4 border-b border-slate-700">
                <form method="GET" action="{{ route('admin.laporan.neraca_lajur') }}" class="flex flex-wrap gap-4 items-end">
                    @if($usahas->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Usaha</label>
                        <select name="usaha_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                            @foreach($usahas as $usaha)
                            <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tahun</label>
                        <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 shadow-lg">
            <div class="p-4 border-b border-slate-700 text-center">
                <p class="text-slate-50 font-bold text-lg uppercase">{{ $usaha->nama }}</p>
                <p class="text-slate-300 font-semibold">NERACA LAJUR</p>
                <p class="text-slate-400 text-sm">31 Desember {{ $tahun }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-700">
                            <th rowspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600 w-16">No</th>
                            <th rowspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600 min-w-48">Nama Akun</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600">Neraca Saldo</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600">Jurnal Penyesuaian</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600">Neraca Saldo Setelah Disesuaikan</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600">Laba Rugi</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-200 border border-slate-600">Neraca</th>
                        </tr>
                        <tr class="bg-slate-700">
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-300 border border-slate-600 min-w-32">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($rows as $row)
                        <tr class="hover:bg-slate-700/40 transition-colors">
                            <td class="px-3 py-2 text-center text-slate-300 border border-slate-700 font-mono">{{ $row['kode'] }}</td>
                            <td class="px-3 py-2 text-slate-200 border border-slate-700">{{ $row['name'] }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['ns_debit'] > 0 ? number_format($row['ns_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['ns_kredit'] > 0 ? number_format($row['ns_kredit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['jp_debit'] > 0 ? number_format($row['jp_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['jp_kredit'] > 0 ? number_format($row['jp_kredit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['nssd'] > 0 ? number_format($row['nssd'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['nssk'] > 0 ? number_format($row['nssk'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['lr_debit'] > 0 ? number_format($row['lr_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['lr_kredit'] > 0 ? number_format($row['lr_kredit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['n_debit'] > 0 ? number_format($row['n_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $row['n_kredit'] > 0 ? number_format($row['n_kredit'], 0, ',', '.') : '' }}</td>
                        </tr>
                        @endforeach

                        <tr class="bg-slate-700/50 font-semibold">
                            <td colspan="2" class="px-3 py-2 text-slate-200 border border-slate-600">Jumlah</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['ns_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['ns_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['jp_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['jp_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['nssd'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['nssk'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['lr_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['lr_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['n_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['n_kredit'], 0, ',', '.') }}</td>
                        </tr>

                        @if($labaUsaha != 0)
                        <tr class="hover:bg-slate-700/40 transition-colors">
                            <td colspan="2" class="px-3 py-2 text-slate-300 border border-slate-700">
                                {{ $labaUsaha >= 0 ? 'Laba Usaha' : 'Rugi Usaha' }}
                            </td>
                            <td colspan="6" class="border border-slate-700"></td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $labaUsaha >= 0 ? number_format($labaUsaha, 0, ',', '.') : '' }}</td>
                            <td class="border border-slate-700"></td>
                            <td class="border border-slate-700"></td>
                            <td class="px-3 py-2 text-right text-slate-300 border border-slate-700">{{ $labaUsaha >= 0 ? number_format($labaUsaha, 0, ',', '.') : '' }}</td>
                        </tr>
                        @endif

                        <tr class="bg-slate-700 font-bold">
                            <td colspan="2" class="px-3 py-2 text-slate-100 border border-slate-600"></td>
                            <td colspan="6" class="border border-slate-600"></td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['lr_debit'] + max(0, $labaUsaha), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['lr_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['n_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-100 border border-slate-600">{{ number_format($totals['n_kredit'] + max(0, $labaUsaha), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
