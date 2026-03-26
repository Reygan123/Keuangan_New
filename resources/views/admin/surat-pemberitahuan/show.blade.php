@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-50">Detail Surat Pemberitahuan</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $suratPemberitahuan->nomor_surat }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.surat-pemberitahuan.edit', $suratPemberitahuan->id) }}" class="flex items-center gap-2 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path></svg>
                    Edit
                </a>
                <a href="{{ route('admin.surat-pemberitahuan.preview-pdf', $suratPemberitahuan->id) }}" class="flex items-center gap-2 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
                <a href="{{ route('admin.surat-pemberitahuan.index') }}" class="flex items-center gap-2 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
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
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Judul Indonesia</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->judul_indonesia }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Judul Inggris</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->judul_inggris }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Status</p>
                            @switch($suratPemberitahuan->status)
                                @case('draft')
                                    <span class="inline-block px-2 py-1 bg-slate-900/50 text-slate-200 rounded text-xs font-medium">Draft</span>
                                    @break
                                @case('active')
                                    <span class="inline-block px-2 py-1 bg-green-900/50 text-green-200 rounded text-xs font-medium">Active</span>
                                    @break
                                @case('archived')
                                    <span class="inline-block px-2 py-1 bg-amber-900/50 text-amber-200 rounded text-xs font-medium">Archived</span>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            <p class="text-slate-400">Usaha</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->usaha->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Penandatangan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Tanggal Surat</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($suratPemberitahuan->tanggal_surat)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tempat Surat</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->tempat_surat }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Nama Penandatangan</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->nama_penandatangan }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Jabatan</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->jabatan_penandatangan }}</p>
                        </div>
                        @if($suratPemberitahuan->nip_penandatangan)
                        <div>
                            <p class="text-slate-400">NIP</p>
                            <p class="text-slate-200 font-medium">{{ $suratPemberitahuan->nip_penandatangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Isi Surat</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-slate-400 mb-2">Kepada</p>
                        <div class="bg-slate-700/50 rounded p-3 text-slate-300">
                            {{ $suratPemberitahuan->kepada }}
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-2">Isi Surat</p>
                        <div class="bg-slate-700/50 rounded p-3 text-slate-300 whitespace-pre-line">
                            {!! $suratPemberitahuan->isi_surat !!}
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-2">Penutup</p>
                        <div class="bg-slate-700/50 rounded p-3 text-slate-300 whitespace-pre-line">
                            {!! $suratPemberitahuan->penutup !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-50">Daftar Peserta Magang</h3>
                    <span class="px-3 py-1 bg-slate-700 text-slate-300 text-sm rounded">{{ $suratPemberitahuan->pesertaMagangs->count() }} peserta</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-700/50 border-b border-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">No</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Asal Perguruan Tinggi</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Posisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($suratPemberitahuan->pesertaMagangs as $peserta)
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="px-4 py-3 text-slate-300">{{ $peserta->nomor_urut }}</td>
                                <td class="px-4 py-3 text-slate-200">{{ $peserta->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $peserta->asal_perguruan_tinggi }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $peserta->posisi }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Update Status</h3>
                <form action="{{ route('admin.surat-pemberitahuan.update-status', $suratPemberitahuan->id) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Status Surat</label>
                        <select name="status" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                            <option value="draft" {{ $suratPemberitahuan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ $suratPemberitahuan->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ $suratPemberitahuan->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
