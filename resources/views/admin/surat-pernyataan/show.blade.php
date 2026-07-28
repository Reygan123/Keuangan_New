
@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-[#F5F7FA] p-4 md:p-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#0F172A]">Detail Surat Pernyataan</h1>
                    <p class="text-[#64748B] text-sm mt-1">{{ $suratPernyataan->nomor_surat }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.surat-pernyataan.edit', $suratPernyataan->id) }}"
                        class="flex items-center gap-2 px-3 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-medium rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.surat-pernyataan.download-pdf', $suratPernyataan->id) }}"
                        class="flex items-center gap-2 px-3 py-2 bg-[#8B5CF6] hover:bg-[#7C3AED] text-white text-sm font-medium rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('admin.surat-pernyataan.index') }}"
                        class="flex items-center gap-2 px-3 py-2 bg-[#F8FAFC] hover:bg-[#EEF2F6] text-[#0F172A] text-sm font-medium rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            @if (session('success'))
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
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->nomor_surat }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Tanggal Dibuat</p>
                                <p class="text-[#0F172A] font-medium">
                                    {{ \Carbon\Carbon::parse($suratPernyataan->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Tanggal Surat</p>
                                <p class="text-[#0F172A] font-medium">
                                    {{ \Carbon\Carbon::parse($suratPernyataan->tanggal_surat)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Status</p>
                                @switch($suratPernyataan->status)
                                    @case('draft')
                                        <span
                                            class="inline-block px-2 py-1 bg-[#F8FAFC] text-[#0F172A] rounded text-xs font-medium">Draft</span>
                                    @break

                                    @case('active')
                                        <span
                                            class="inline-block px-2 py-1 bg-green-900/50 text-green-200 rounded text-xs font-medium">Active</span>
                                    @break

                                    @case('expired')
                                        <span
                                            class="inline-block px-2 py-1 bg-amber-900/50 text-amber-200 rounded text-xs font-medium">Expired</span>
                                    @break

                                    @case('revoked')
                                        <span
                                            class="inline-block px-2 py-1 bg-red-900/50 text-red-200 rounded text-xs font-medium">Revoked</span>
                                    @break
                                @endswitch
                            </div>
                            <div>
                                <p class="text-[#64748B]">Usaha</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->usaha->nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                        <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Informasi Pribadi</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-[#64748B]">Nama Lengkap</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Jabatan</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->jabatan }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Departemen</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->departemen }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Alamat</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->alamat }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Desa/Kelurahan</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->desa_kelurahan }}</p>
                            </div>
                            <div>
                                <p class="text-[#64748B]">Kecamatan</p>
                                <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->kecamatan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Informasi Tanda Tangan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-[#64748B]">Tempat TTD</p>
                                    <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->tempat_ttd }}</p>
                                </div>
                                <div>
                                    <p class="text-[#64748B]">Nama Pejabat</p>
                                    <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->nama_pejabat }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-[#64748B]">Jabatan Pejabat</p>
                                    <p class="text-[#0F172A] font-medium">{{ $suratPernyataan->jabatan_pejabat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($suratPernyataan->catatan)
                    <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                        <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Catatan</h3>
                        <div class="bg-[#F8FAFC] rounded p-3 text-[#475569] text-sm">
                            {{ $suratPernyataan->catatan }}
                        </div>
                    </div>
                @endif

                @if ($suratPernyataan->description)
                    <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                        <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Isi Pernyataan</h3>
                        <div class="bg-[#F8FAFC] rounded p-3 text-[#475569] text-sm">
                            {!! $suratPernyataan->description !!}
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-lg border border-[#EEF2F6] p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-[#0F172A] mb-4">Update Status</h3>
                    <form action="{{ route('admin.surat-pernyataan.update-status', $suratPernyataan->id) }}" method="POST"
                        class="flex flex-col md:flex-row md:items-end gap-3">
                        @csrf
                        @method('PATCH')
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[#475569] mb-2">Status Surat Pernyataan</label>
                            <select name="status"
                                class="w-full px-3 py-2 bg-[#F8FAFC] border border-[#EEF2F6] rounded text-[#0F172A] text-sm focus:outline-none focus:border-[#CBD5E1]">
                                <option value="draft" {{ $suratPernyataan->status == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="active" {{ $suratPernyataan->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="expired" {{ $suratPernyataan->status == 'expired' ? 'selected' : '' }}>
                                    Expired</option>
                                <option value="revoked" {{ $suratPernyataan->status == 'revoked' ? 'selected' : '' }}>
                                    Revoked</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white text-sm font-medium rounded transition-colors">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
