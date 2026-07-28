@extends('layouts.admin.app')
@section('title', 'Pembayaran Di Muka')
@section('content')
<div class="min-h-screen bg-slate-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1">Manajemen Pembayaran Di Muka</h1>
            <p class="text-xs text-slate-500">Kelola pembayaran dimuka dan amortisasi aset</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">
                {{ $message }}
            </div>
        @endif

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 mb-6 shadow-sm">
            <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Pilih Usaha</label>
                    <select name="usaha_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    @if($selectedUsahaId)
                    <a href="{{ route('admin.pembayaran-dimuka.create', ['usaha_id' => $selectedUsahaId]) }}" class="w-full flex items-center justify-center bg-[#005198] hover:bg-[#003d73] text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
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
                <input type="month" name="bulan" value="{{ now()->format('Y-m') }}" class="flex-1 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors whitespace-nowrap shadow-sm">
                    Proses Amortisasi
                </button>
            </form>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Pembayaran</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal Transaksi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Periode</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Jumlah Nominal</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Diamortisasi</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Nilai Buku</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($pembayaranDimuka as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3.5 text-slate-900 font-medium">{{ $item->nama_pembayaran }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 text-slate-600 text-xs">
                                {{ \Carbon\Carbon::parse($item->periode_mulai)->format('d M Y') }}<br>
                                s/d {{ \Carbon\Carbon::parse($item->periode_akhir)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-right text-slate-800 font-mono">Rp {{ number_format($item->jumlah_nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-right text-slate-800 font-mono">Rp {{ number_format($item->total_diamortisasi, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-right text-emerald-600 font-semibold font-mono">Rp {{ number_format($item->nilai_buku, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $item->status == 'AKTIF' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex justify-center gap-1.5">
                                    <a href="{{ route('admin.pembayaran-dimuka.riwayat', $item->id) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors" title="Riwayat">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </a>
                                    <a href="{{ route('admin.pembayaran-dimuka.edit', $item->id) }}" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-colors border border-amber-200/60" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.pembayaran-dimuka.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pembayaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors border border-rose-200/60" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
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
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk melihat pembayaran di muka</p>
        </div>
        @endif
    </div>
</div>
@endsection
