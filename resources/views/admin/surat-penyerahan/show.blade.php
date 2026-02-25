@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-50">Detail Surat Penyerahan</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $suratPenyerahan->nomor_surat }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.surat-penyerahan.edit', $suratPenyerahan->id) }}" class="flex items-center gap-2 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path></svg>
                    Edit
                </a>
                <a href="{{ route('admin.surat-penyerahan.download-pdf', $suratPenyerahan->id) }}" class="flex items-center gap-2 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
                <a href="{{ route('admin.surat-penyerahan.index') }}" class="flex items-center gap-2 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">
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
                            <p class="text-slate-200 font-medium">{{ $suratPenyerahan->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Perihal</p>
                            <p class="text-slate-200 font-medium">{{ $suratPenyerahan->perihal }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Lampiran</p>
                            <p class="text-slate-200 font-medium">{{ $suratPenyerahan->lampiran }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tempat & Tanggal</p>
                            <p class="text-slate-200 font-medium">{{ $suratPenyerahan->tempat_surat }}, {{ \Carbon\Carbon::parse($suratPenyerahan->tanggal_surat)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Status</p>
                            @switch($suratPenyerahan->status)
                                @case('draft')
                                    <span class="inline-block px-2 py-1 bg-slate-900/50 text-slate-200 rounded text-xs font-medium">Draft</span>
                                    @break
                                @case('active')
                                    <span class="inline-block px-2 py-1 bg-blue-900/50 text-blue-200 rounded text-xs font-medium">Active</span>
                                    @break
                                @case('signed')
                                    <span class="inline-block px-2 py-1 bg-green-900/50 text-green-200 rounded text-xs font-medium">Signed</span>
                                    @break
                                @case('completed')
                                    <span class="inline-block px-2 py-1 bg-purple-900/50 text-purple-200 rounded text-xs font-medium">Completed</span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-block px-2 py-1 bg-red-900/50 text-red-200 rounded text-xs font-medium">Cancelled</span>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            <p class="text-slate-400">Usaha</p>
                            <p class="text-slate-200 font-medium">{{ $suratPenyerahan->usaha->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Pihak</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Pihak Pertama</h4>
                            <div class="space-y-1">
                                <p class="text-slate-200">{{ $suratPenyerahan->pihak_pertama_nama }}</p>
                                <p class="text-slate-400 text-sm">{{ $suratPenyerahan->pihak_pertama_jabatan }}</p>
                                <p class="text-slate-400 text-sm">{{ $suratPenyerahan->pihak_pertama_instansi }}</p>
                                @if($suratPenyerahan->pihak_pertama_nip)
                                <p class="text-slate-400 text-sm">NIP: {{ $suratPenyerahan->pihak_pertama_nip }}</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Pihak Kedua</h4>
                            <div class="space-y-1">
                                <p class="text-slate-200">{{ $suratPenyerahan->pihak_kedua_nama }}</p>
                                <p class="text-slate-400 text-sm">{{ $suratPenyerahan->pihak_kedua_jabatan }}</p>
                                <p class="text-slate-400 text-sm">{{ $suratPenyerahan->pihak_kedua_instansi }}</p>
                                @if($suratPenyerahan->pihak_kedua_nip)
                                <p class="text-slate-400 text-sm">NIP: {{ $suratPenyerahan->pihak_kedua_nip }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Deskripsi Penyerahan</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-slate-400 mb-2">Deskripsi</p>
                        <div class="bg-slate-700/50 rounded p-3 text-slate-300 whitespace-pre-line">
                            {!! $suratPenyerahan->deskripsi_penyerahan !!}
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-2">Keterangan</p>
                        <div class="bg-slate-700/50 rounded p-3 text-slate-300 whitespace-pre-line">
                            {!! $suratPenyerahan->keterangan !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-50">Detail Akun yang Diserahkan</h3>
                    <span class="px-3 py-1 bg-slate-700 text-slate-300 text-sm rounded">{{ $suratPenyerahan->detailPenyerahans->count() }} akun</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-700/50 border-b border-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">No</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Nama Aplikasi</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Username</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Email Terkait</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Password</th>
                                <th class="px-4 py-3 text-left text-slate-300 font-semibold">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($suratPenyerahan->detailPenyerahans as $detail)
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="px-4 py-3 text-slate-300">{{ $detail->nomor_urut }}</td>
                                <td class="px-4 py-3 text-slate-200">{{ $detail->nama_aplikasi }}</td>
                                <td class="px-4 py-3 text-slate-300 font-mono">{{ $detail->username }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $detail->email_terkait ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-300 font-mono">{{ $detail->password }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $detail->catatan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Update Status</h3>
                <form action="{{ route('admin.surat-penyerahan.update-status', $suratPenyerahan->id) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Status Surat Penyerahan</label>
                        <select name="status" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                            <option value="draft" {{ $suratPenyerahan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ $suratPenyerahan->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="signed" {{ $suratPenyerahan->status == 'signed' ? 'selected' : '' }}>Signed</option>
                            <option value="completed" {{ $suratPenyerahan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $suratPenyerahan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
