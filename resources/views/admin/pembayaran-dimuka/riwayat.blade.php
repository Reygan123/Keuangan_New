@extends('layouts.admin.app')
@section('title', 'Riwayat Amortisasi')
@section('content')
    <div class="min-h-screen bg-slate-950 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('admin.pembayaran-dimuka.index') }}"
                    class="inline-flex items-center text-slate-400 hover:text-slate-300 text-sm mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-white">Riwayat Amortisasi</h1>
                <p class="text-slate-400 text-sm">{{ $pembayaran->nama_pembayaran }}</p>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600/20 border border-blue-500/30 rounded-lg p-3">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Usaha</p>
                        <p class="text-white font-semibold">{{ $pembayaran->usaha->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jumlah Nominal</p>
                    <p class="text-lg font-bold text-white font-mono">Rp
                        {{ number_format($pembayaran->jumlah_nominal, 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Diamortisasi</p>
                    <p class="text-lg font-bold text-amber-400 font-mono">Rp
                        {{ number_format($riwayat->sum('jumlah_amortisasi'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nilai Buku</p>
                    <p class="text-lg font-bold text-emerald-400 font-mono">Rp
                        {{ number_format($pembayaran->jumlah_nominal - $riwayat->sum('jumlah_amortisasi'), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg overflow-hidden shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-700/60">
                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    Tanggal Amortisasi</th>
                                <th
                                    class="text-right px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    Jumlah Amortisasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/60">
                            @forelse($riwayat as $amortisasi)
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3 text-slate-300">
                                        {{ \Carbon\Carbon::parse($amortisasi->tanggal_amortisasi)->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-right text-slate-300 font-mono">Rp
                                        {{ number_format($amortisasi->jumlah_amortisasi, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-8 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm">Belum ada data amortisasi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
