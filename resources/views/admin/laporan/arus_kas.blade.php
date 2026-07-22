@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-950 p-3 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-50">Laporan Arus Kas</h1>
                <p class="text-xs md:text-sm text-slate-400 mt-1">Pergerakan kas masuk dan keluar</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.arus_kas') }}" class="flex flex-col sm:flex-row gap-2 items-end">
                @if($usahas && $usahas->count() > 0)
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Usaha</label>
                    <select name="usaha_id" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                        @foreach($usahas as $usahaItem)
                        <option value="{{ $usahaItem->id }}" {{ $usahaSelected == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Tahun</label>
                    <select name="tahun" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                        @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" @selected($tahun == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-colors font-semibold h-fit">Filter</button>
            </form>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-slate-700 px-4 md:px-6 py-4 border-b border-slate-600">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-semibold text-slate-100">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm text-slate-300 mt-2">LAPORAN ARUS KAS</p>
                    <p class="text-xs text-slate-400">Tahun {{ $tahun }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="space-y-4 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-750 border border-slate-700 rounded-lg p-4">
                            <h3 class="text-xs font-bold text-slate-200 uppercase mb-3">Operasi</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Penerimaan</span>
                                    <span class="text-green-400 font-semibold">{{ number_format($arusKas['OPERASI']['penerimaan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-slate-700">
                                    <span class="text-slate-400">Pengeluaran</span>
                                    <span class="text-red-400 font-semibold">{{ number_format($arusKas['OPERASI']['pengeluaran'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 font-semibold">
                                    <span class="text-slate-300">Arus Bersih</span>
                                    <span class="text-blue-400">{{ number_format($arusKasBersihOperasi, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-750 border border-slate-700 rounded-lg p-4">
                            <h3 class="text-xs font-bold text-slate-200 uppercase mb-3">Investasi</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Penerimaan</span>
                                    <span class="text-green-400 font-semibold">{{ number_format($arusKas['INVESTASI']['penerimaan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-slate-700">
                                    <span class="text-slate-400">Pengeluaran</span>
                                    <span class="text-red-400 font-semibold">{{ number_format($arusKas['INVESTASI']['pengeluaran'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 font-semibold">
                                    <span class="text-slate-300">Arus Bersih</span>
                                    <span class="text-blue-400">{{ number_format($arusKasBersihInvestasi, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-750 border border-slate-700 rounded-lg p-4">
                            <h3 class="text-xs font-bold text-slate-200 uppercase mb-3">Pendanaan</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Penerimaan</span>
                                    <span class="text-green-400 font-semibold">{{ number_format($arusKas['PENDANAAN']['penerimaan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-slate-700">
                                    <span class="text-slate-400">Pengeluaran</span>
                                    <span class="text-red-400 font-semibold">{{ number_format($arusKas['PENDANAAN']['pengeluaran'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 font-semibold">
                                    <span class="text-slate-300">Arus Bersih</span>
                                    <span class="text-blue-400">{{ number_format($arusKasBersihPendanaan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-950 to-blue-900 border border-blue-800 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-semibold text-blue-100">Total Arus Kas Bersih</span>
                                <span class="text-lg font-bold text-blue-300">{{ number_format($totalArusKasBersih, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="bg-slate-750 border border-slate-700 rounded-lg p-3 flex justify-between">
                                <span class="text-slate-300">Saldo Awal</span>
                                <span class="text-slate-100 font-semibold">{{ number_format($saldoAwalKas, 0, ',', '.') }}</span>
                            </div>
                            <div class="bg-green-950 border border-green-800 rounded-lg p-3 flex justify-between">
                                <span class="text-green-100 font-semibold">Saldo Akhir</span>
                                <span class="text-green-300 font-bold">{{ number_format($saldoAkhirKas, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(['OPERASI' => 'blue', 'INVESTASI' => 'amber', 'PENDANAAN' => 'purple'] as $tipe => $color)
                    <div class="bg-slate-750 border border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                            <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">{{ $tipe }}</h4>
                        </div>
                        <div class="divide-y divide-slate-700 max-h-64 overflow-y-auto">
                            @forelse($arusKas[$tipe]['transaksi'] as $t)
                            <div class="px-4 py-2 text-xs flex justify-between hover:bg-slate-700 transition-colors">
                                <span class="text-slate-400">{{ $t['tanggal'] }}</span>
                                <span class="font-semibold {{ $t['tipe'] == 'penerimaan' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $t['tipe'] == 'penerimaan' ? '+' : '-' }} {{ number_format($t['jumlah'], 0, ',', '.') }}
                                </span>
                            </div>
                            @empty
                            <div class="px-4 py-3 text-xs text-slate-500 text-center">Tidak ada transaksi</div>
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
