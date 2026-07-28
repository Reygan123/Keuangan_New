@extends('layouts.admin.app')
@section('title', 'Riwayat Penyusutan')
@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="mb-4">
            <a href="{{ route('admin.penyusutan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Riwayat Penyusutan</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">{{ $aset->uraian }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Harga Beli</p>
                <p class="text-slate-900 text-base font-bold font-mono">{{ number_format($aset->harga_beli, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Akumulasi</p>
                <p class="text-amber-600 text-base font-bold font-mono">{{ number_format($riwayat->sum('jumlah_penyusutan'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Nilai Buku</p>
                <p class="text-emerald-600 text-base font-bold font-mono">{{ number_format($aset->harga_beli - $riwayat->sum('jumlah_penyusutan'), 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-left text-slate-600 font-semibold uppercase tracking-wider">Tanggal</th>
                            <th class="px-3 sm:px-4 py-3 text-right text-slate-600 font-semibold uppercase tracking-wider">Jumlah</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-slate-600 font-semibold uppercase tracking-wider hidden sm:table-cell">Beban</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-slate-600 font-semibold uppercase tracking-wider hidden md:table-cell">Akumulasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($riwayat as $susut)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-3 sm:px-4 py-3.5 text-slate-700 text-xs">{{ $susut->tanggal_penyusutan->format('d/m/Y') }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-right text-slate-900 font-semibold font-mono">{{ number_format($susut->jumlah_penyusutan, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-slate-600 text-xs hidden sm:table-cell truncate">{{ $susut->akunBeban->name ?? '-' }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-slate-600 text-xs hidden md:table-cell truncate">{{ $susut->akunAkumulasi->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($riwayat->isEmpty())
        <div class="mt-6 text-center py-8">
            <p class="text-slate-500 text-xs sm:text-sm">Belum ada data penyusutan</p>
        </div>
        @endif
    </div>
</div>
@endsection
