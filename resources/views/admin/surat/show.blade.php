@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-50">Detail Surat</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $surat->nomor_surat }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.surat.edit', $surat->id) }}" class="flex items-center gap-2 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.surat.index') }}" class="flex items-center gap-2 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-900/50 border border-green-700 rounded-lg text-green-200 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Surat</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Nomor Surat</p>
                            <p class="text-slate-200 font-medium">{{ $surat->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Jenis Surat</p>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-blue-900/50 text-blue-200 rounded text-xs font-medium">{{ $surat->jenisSurat->initial_code }}</span>
                                <span class="text-slate-200 font-medium">{{ $surat->jenisSurat->nama_jenis }}</span>
                            </div>
                            <p class="text-slate-400 text-xs mt-1">{{ $surat->jenisSurat->keterangan }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Kode Unit & Perusahaan</p>
                            <div class="flex gap-2">
                                <span class="text-slate-200 font-medium">{{ $surat->kode_unit }}</span>
                                <span class="text-slate-400">/</span>
                                <span class="text-slate-200 font-medium">{{ $surat->kode_perusahaan }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-slate-400">Bulan / Tahun</p>
                            <p class="text-slate-200 font-medium">{{ str_pad($surat->bulan, 2, '0', STR_PAD_LEFT) }} / {{ $surat->tahun }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Tambahan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Nomor Urut</p>
                            <p class="text-slate-200 font-medium">{{ $surat->nomor_urut }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tanggal Dikeluarkan</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Dibuat Pada</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Usaha</p>
                            <p class="text-slate-200 font-medium">{{ $surat->usaha ? $surat->usaha->nama : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Keterangan</h3>
                <div class="bg-slate-700/50 rounded p-4 text-slate-300 text-sm">
                    {{ $surat->keterangan }}
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Analisis Nomor Surat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="bg-slate-700/50 p-3 rounded">
                        <p class="text-slate-400 text-xs">Kode Surat</p>
                        <p class="text-slate-200 font-medium">{{ $surat->jenisSurat->kode_surat }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-3 rounded">
                        <p class="text-slate-400 text-xs">Initial Code</p>
                        <p class="text-slate-200 font-medium">{{ $surat->jenisSurat->initial_code }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-3 rounded">
                        <p class="text-slate-400 text-xs">Nomor Urut</p>
                        <p class="text-slate-200 font-medium">{{ str_pad($surat->nomor_urut, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-3 rounded">
                        <p class="text-slate-400 text-xs">Bulan/Tahun</p>
                        <p class="text-slate-200 font-medium">{{ str_pad($surat->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $surat->tahun }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
