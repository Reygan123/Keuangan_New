@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-950 p-3 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-50">Laporan Neraca</h1>
                <p class="text-xs md:text-sm text-slate-400 mt-1">Posisi aset, kewajiban, dan ekuitas</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.neraca') }}" class="flex gap-2 items-end">
                <div>
                    <label class="text-xs text-slate-300 block mb-2">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                        @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" @selected($tahun == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-slate-700 px-4 md:px-6 py-4 border-b border-slate-600">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-semibold text-slate-100">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm text-slate-300 mt-2">LAPORAN NERACA</p>
                    <p class="text-xs text-slate-400">31 Desember {{ $tahun }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-100 bg-slate-700 px-4 py-2 rounded-lg">ASET</h3>

                        <div class="bg-slate-750 rounded-lg border border-slate-700 overflow-hidden">
                            <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                                <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Aset Lancar</h4>
                            </div>
                            <div class="divide-y divide-slate-700">
                                @foreach($neracaItems['ASET_LANCAR'] as $aset)
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">{{ $aset['name'] }}</span>
                                    <span class="text-slate-100 font-medium">{{ number_format($aset['saldo_akhir'], 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                <div class="px-4 py-2 flex justify-between bg-slate-700 text-xs font-semibold">
                                    <span class="text-slate-200">Total</span>
                                    <span class="text-slate-100">{{ number_format($totalAsetLancar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-750 rounded-lg border border-slate-700 overflow-hidden">
                            <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                                <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Aset Tetap (Bruto)</h4>
                            </div>
                            <div class="divide-y divide-slate-700">
                                @foreach($neracaItems['ASET_TETAP_BRUTO'] as $aset)
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">{{ $aset['name'] }}</span>
                                    <span class="text-slate-100 font-medium">{{ number_format($aset['saldo_akhir'], 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                <div class="px-4 py-2 flex justify-between bg-slate-700 text-xs font-semibold">
                                    <span class="text-slate-200">Total</span>
                                    <span class="text-slate-100">{{ number_format($totalAsetTetapBruto, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-750 rounded-lg border border-slate-700 overflow-hidden">
                            <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                                <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Akumulasi Penyusutan</h4>
                            </div>
                            <div class="divide-y divide-slate-700">
                                @foreach($neracaItems['AKUMULASI_PENYUSUTAN'] as $akumulasi)
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">{{ $akumulasi['name'] }}</span>
                                    <span class="text-red-400 font-medium">({{ number_format(abs($akumulasi['saldo_akhir']), 0, ',', '.') }})</span>
                                </div>
                                @endforeach
                                <div class="px-4 py-2 flex justify-between bg-slate-700 text-xs font-semibold">
                                    <span class="text-slate-200">Total</span>
                                    <span class="text-red-400">({{ number_format(abs($totalAkumulasiPenyusutan), 0, ',', '.') }})</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-950 border border-blue-800 rounded-lg px-4 py-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-blue-100">Total Aset Tetap (Neto)</span>
                                <span class="text-sm font-bold text-blue-300">{{ number_format($totalAsetTetapNeto, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="bg-green-950 border border-green-800 rounded-lg px-4 py-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-green-100">TOTAL ASET</span>
                                <span class="text-sm font-bold text-green-300">{{ number_format($totalAset, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-100 bg-slate-700 px-4 py-2 rounded-lg">KEWAJIBAN & EKUITAS</h3>

                        <div class="bg-slate-750 rounded-lg border border-slate-700 overflow-hidden">
                            <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                                <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Kewajiban Lancar</h4>
                            </div>
                            <div class="divide-y divide-slate-700">
                                @foreach($neracaItems['KEWAJIBAN_LANCAR'] as $kewajiban)
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">{{ $kewajiban['name'] }}</span>
                                    <span class="text-slate-100 font-medium">{{ number_format($kewajiban['saldo_akhir'], 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                <div class="px-4 py-2 flex justify-between bg-slate-700 text-xs font-semibold">
                                    <span class="text-slate-200">Total</span>
                                    <span class="text-slate-100">{{ number_format($totalKewajibanLancar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-750 rounded-lg border border-slate-700 overflow-hidden">
                            <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                                <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Kewajiban Jangka Panjang</h4>
                            </div>
                            <div class="divide-y divide-slate-700">
                                @foreach($neracaItems['KEWAJIBAN_PANJANG'] as $kewajiban)
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">{{ $kewajiban['name'] }}</span>
                                    <span class="text-slate-100 font-medium">{{ number_format($kewajiban['saldo_akhir'], 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                <div class="px-4 py-2 flex justify-between bg-slate-700 text-xs font-semibold">
                                    <span class="text-slate-200">Total</span>
                                    <span class="text-slate-100">{{ number_format($totalKewajibanPanjang, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-950 border border-orange-800 rounded-lg px-4 py-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-orange-100">Total Kewajiban</span>
                                <span class="text-sm font-bold text-orange-300">{{ number_format($totalKewajiban, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="bg-slate-750 rounded-lg border border-slate-700 overflow-hidden">
                            <div class="bg-slate-700 px-4 py-2 border-b border-slate-600">
                                <h4 class="text-xs font-semibold text-slate-200 uppercase tracking-wide">Ekuitas</h4>
                            </div>
                            <div class="divide-y divide-slate-700">
                                @foreach($neracaItems['EKUITAS'] as $ekuitas)
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">{{ $ekuitas['name'] }}</span>
                                    <span class="text-slate-100 font-medium">{{ number_format($ekuitas['saldo_akhir'], 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                <div class="px-4 py-2 flex justify-between hover:bg-slate-700 transition-colors text-xs">
                                    <span class="text-slate-300">Laba Tahun Berjalan</span>
                                    <span class="text-slate-100 font-medium">{{ number_format($labaBersih, 0, ',', '.') }}</span>
                                </div>
                                <div class="px-4 py-2 flex justify-between bg-slate-700 text-xs font-semibold">
                                    <span class="text-slate-200">Total</span>
                                    <span class="text-slate-100">{{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-950 border border-purple-800 rounded-lg px-4 py-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-purple-100">TOTAL KEWAJIBAN & EKUITAS</span>
                                <span class="text-sm font-bold text-purple-300">{{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="bg-slate-750 rounded-lg border {{ $statusSeimbang == 'Seimbang' ? 'border-green-600' : 'border-red-600' }} overflow-hidden">
                            <div class="px-4 py-3 flex justify-between items-center">
                                <span class="text-xs font-semibold text-slate-200">Status Neraca</span>
                                <span class="text-sm font-bold {{ $statusSeimbang == 'Seimbang' ? 'text-green-400' : 'text-red-400' }}">{{ $statusSeimbang }}</span>
                            </div>
                            @if($statusSeimbang == 'Tidak Seimbang')
                            <div class="px-4 py-2 bg-slate-700 flex justify-between items-center border-t border-red-600 text-xs">
                                <span class="text-slate-300">Selisih</span>
                                <span class="text-red-400 font-semibold">{{ number_format($selisih, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
