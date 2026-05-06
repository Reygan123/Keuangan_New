@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-950 p-3 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-50">Buku Besar</h1>
                <p class="text-xs md:text-sm text-slate-400 mt-1">Mutasi per akun dalam periode tertentu</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.buku_besar') }}" class="flex flex-wrap gap-2 items-end">
                @if($usahas->count() > 0)
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Usaha</label>
                    <select name="usaha_id" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg focus:outline-none focus:border-blue-500">
                        @foreach($usahas as $u)
                        <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Akun</label>
                    <select name="akun_id" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg focus:outline-none focus:border-blue-500">
                        @foreach($akuns as $akun)
                        <option value="{{ $akun->id }}" {{ $akunSelected && $akunSelected->id == $akun->id ? 'selected' : '' }}>
                            {{ $akun->kode }} - {{ $akun->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Tahun</label>
                    <select name="tahun" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg focus:outline-none focus:border-blue-500">
                        @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Bulan</label>
                    <select name="bulan" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Semua Bulan</option>
                        @foreach($bulanList as $num => $nama)
                        <option value="{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($num, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg font-semibold h-fit">Filter</button>
            </form>
        </div>

        @if($akunSelected)
        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-slate-700 px-4 md:px-6 py-4 border-b border-slate-600">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-semibold text-slate-100">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm text-slate-300 mt-1">BUKU BESAR</p>
                    <p class="text-xs text-slate-400">{{ $akunSelected->kode }} - {{ $akunSelected->name }}</p>
                    <p class="text-xs text-slate-400">{{ $start_date->format('d M Y') }} s/d {{ $end_date->format('d M Y') }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-700 border-b border-slate-600">
                                <th class="px-4 py-3 text-left font-semibold text-slate-200">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-200">Keterangan</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Debit</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Kredit</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <tr class="bg-slate-700/30">
                                <td class="px-4 py-2 text-slate-400">-</td>
                                <td class="px-4 py-2 text-slate-400 italic">Saldo Awal</td>
                                <td class="px-4 py-2 text-right text-slate-400">-</td>
                                <td class="px-4 py-2 text-right text-slate-400">-</td>
                                <td class="px-4 py-2 text-right text-slate-200 font-semibold">{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                            </tr>
                            @forelse($bukuBesarData as $row)
                            <tr class="hover:bg-slate-700/40 transition-colors {{ $row['is_penyesuaian'] ? 'bg-amber-950/20' : '' }}">
                                <td class="px-4 py-2 text-slate-300">{{ $row['tanggal'] }}</td>
                                <td class="px-4 py-2 text-slate-300">
                                    {{ $row['deskripsi'] }}
                                    @if($row['is_penyesuaian'])
                                    <span class="ml-1 text-amber-400 text-[10px] font-bold uppercase">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right text-slate-300">{{ $row['debit'] > 0 ? number_format($row['debit'], 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-2 text-right text-slate-300">{{ $row['kredit'] > 0 ? number_format($row['kredit'], 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-2 text-right font-semibold {{ $row['saldo'] >= 0 ? 'text-slate-100' : 'text-red-400' }}">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">Tidak ada mutasi pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(count($bukuBesarData) > 0)
                        <tfoot class="bg-slate-700/50 border-t border-slate-600">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-slate-200 font-semibold text-xs">Total</td>
                                <td class="px-4 py-3 text-right text-slate-100 font-semibold text-xs">{{ number_format(array_sum(array_column($bukuBesarData, 'debit')), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-slate-100 font-semibold text-xs">{{ number_format(array_sum(array_column($bukuBesarData, 'kredit')), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold text-xs {{ end($bukuBesarData)['saldo'] >= 0 ? 'text-green-400' : 'text-red-400' }}">{{ number_format(end($bukuBesarData)['saldo'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
