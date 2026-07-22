@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Jenis Surat</h1>
            <a href="{{ route('admin.jenis-surat.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jenis Surat
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-900/50 border border-green-700 text-green-200 text-sm rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button class="text-green-200 hover:text-green-100" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @endif

        @if($jenisSurats->count() > 0)
        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-700/50 border-b border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">No</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Kode Surat</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Initial Code</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Nama Jenis</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-center text-slate-300 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($jenisSurats as $key => $jenis)
                        <tr class="hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3 text-slate-300">{{ $key + 1 }}</td>
                            <td class="px-4 py-3 text-slate-200 font-medium">{{ $jenis->kode_surat }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 bg-blue-900/50 text-blue-200 rounded text-xs">{{ $jenis->initial_code }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $jenis->nama_jenis }}</td>
                            <td class="px-4 py-3 text-slate-300">
                                <div class="max-w-md">{{ Str::limit($jenis->keterangan, 80) }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.jenis-surat.edit', $jenis->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-600/20 hover:bg-amber-600/40 text-amber-300 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.jenis-surat.destroy', $jenis->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-600/20 hover:bg-red-600/40 text-red-300 rounded transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 md:p-12 text-center">
            <svg class="w-12 h-12 md:w-16 md:h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-slate-400 text-base md:text-lg mb-3 font-medium">Tidak ada data jenis surat</p>
            <p class="text-slate-500 text-sm mb-6">Mulai dengan menambahkan jenis surat baru</p>
            <a href="{{ route('admin.jenis-surat.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-blue-600/50 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jenis Surat
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
