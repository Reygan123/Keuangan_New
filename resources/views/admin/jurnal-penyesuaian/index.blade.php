@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 mb-1">Jurnal Penyesuaian</h1>
                <p class="text-slate-500 text-sm">Daftar transaksi yang telah disesuaikan pada akhir periode.</p>
            </div>
            <a href="{{ route('admin.jurnal-penyesuaian.create') }}" class="px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all whitespace-nowrap h-fit">
                + Tambah Jurnal Penyesuaian
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-6 border-b border-slate-200 bg-slate-50/50">
                <form method="GET" action="{{ route('admin.jurnal-penyesuaian') }}" class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-1">
                        @if($usahas && $usahas->count() > 0)
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha</label>
                            <select name="usaha_id" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                <option value="">Semua Usaha</option>
                                @foreach($usahas as $u)
                                <option value="{{ $u->id }}" {{ request('usaha_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.jurnal-penyesuaian') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-sm font-semibold rounded-xl transition-all">Reset</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Akun</th>
                            <th class="px-4 py-3 text-left">Deskripsi / Keterangan</th>
                            <th class="px-4 py-3 text-right">Debit</th>
                            <th class="px-4 py-3 text-right">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse($jurnals as $jp)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 text-slate-500 text-xs">
                                {{ \Carbon\Carbon::parse($jp->tanggal_transaksi ?? $jp->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-slate-800 font-medium text-xs">
                                {{ $jp->akun->kode ?? '' }} - {{ $jp->akun->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-slate-800 font-medium">
                                {{ $jp->deskripsi ?? $jp->keterangan }}
                            </td>
                            <td class="px-4 py-3 text-right text-emerald-600 font-semibold">
                                {{ $jp->debit > 0 ? 'Rp ' . number_format($jp->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right text-rose-600 font-semibold">
                                {{ $jp->kredit > 0 ? 'Rp ' . number_format($jp->kredit, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada jurnal penyesuaian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($jurnals, 'hasPages') && $jurnals->hasPages())
            <div class="p-4 sm:p-6 border-t border-slate-200 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-xs text-slate-500">
                        Menampilkan {{ $jurnals->firstItem() ?? 0 }} - {{ $jurnals->lastItem() ?? 0 }} dari {{ $jurnals->total() }} entri
                    </div>
                    <div class="flex items-center gap-1">
                        @if($jurnals->onFirstPage())
                        <span class="px-3 py-1.5 bg-slate-100 text-slate-400 border border-slate-200 text-xs rounded-lg cursor-not-allowed">Sebelumnya</span>
                        @else
                        <a href="{{ $jurnals->previousPageUrl() }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition-colors">Sebelumnya</a>
                        @endif

                        @foreach($jurnals->getUrlRange(max(1, $jurnals->currentPage() - 2), min($jurnals->lastPage(), $jurnals->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" class="px-3 py-1.5 {{ $jurnals->currentPage() == $page ? 'bg-[#005198] text-white font-semibold' : 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-300' }} text-xs rounded-lg transition-colors">{{ $page }}</a>
                        @endforeach

                        @if($jurnals->hasMorePages())
                        <a href="{{ $jurnals->nextPageUrl() }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition-colors">Selanjutnya</a>
                        @else
                        <span class="px-3 py-1.5 bg-slate-100 text-slate-400 border border-slate-200 text-xs rounded-lg cursor-not-allowed">Selanjutnya</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
