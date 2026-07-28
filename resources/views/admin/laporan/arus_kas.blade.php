@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Laporan Arus Kas</h1>
                <p class="text-slate-500 text-sm mt-1">Pergerakan kas masuk dan keluar</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.arus_kas') }}" class="flex flex-col sm:flex-row gap-2.5 items-end">
                @if($usahas && $usahas->count() > 0)
                <div>
                    <label class="text-xs text-slate-700 font-semibold block mb-1">Usaha</label>
                    <select name="usaha_id" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        @foreach($usahas as $usahaItem)
                        <option value="{{ $usahaItem->id }}" {{ $usahaSelected == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="text-xs text-slate-700 font-semibold block mb-1">Tahun</label>
                    <select name="tahun" class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                        @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" @selected($tahun == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-[#005198] hover:bg-[#003d73] text-white text-sm px-4 py-2 rounded-xl transition-all font-semibold shadow-sm h-fit">Filter</button>
            </form>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-4 md:px-6 py-4 border-b border-slate-200">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-bold text-slate-900">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm font-semibold text-slate-600 tracking-wide mt-1">LAPORAN ARUS KAS</p>
                    <p class="text-xs text-slate-500 mt-0.5">Tahun {{ $tahun }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="space-y-4 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-4 shadow-sm">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Operasi</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Penerimaan</span>
                                    <span class="text-emerald-600 font-semibold">{{ number_format($arusKas['OPERASI']['penerimaan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-slate-200">
                                    <span class="text-slate-600">Pengeluaran</span>
                                    <span class="text-rose-600 font-semibold">{{ number_format($arusKas['OPERASI']['pengeluaran'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 font-semibold">
                                    <span class="text-slate-900">Arus Bersih</span>
                                    <span class="text-blue-700 font-bold">{{ number_format($arusKasBersihOperasi, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-4 shadow-sm">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Investasi</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Penerimaan</span>
                                    <span class="text-emerald-600 font-semibold">{{ number_format($arusKas['INVESTASI']['penerimaan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-slate-200">
                                    <span class="text-slate-600">Pengeluaran</span>
                                    <span class="text-rose-600 font-semibold">{{ number_format($arusKas['INVESTASI']['pengeluaran'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 font-semibold">
                                    <span class="text-slate-900">Arus Bersih</span>
                                    <span class="text-blue-700 font-bold">{{ number_format($arusKasBersihInvestasi, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-4 shadow-sm">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Pendanaan</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Penerimaan</span>
                                    <span class="text-emerald-600 font-semibold">{{ number_format($arusKas['PENDANAAN']['penerimaan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-slate-200">
                                    <span class="text-slate-600">Pengeluaran</span>
                                    <span class="text-rose-600 font-semibold">{{ number_format($arusKas['PENDANAAN']['pengeluaran'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 font-semibold">
                                    <span class="text-slate-900">Arus Bersih</span>
                                    <span class="text-blue-700 font-bold">{{ number_format($arusKasBersihPendanaan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50/80 border border-blue-200/80 rounded-xl p-4 shadow-sm flex items-center justify-between">
                            <span class="text-xs font-semibold text-blue-900">Total Arus Kas Bersih</span>
                            <span class="text-lg font-bold text-blue-700">{{ number_format($totalArusKasBersih, 0, ',', '.') }}</span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-3 flex justify-between">
                                <span class="text-slate-600 font-medium">Saldo Awal</span>
                                <span class="text-slate-900 font-semibold">{{ number_format($saldoAwalKas, 0, ',', '.') }}</span>
                            </div>
                            <div class="bg-emerald-50/80 border border-emerald-200/80 rounded-xl p-3 flex justify-between">
                                <span class="text-emerald-900 font-semibold">Saldo Akhir</span>
                                <span class="text-emerald-700 font-bold">{{ number_format($saldoAkhirKas, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(['OPERASI' => 'blue', 'INVESTASI' => 'amber', 'PENDANAAN' => 'purple'] as $tipe => $color)
                    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-200">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">{{ $tipe }}</h4>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            @forelse($arusKas[$tipe]['transaksi'] as $t)
                            <div class="px-4 py-2.5 text-xs flex justify-between hover:bg-slate-50 transition-colors">
                                <span class="text-slate-500">{{ $t['tanggal'] }}</span>
                                <span class="font-semibold {{ $t['tipe'] == 'penerimaan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $t['tipe'] == 'penerimaan' ? '+' : '-' }} {{ number_format($t['jumlah'], 0, ',', '.') }}
                                </span>
                            </div>
                            @empty
                            <div class="px-4 py-4 text-xs text-slate-400 text-center">Tidak ada transaksi</div>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
