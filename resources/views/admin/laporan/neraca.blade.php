@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-50 p-4 sm:p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Laporan Neraca</h1>
                    <p class="text-slate-500 text-sm mt-1">Posisi aset, kewajiban, dan ekuitas</p>
                </div>
                <form method="GET" action="{{ route('admin.laporan.neraca') }}"
                    class="flex flex-col sm:flex-row gap-2.5 items-end">
                    @if ($usahas && $usahas->count() > 0)
                        <div>
                            <label class="text-xs text-slate-700 font-semibold block mb-1">Usaha</label>
                            <select name="usaha_id"
                                class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                @foreach ($usahas as $usahaItem)
                                    <option value="{{ $usahaItem->id }}"
                                        {{ $usahaSelected == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <label class="text-xs text-slate-700 font-semibold block mb-1">Tahun</label>
                        <select name="tahun"
                            class="bg-white border border-slate-300 text-slate-900 text-sm px-3 py-2 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            @for ($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                                <option value="{{ $i }}" @selected($tahun == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-[#005198] hover:bg-[#003d73] text-white text-sm px-4 py-2 rounded-xl transition-all font-semibold shadow-sm h-fit">Filter</button>
                </form>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-slate-50 px-4 md:px-6 py-4 border-b border-slate-200">
                    <div class="text-center">
                        <h2 class="text-base md:text-lg font-bold text-slate-900">{{ $usaha->nama }}</h2>
                        <p class="text-xs md:text-sm font-semibold text-slate-600 tracking-wide mt-1">LAPORAN NERACA</p>
                        <p class="text-xs text-slate-500 mt-0.5">31 Desember {{ $tahun }}</p>
                    </div>
                </div>

                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-900 bg-slate-100/80 px-4 py-2 rounded-xl border border-slate-200/60">ASET</h3>

                            <div class="bg-slate-50/70 rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                                <div class="bg-slate-100/80 px-4 py-2 border-b border-slate-200/80">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Aset Lancar
                                    </h4>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($neracaItems['ASET_LANCAR'] as $aset)
                                        <div
                                            class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                            <span class="text-slate-700">{{ $aset['name'] }}</span>
                                            <span
                                                class="text-slate-900 font-medium">{{ number_format($aset['saldo_akhir'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="px-4 py-2 flex justify-between bg-slate-100/80 text-xs font-semibold">
                                        <span class="text-slate-800">Total</span>
                                        <span
                                            class="text-slate-900">{{ number_format($totalAsetLancar, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50/70 rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                                <div class="bg-slate-100/80 px-4 py-2 border-b border-slate-200/80">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Aset Tetap
                                        (Bruto)</h4>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($neracaItems['ASET_TETAP_BRUTO'] as $aset)
                                        <div
                                            class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                            <span class="text-slate-700">{{ $aset['name'] }}</span>
                                            <span
                                                class="text-slate-900 font-medium">{{ number_format($aset['saldo_akhir'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="px-4 py-2 flex justify-between bg-slate-100/80 text-xs font-semibold">
                                        <span class="text-slate-800">Total</span>
                                        <span
                                            class="text-slate-900">{{ number_format($totalAsetTetapBruto, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50/70 rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                                <div class="bg-slate-100/80 px-4 py-2 border-b border-slate-200/80">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Akumulasi
                                        Penyusutan</h4>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($neracaItems['AKUMULASI_PENYUSUTAN'] as $akumulasi)
                                        <div
                                            class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                            <span class="text-slate-700">{{ $akumulasi['name'] }}</span>
                                            <span
                                                class="text-rose-600 font-medium">({{ number_format(abs($akumulasi['saldo_akhir']), 0, ',', '.') }})</span>
                                        </div>
                                    @endforeach
                                    <div class="px-4 py-2 flex justify-between bg-slate-100/80 text-xs font-semibold">
                                        <span class="text-slate-800">Total</span>
                                        <span
                                            class="text-rose-600">({{ number_format(abs($totalAkumulasiPenyusutan), 0, ',', '.') }})</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50/80 border border-blue-200/80 rounded-xl px-4 py-3 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-blue-900">Total Aset Tetap (Neto)</span>
                                    <span
                                        class="text-sm font-bold text-blue-700">{{ number_format($totalAsetTetapNeto, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="bg-emerald-50/80 border border-emerald-200/80 rounded-xl px-4 py-3 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-emerald-900">TOTAL ASET</span>
                                    <span
                                        class="text-sm font-bold text-emerald-700">{{ number_format($totalAset, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-900 bg-slate-100/80 px-4 py-2 rounded-xl border border-slate-200/60">KEWAJIBAN &
                                EKUITAS</h3>

                            <div class="bg-slate-50/70 rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                                <div class="bg-slate-100/80 px-4 py-2 border-b border-slate-200/80">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Kewajiban
                                        Lancar</h4>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($neracaItems['KEWAJIBAN_LANCAR'] as $kewajiban)
                                        <div
                                            class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                            <span class="text-slate-700">{{ $kewajiban['name'] }}</span>
                                            <span
                                                class="text-slate-900 font-medium">{{ number_format($kewajiban['saldo_akhir'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="px-4 py-2 flex justify-between bg-slate-100/80 text-xs font-semibold">
                                        <span class="text-slate-800">Total</span>
                                        <span
                                            class="text-slate-900">{{ number_format($totalKewajibanLancar, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50/70 rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                                <div class="bg-slate-100/80 px-4 py-2 border-b border-slate-200/80">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Kewajiban
                                        Jangka Panjang</h4>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($neracaItems['KEWAJIBAN_PANJANG'] as $kewajiban)
                                        <div
                                            class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                            <span class="text-slate-700">{{ $kewajiban['name'] }}</span>
                                            <span
                                                class="text-slate-900 font-medium">{{ number_format($kewajiban['saldo_akhir'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="px-4 py-2 flex justify-between bg-slate-100/80 text-xs font-semibold">
                                        <span class="text-slate-800">Total</span>
                                        <span
                                            class="text-slate-900">{{ number_format($totalKewajibanPanjang, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-amber-50/80 border border-amber-200/80 rounded-xl px-4 py-3 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-amber-900">Total Kewajiban</span>
                                    <span
                                        class="text-sm font-bold text-amber-800">{{ number_format($totalKewajiban, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="bg-slate-50/70 rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                                <div class="bg-slate-100/80 px-4 py-2 border-b border-slate-200/80">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Ekuitas</h4>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($neracaItems['EKUITAS'] as $ekuitas)
                                        <div
                                            class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                            <span class="text-slate-700">{{ $ekuitas['name'] }}</span>
                                            <span
                                                class="text-slate-900 font-medium">{{ number_format($ekuitas['saldo_akhir'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div
                                        class="px-4 py-2 flex justify-between hover:bg-slate-50 transition-colors text-xs">
                                        <span class="text-slate-700">Laba Tahun Berjalan</span>
                                        <span
                                            class="text-slate-900 font-medium">{{ number_format($labaBersih, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="px-4 py-2 flex justify-between bg-slate-100/80 text-xs font-semibold">
                                        <span class="text-slate-800">Total</span>
                                        <span
                                            class="text-slate-900">{{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50/80 border border-purple-200/80 rounded-xl px-4 py-3 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-purple-900">TOTAL KEWAJIBAN & EKUITAS</span>
                                    <span
                                        class="text-sm font-bold text-purple-800">{{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div
                                class="bg-slate-50/80 rounded-xl border {{ $statusSeimbang == 'Seimbang' ? 'border-emerald-300' : 'border-rose-300' }} overflow-hidden shadow-sm">
                                <div class="px-4 py-3 flex justify-between items-center">
                                    <span class="text-xs font-semibold text-slate-800">Status Neraca</span>
                                    <span
                                        class="text-sm font-bold {{ $statusSeimbang == 'Seimbang' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $statusSeimbang }}</span>
                                </div>
                                @if ($statusSeimbang == 'Tidak Seimbang')
                                    <div
                                        class="px-4 py-2 bg-rose-50 flex justify-between items-center border-t border-rose-200 text-xs">
                                        <span class="text-slate-700 font-medium">Selisih</span>
                                        <span
                                            class="text-rose-600 font-semibold">{{ number_format($selisih, 0, ',', '.') }}</span>
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
