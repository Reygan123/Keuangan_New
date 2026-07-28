@extends('layouts.admin.app')

@section('title', $title)

@section('content')
    <div class="min-h-screen bg-slate-50 py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('admin.kasbank.index', $tipe) }}"
                    class="p-2 hover:bg-slate-200/60 rounded-xl text-slate-500 hover:text-slate-800 transition-colors duration-200">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">{{ $title }} #{{ $kasbank->id }}</h1>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="mb-6 p-4 bg-blue-50/60 rounded-xl border border-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600/10 border border-blue-200/80 rounded-lg p-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-medium">Usaha</p>
                            <p class="text-slate-900 font-semibold text-sm">{{ $kasbank->usaha->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal Transaksi</dt>
                        <dd class="text-slate-900 text-sm font-medium">{{ \Carbon\Carbon::parse($kasbank->tanggal)->format('d M Y') }}
                        </dd>
                    </div>

                    <div class="border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Jumlah</dt>
                        <dd class="text-blue-600 text-base font-bold font-mono">Rp
                            {{ number_format($kasbank->jumlah, 0, ',', '.') }}</dd>
                    </div>

                    <div class="border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Label/Kategori</dt>
                        <dd class="text-slate-900 text-sm font-medium">{{ $kasbank->label->nama_label ?? $kasbank->label->name }}</dd>
                    </div>

                    <div class="border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</dt>
                        <dd>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $kasbank->status == 'PROCESSED' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ $kasbank->status }}
                            </span>
                        </dd>
                    </div>

                    <div class="border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Akun Kas/Bank</dt>
                        <dd class="text-slate-900 text-sm font-medium">{{ $kasbank->akunPayment->kode }} -
                            {{ $kasbank->akunPayment->name }}</dd>
                    </div>

                    <div class="border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Akun Lawan</dt>
                        <dd class="text-slate-900 text-sm font-medium">{{ $kasbank->akunLawan->kode }} -
                            {{ $kasbank->akunLawan->name }}</dd>
                    </div>

                    <div class="md:col-span-2 border-b border-slate-200/80 pb-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Keterangan</dt>
                        <dd class="text-slate-700 text-sm bg-slate-50 rounded-xl p-3.5 border border-slate-200/80">
                            {{ $kasbank->keterangan ?? 'Tidak ada keterangan.' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-6 flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.kasbank.edit', ['tipe' => $tipe, 'kasbank' => $kasbank->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.kasbank.destroy', ['tipe' => $tipe, 'kasbank' => $kasbank->id]) }}"
                    method="POST" onsubmit="return confirm('Hapus transaksi ini? Jurnal akan dibalikkan.');"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
