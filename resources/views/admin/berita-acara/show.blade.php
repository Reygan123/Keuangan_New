@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] p-4 md:p-6 font-sans">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#0A192F]">Detail Berita Acara</h1>
                <p class="text-[#64748B] text-sm mt-1">{{ $beritaAcara->nomor_surat }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.berita-acara.edit', $beritaAcara->id) }}" class="flex items-center gap-2 px-3 py-2 bg-[#ED8B00] hover:bg-[#c97600] text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path></svg>
                    Edit
                </a>
                <a href="{{ route('admin.berita-acara.download-pdf', $beritaAcara->id) }}" class="flex items-center gap-2 px-3 py-2 bg-[#3B41E3] hover:bg-[#2f34c0] text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
                <a href="{{ route('admin.berita-acara.index') }}" class="flex items-center gap-2 px-3 py-2 bg-white border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#334155] text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-[#ECFDF5] border border-[#A7F3D0] rounded-lg text-[#059669] text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-[#0A192F] mb-4">Informasi Surat</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-[#94A3B8]">Nomor Surat</p>
                            <p class="text-[#0F172A] font-medium">{{ $beritaAcara->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-[#94A3B8]">Judul</p>
                            <p class="text-[#0F172A] font-medium">{{ $beritaAcara->judul }}</p>
                        </div>
                        <div>
                            <p class="text-[#94A3B8]">Hari & Tanggal</p>
                            <p class="text-[#0F172A] font-medium">{{ $beritaAcara->hari }}, {{ \Carbon\Carbon::parse($beritaAcara->tanggal_acara)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[#94A3B8]">Status</p>
                            @switch($beritaAcara->status)
                                @case('draft')
                                    <span class="inline-block px-2 py-1 bg-[#F1F5F9] text-[#475569] rounded-full text-xs font-semibold">Draft</span>
                                    @break
                                @case('active')
                                    <span class="inline-block px-2 py-1 bg-[#ECFDF5] text-[#059669] rounded-full text-xs font-semibold">Active</span>
                                    @break
                                @case('archived')
                                    <span class="inline-block px-2 py-1 bg-[#FEF3E2] text-[#B45309] rounded-full text-xs font-semibold">Archived</span>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            <p class="text-[#94A3B8]">Usaha</p>
                            <p class="text-[#0F172A] font-medium">{{ $beritaAcara->usaha->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-[#0A192F] mb-4">Informasi Pihak</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <h4 class="text-sm font-semibold text-[#005198]">Pihak Pertama</h4>
                            <div>
                                <p class="text-xs text-[#94A3B8]">Nama</p>
                                <p class="text-sm text-[#0F172A]">{{ $beritaAcara->pihak_pertama_nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#94A3B8]">Jabatan</p>
                                <p class="text-sm text-[#0F172A]">{{ $beritaAcara->pihak_pertama_jabatan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#94A3B8]">Instansi</p>
                                <p class="text-sm text-[#0F172A]">{{ $beritaAcara->pihak_pertama_instansi }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <h4 class="text-sm font-semibold text-[#ED8B00]">Pihak Kedua</h4>
                            <div>
                                <p class="text-xs text-[#94A3B8]">Nama</p>
                                <p class="text-sm text-[#0F172A]">{{ $beritaAcara->pihak_kedua_nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#94A3B8]">Jabatan</p>
                                <p class="text-sm text-[#0F172A]">{{ $beritaAcara->pihak_kedua_jabatan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#94A3B8]">Instansi</p>
                                <p class="text-sm text-[#0F172A]">{{ $beritaAcara->pihak_kedua_instansi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 md:p-6">
                <h3 class="text-lg font-semibold text-[#0A192F] mb-4">Keterangan</h3>
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-xl p-4 text-[#334155] text-sm">
                    {!! $beritaAcara->keterangan !!}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 md:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-[#0A192F]">Daftar Akun yang Diserahkan</h3>
                    <span class="px-3 py-1 bg-[#EAF3FB] text-[#005198] text-sm font-semibold rounded-full">{{ $beritaAcara->akuns->count() }} akun</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                            <tr>
                                <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">No</th>
                                <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Nama Aplikasi</th>
                                <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Username</th>
                                <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Email Terkait</th>
                                <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Password</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EEF2F6]">
                            @foreach($beritaAcara->akuns as $akun)
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                <td class="px-4 py-3 text-[#64748B]">{{ $akun->nomor_urut }}</td>
                                <td class="px-4 py-3 text-[#0F172A]">{{ $akun->nama_aplikasi }}</td>
                                <td class="px-4 py-3 text-[#334155]">{{ $akun->username }}</td>
                                <td class="px-4 py-3 text-[#334155]">{{ $akun->email_terkait }}</td>
                                <td class="px-4 py-3 text-[#334155] font-mono text-xs">{{ $akun->password }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-4 md:p-6">
                <h3 class="text-lg font-semibold text-[#0A192F] mb-4">Update Status</h3>
                <form action="{{ route('admin.berita-acara.update-status', $beritaAcara->id) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Status Berita Acara</label>
                        <select name="status" class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-lg text-[#0F172A] text-sm focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20">
                            <option value="draft" {{ $beritaAcara->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ $beritaAcara->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ $beritaAcara->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-lg transition-colors">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection