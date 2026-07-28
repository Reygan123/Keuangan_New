
@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA]">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0F172A]">Data Surat</h1>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.surat.export-pdf', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#EF4444] hover:bg-[#DC2626] text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.surat.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Surat
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-900/50 border border-green-700 text-green-200 text-sm rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button class="text-green-200 hover:text-green-100" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button class="text-red-200 hover:text-red-100" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @endif

        <div class="mb-6 bg-white rounded-lg border border-[#EEF2F6] p-4">
            <form action="{{ route('admin.surat.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#475569] mb-2">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor/keterangan..." class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] placeholder-[#94A3B8]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#475569] mb-2">Jenis Surat</label>
                    <select name="jenis_surat_id" class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3]">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisSurats as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#475569] mb-2">Tahun</label>
                    <select name="tahun" class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3]">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white text-sm font-medium rounded-lg transition">Filter</button>
                    <a href="{{ route('admin.surat.index') }}" class="px-4 py-2 bg-[#F8FAFC] hover:bg-[#EEF2F6] text-[#0F172A] text-sm font-medium rounded-lg transition">Reset</a>
                </div>
            </form>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <div class="text-[#64748B] text-sm">
                Menampilkan {{ $surats->firstItem() ?? 0 }} - {{ $surats->lastItem() ?? 0 }} dari {{ $surats->total() }} data
            </div>
        </div>

        @if($surats->count() > 0)
        <div class="bg-white rounded-lg border border-[#EEF2F6] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <tr>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">No</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Nomor Surat</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Jenis</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Unit</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Perusahaan</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Bulan/Tahun</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-left text-[#475569] font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-center text-[#475569] font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF2F6]">
                        @forelse($surats as $key => $surat)
                        <tr class="hover:bg-white transition">
                            <td class="px-4 py-3 text-[#475569]">{{ $surats->firstItem() + $key }}</td>
                            <td class="px-4 py-3">
                                <div class="text-[#0F172A] font-medium">{{ $surat->nomor_surat }}</div>
                                <div class="text-xs text-[#64748B]">{{ $surat->nomor_urut }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 bg-[#F8FAFC] text-[#0F172A] rounded text-xs">{{ $surat->jenisSurat->initial_code }}</span>
                                <div class="text-xs text-[#64748B] mt-1">{{ $surat->jenisSurat->nama_jenis }}</div>
                            </td>
                            <td class="px-4 py-3 text-[#475569]">{{ $surat->kode_unit }}</td>
                            <td class="px-4 py-3 text-[#475569]">{{ $surat->kode_perusahaan }}</td>
                            <td class="px-4 py-3 text-[#475569]">
                                {{ str_pad($surat->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $surat->tahun }}
                            </td>
                            <td class="px-4 py-3 text-[#475569]">
                                <div class="max-w-xs truncate">{{ $surat->keterangan }}</div>
                            </td>
                            <td class="px-4 py-3 text-[#475569]">
                                {{ CarbonCarbon::parse($surat->tanggal_dikeluarkan)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.surat.show', $surat->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-[#EFF6FF] hover:bg-[#DBEAFE] text-[#3B41E3] rounded transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.surat.edit', $surat->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.surat.destroy', $surat->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#DC2626] rounded transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-[#64748B]">Tidak ada data surat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($surats->hasPages())
            <div class="px-4 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC]">
                {{ $surats->links() }}
            </div>
            @endif
        </div>
        @else
        <div class="bg-white rounded-lg border border-[#EEF2F6] p-8 md:p-12 text-center">
            <svg class="w-12 h-12 md:w-16 md:h-16 text-[#94A3B8] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-[#64748B] text-base md:text-lg mb-3 font-medium">Tidak ada data surat</p>
            <p class="text-[#0F172A]0 text-sm mb-6">Mulai dengan menambahkan data surat baru</p>
            <a href="{{ route('admin.surat.create') }}" class="inline-flex items-center gap-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-blue-600/50 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Surat
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
