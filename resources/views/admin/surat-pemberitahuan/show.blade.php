
@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#0F172A]">Detail Surat Pemberitahuan</h1>
                <p class="text-[#64748B] text-sm mt-1">{{ $suratPemberitahuan->nomor_surat }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.surat-pemberitahuan.edit', $suratPemberitahuan->id) }}" class="flex items-center gap-2 px-3 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path></svg>
                    Edit
                </a>
                <a href="{{ route('admin.surat-pemberitahuan.preview-pdf', $suratPemberitahuan->id) }}" class="flex items-center gap-2 px-3 py-2 bg-[#8B5CF6] hover:bg-[#7C3AED] text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
                <a href="{{ route('admin.surat-pemberitahuan.index') }}" class="flex items-center gap-2 px-3 py-2 bg-[#F8FAFC] hover:bg-[#EEF2F6] text-[#0F172A] text-sm font-medium rounded transition-colors">
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
                <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Informasi Surat</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-[#64748B]">Nomor Surat</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748B]">Judul Indonesia</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->judul_indonesia }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748B]">Judul Inggris</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->judul_inggris }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748B]">Status</p>
                            @switch($suratPemberitahuan->status)
                                @case('draft')
                                    <span class="inline-block px-2 py-1 bg-[#F8FAFC] text-[#0F172A] rounded text-xs font-medium">Draft</span>
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
                            <p class="text-[#64748B]">Usaha</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->usaha->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Informasi Penandatangan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-[#64748B]">Tanggal Surat</p>
                            <p class="text-[#0F172A] font-medium">{{ \Carbon\Carbon::parse($suratPemberitahuan->tanggal_surat)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748B]">Tempat Surat</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->tempat_surat }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748B]">Nama Penandatangan</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->nama_penandatangan }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748B]">Jabatan</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->jabatan_penandatangan }}</p>
                        </div>
                        @if($suratPemberitahuan->nip_penandatangan)
                        <div>
                            <p class="text-[#64748B]">NIP</p>
                            <p class="text-[#0F172A] font-medium">{{ $suratPemberitahuan->nip_penandatangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Isi Surat</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-[#64748B] mb-2">Kepada</p>
                        <div class="bg-[#F8FAFC] rounded p-3 text-[#475569]">
                            {{ $suratPemberitahuan->kepada }}
                        </div>
                    </div>
                    <div>
                        <p class="text-[#64748B] mb-2">Isi Surat</p>
                        <div class="bg-[#F8FAFC] rounded p-3 text-[#475569] whitespace-pre-line">
                            {!! $suratPemberitahuan->isi_surat !!}
                        </div>
                    </div>
                    <div>
                        <p class="text-[#64748B] mb-2">Penutup</p>
                        <div class="bg-[#F8FAFC] rounded p-3 text-[#475569] whitespace-pre-line">
                            {!! $suratPemberitahuan->penutup !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-[#0F172A]">Daftar Peserta Magang</h3>
                    <span class="px-3 py-1 bg-[#F8FAFC] text-[#475569] text-sm rounded">{{ $suratPemberitahuan->pesertaMagangs->count() }} peserta</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                            <tr>
                                <th class="px-4 py-3 text-left text-[#475569] font-semibold">No</th>
                                <th class="px-4 py-3 text-left text-[#475569] font-semibold">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-[#475569] font-semibold">Asal Perguruan Tinggi</th>
                                <th class="px-4 py-3 text-left text-[#475569] font-semibold">Posisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EEF2F6]">
                            @foreach($suratPemberitahuan->pesertaMagangs as $peserta)
                            <tr class="hover:bg-white transition-colors">
                                <td class="px-4 py-3 text-[#475569]">{{ $peserta->nomor_urut }}</td>
                                <td class="px-4 py-3 text-[#0F172A]">{{ $peserta->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-[#475569]">{{ $peserta->asal_perguruan_tinggi }}</td>
                                <td class="px-4 py-3 text-[#475569]">{{ $peserta->posisi }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Update Status</h3>
                <form action="{{ route('admin.surat-pemberitahuan.update-status', $suratPemberitahuan->id) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-[#475569] mb-2">Status Surat</label>
                        <select name="status" class="w-full px-3 py-2 bg-[#F8FAFC] border border-[#EEF2F6] rounded text-[#0F172A] text-sm focus:outline-none focus:border-[#CBD5E1]">
                            <option value="draft" {{ $suratPemberitahuan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ $suratPemberitahuan->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ $suratPemberitahuan->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white text-sm font-medium rounded transition-colors">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
