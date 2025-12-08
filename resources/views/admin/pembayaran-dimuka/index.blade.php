@extends('layouts.admin.app')
@section('title', 'Pembayaran Di Muka')
@section('content')
<div class="min-h-screen bg-slate-950 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Manajemen Pembayaran Di Muka</h1>
            <p class="text-xs text-slate-400">Kelola pembayaran dimuka dan amortisasi aset</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="mb-4 bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 px-4 py-3 rounded-lg text-sm">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-4 bg-red-900/30 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm">
                {{ $message }}
            </div>
        @endif

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
            <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 text-xs font-semibold mb-1">Pilih Usaha</label>
                    <select name="usaha_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    @if($selectedUsahaId)
                    <a href="{{ route('admin.pembayaran-dimuka.create', ['usaha_id' => $selectedUsahaId]) }}" class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Pembayaran
                    </a>
                    @endif
                </div>
            </form>
        </div>

        @if($selectedUsahaId)
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <form method="POST" action="{{ route('admin.pembayaran-dimuka.proses') }}" class="lg:col-span-3 flex gap-2">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                <input type="month" name="bulan" value="{{ now()->format('Y-m') }}" class="flex-1 bg-slate-800/50 border border-slate-700/60 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
                    Proses Amortisasi
                </button>
            </form>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-700/60">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Pembayaran</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Tanggal Transaksi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Periode</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Jumlah Nominal</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Diamortisasi</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Nilai Buku</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-slate-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60">
                        @forelse($pembayaranDimuka as $item)
                        <tr class="hover:bg-slate-700/30 transition-colors">
                            <td class="px-4 py-3 text-slate-300">{{ $item->nama_pembayaran }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-400 text-xs">
                                {{ \Carbon\Carbon::parse($item->periode_mulai)->format('d M Y') }}<br>
                                s/d {{ \Carbon\Carbon::parse($item->periode_akhir)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-right text-slate-300 font-mono">Rp {{ number_format($item->jumlah_nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-300 font-mono">Rp {{ number_format($item->total_diamortisasi, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-emerald-400 font-mono">Rp {{ number_format($item->nilai_buku, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $item->status == 'AKTIF' ? 'bg-emerald-900/30 text-emerald-300 border border-emerald-500/30' : 'bg-slate-700/50 text-slate-300 border border-slate-600/30' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-1">
                                    <a href="{{ route('admin.pembayaran-dimuka.riwayat', $item->id) }}" class="p-1 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded transition-colors" title="Riwayat">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </a>
                                    <a href="{{ route('admin.pembayaran-dimuka.edit', $item->id) }}" class="p-1 bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 rounded transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.pembayaran-dimuka.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pembayaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p class="text-sm">Belum ada pembayaran di muka</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-8 text-center">
            <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk melihat pembayaran di muka</p>
        </div>
        @endif
    </div>
</div>
@endsection
