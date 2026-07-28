@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-full mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-1">Neraca Lajur</h1>
            <p class="text-slate-500 text-sm">Kertas kerja penyesuaian periode akuntansi</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-6">
            <div class="p-4 border-b border-slate-200">
                <form method="GET" action="{{ route('admin.laporan.neraca_lajur') }}" class="flex flex-wrap gap-4 items-end">
                    @if($usahas->count() > 0)
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Usaha</label>
                        <select name="usaha_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            @foreach($usahas as $usaha)
                            <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun</label>
                        <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 text-center bg-slate-50">
                <p class="text-slate-900 font-bold text-lg uppercase">{{ $usaha->nama }}</p>
                <p class="text-slate-700 font-semibold text-sm">NERACA LAJUR</p>
                <p class="text-slate-500 text-xs">31 Desember {{ $tahun }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100/80">
                            <th rowspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200 w-16">No</th>
                            <th rowspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200 min-w-48">Nama Akun</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200">Neraca Saldo</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200">Jurnal Penyesuaian</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200">Neraca Saldo Setelah Disesuaikan</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200">Laba Rugi</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold text-slate-700 border border-slate-200">Neraca</th>
                        </tr>
                        <tr class="bg-slate-100/80">
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Kredit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Debit</th>
                            <th class="px-3 py-2 text-center font-semibold text-slate-600 border border-slate-200 min-w-32">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rows as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-3 py-2 text-center text-slate-600 border border-slate-200 font-mono">{{ $row['kode'] }}</td>
                            <td class="px-3 py-2 text-slate-800 border border-slate-200 font-medium">{{ $row['name'] }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['ns_debit'] > 0 ? number_format($row['ns_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['ns_kredit'] > 0 ? number_format($row['ns_kredit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['jp_debit'] > 0 ? number_format($row['jp_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['jp_kredit'] > 0 ? number_format($row['jp_kredit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['nssd'] > 0 ? number_format($row['nssd'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['nssk'] > 0 ? number_format($row['nssk'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['lr_debit'] > 0 ? number_format($row['lr_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['lr_kredit'] > 0 ? number_format($row['lr_kredit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['n_debit'] > 0 ? number_format($row['n_debit'], 0, ',', '.') : '' }}</td>
                            <td class="px-3 py-2 text-right text-slate-700 border border-slate-200">{{ $row['n_kredit'] > 0 ? number_format($row['n_kredit'], 0, ',', '.') : '' }}</td>
                        </tr>
                        @endforeach

                        <tr class="bg-slate-50 font-semibold">
                            <td colspan="2" class="px-3 py-2 text-slate-900 border border-slate-200">Jumlah</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['ns_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['ns_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['jp_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['jp_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['nssd'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['nssk'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['lr_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['lr_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['n_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['n_kredit'], 0, ',', '.') }}</td>
                        </tr>

                        @if($labaUsaha != 0)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td colspan="2" class="px-3 py-2 text-slate-800 font-medium border border-slate-200">
                                {{ $labaUsaha >= 0 ? 'Laba Usaha' : 'Rugi Usaha' }}
                            </td>
                            <td colspan="6" class="border border-slate-200"></td>
                            <td class="px-3 py-2 text-right text-slate-800 border border-slate-200">{{ $labaUsaha >= 0 ? number_format($labaUsaha, 0, ',', '.') : '' }}</td>
                            <td class="border border-slate-200"></td>
                            <td class="border border-slate-200"></td>
                            <td class="px-3 py-2 text-right text-slate-800 border border-slate-200">{{ $labaUsaha >= 0 ? number_format($labaUsaha, 0, ',', '.') : '' }}</td>
                        </tr>
                        @endif

                        <tr class="bg-slate-100 font-bold">
                            <td colspan="2" class="px-3 py-2 text-slate-900 border border-slate-200"></td>
                            <td colspan="6" class="border border-slate-200"></td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['lr_debit'] + max(0, $labaUsaha), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['lr_kredit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['n_debit'], 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right text-slate-900 border border-slate-200">{{ number_format($totals['n_kredit'] + max(0, $labaUsaha), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
