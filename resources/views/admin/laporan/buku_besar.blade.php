@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Buku Besar</h1>
                <p class="text-slate-500 text-sm mt-1">Mutasi per akun dalam periode tertentu</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.buku_besar') }}" class="flex flex-wrap gap-2.5 items-end">
                @if($usahas->count() > 0)
                <div>
                    <label class="text-xs text-slate-700 font-semibold block mb-1">Usaha</label>
                    <select name="usaha_id" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        @foreach($usahas as $u)
                        <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="text-xs text-slate-700 font-semibold block mb-1">Akun</label>
                    <select name="akun_id" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        @foreach($akuns as $akun)
                        <option value="{{ $akun->id }}" {{ $akunSelected && $akunSelected->id == $akun->id ? 'selected' : '' }}>
                            {{ $akun->kode }} - {{ $akun->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-700 font-semibold block mb-1">Tahun</label>
                    <select name="tahun" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-700 font-semibold block mb-1">Bulan</label>
                    <select name="bulan" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @foreach($bulanList as $num => $nama)
                        <option value="{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($num, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-[#005198] hover:bg-[#003d73] text-white text-sm px-4 py-2 rounded-xl font-semibold shadow-sm transition-all h-fit">Filter</button>
            </form>
        </div>

        @if($akunSelected)
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-4 md:px-6 py-4 border-b border-slate-200">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-bold text-slate-900">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm font-semibold text-slate-600 tracking-wide mt-1">BUKU BESAR</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $akunSelected->kode }} - {{ $akunSelected->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $start_date->format('d M Y') }} s/d {{ $end_date->format('d M Y') }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-right">Debit</th>
                                <th class="px-4 py-3 text-right">Kredit</th>
                                <th class="px-4 py-3 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            <tr class="bg-slate-50/60 font-medium">
                                <td class="px-4 py-2.5 text-slate-400">-</td>
                                <td class="px-4 py-2.5 text-slate-500 italic">Saldo Awal</td>
                                <td class="px-4 py-2.5 text-right text-slate-400">-</td>
                                <td class="px-4 py-2.5 text-right text-slate-400">-</td>
                                <td class="px-4 py-2.5 text-right text-slate-900 font-semibold">{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                            </tr>
                            @forelse($bukuBesarData as $row)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $row['is_penyesuaian'] ? 'bg-amber-50/50' : '' }}">
                                <td class="px-4 py-2.5 text-slate-600">{{ $row['tanggal'] }}</td>
                                <td class="px-4 py-2.5 text-slate-800 font-medium">
                                    {{ $row['deskripsi'] }}
                                    @if($row['is_penyesuaian'])
                                    <span class="ml-1.5 px-1.5 py-0.5 bg-amber-100 text-amber-800 border border-amber-200/80 rounded text-[10px] font-bold uppercase">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-right text-slate-700">{{ $row['debit'] > 0 ? number_format($row['debit'], 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-700">{{ $row['kredit'] > 0 ? number_format($row['kredit'], 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold {{ $row['saldo'] >= 0 ? 'text-slate-900' : 'text-rose-600' }}">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Tidak ada mutasi pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(count($bukuBesarData) > 0)
                        <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-semibold text-slate-900">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-slate-700 text-xs uppercase tracking-wider">Total</td>
                                <td class="px-4 py-3 text-right text-slate-900 font-semibold">{{ number_format(array_sum(array_column($bukuBesarData, 'debit')), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-slate-900 font-semibold">{{ number_format(array_sum(array_column($bukuBesarData, 'kredit')), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ end($bukuBesarData)['saldo'] >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">{{ number_format(end($bukuBesarData)['saldo'], 0, ',', '.') }}</td>
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
