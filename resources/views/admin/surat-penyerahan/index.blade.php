@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Daftar Surat Penyerahan</h1>
            <a href="{{ route('admin.surat-penyerahan.create', ['usaha_id' => $currentUsaha?->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Surat
            </a>
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

        <div class="mb-4 flex flex-col sm:flex-row gap-3">
            @if($usahas->count() > 1)
            <div>
                <select id="usahaFilter" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" onchange="filterByUsaha()">
                    @foreach($usahas as $usahaItem)
                    <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari nomor surat atau perihal..." class="w-full px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 placeholder-slate-500">
            </div>
            <select id="statusFilter" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="active">Active</option>
                <option value="signed">Signed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Reset</button>
        </div>

        @if($suratPenyerahans->count() > 0)
        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-700/50 border-b border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">No</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Nomor Surat</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Perihal</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Pihak Kedua</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Jumlah Akun</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Status</th>
                            <th class="px-4 py-3 text-center text-slate-300 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($suratPenyerahans as $key => $surat)
                        <tr class="hover:bg-slate-700/30 transition surat-row" data-status="{{ $surat->status }}">
                            <td class="px-4 py-3 text-slate-300">{{ $suratPenyerahans->firstItem() + $key }}</td>
                            <td class="px-4 py-3 text-slate-200 font-medium">{{ $surat->nomor_surat }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ Str::limit($surat->perihal, 30) }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ Str::limit($surat->pihak_kedua_instansi, 25) }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $surat->detailPenyerahans->count() }} akun</td>
                            <td class="px-4 py-3">
                                @switch($surat->status)
                                    @case('draft')
                                        <span class="text-xs px-2 py-1 bg-slate-900/50 text-slate-200 rounded">Draft</span>
                                        @break
                                    @case('active')
                                        <span class="text-xs px-2 py-1 bg-blue-900/50 text-blue-200 rounded">Active</span>
                                        @break
                                    @case('signed')
                                        <span class="text-xs px-2 py-1 bg-green-900/50 text-green-200 rounded">Signed</span>
                                        @break
                                    @case('completed')
                                        <span class="text-xs px-2 py-1 bg-purple-900/50 text-purple-200 rounded">Completed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="text-xs px-2 py-1 bg-red-900/50 text-red-200 rounded">Cancelled</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.surat-penyerahan.show', $surat->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 rounded transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.surat-penyerahan.edit', $surat->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-600/20 hover:bg-amber-600/40 text-amber-300 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.surat-penyerahan.download-pdf', $surat->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-purple-600/20 hover:bg-purple-600/40 text-purple-300 rounded transition" title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.surat-penyerahan.destroy', $surat->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-400">Tidak ada data surat penyerahan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($suratPenyerahans->hasPages())
            <div class="px-4 py-4 border-t border-slate-700 bg-slate-700/20">
                {{ $suratPenyerahans->links() }}
            </div>
            @endif
        </div>
        @else
        <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 md:p-12 text-center">
            <svg class="w-12 h-12 md:w-16 md:h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-slate-400 text-base md:text-lg mb-3 font-medium">Tidak ada surat penyerahan</p>
            <p class="text-slate-500 text-sm mb-6">Mulai dengan membuat surat penyerahan baru</p>
            <a href="{{ route('admin.surat-penyerahan.create', ['usaha_id' => $currentUsaha?->id]) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-blue-600/50 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Surat Penyerahan
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function filterByUsaha() {
    const usahaId = document.getElementById('usahaFilter').value;
    window.location.href = '{{ route("admin.surat-penyerahan.index") }}?usaha_id=' + usahaId;
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterRows();
}

function filterRows() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusTerm = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.surat-row');

    rows.forEach(row => {
        const suratNo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const perihal = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const pihak = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        const status = row.getAttribute('data-status');

        const matchSearch = suratNo.includes(searchTerm) || perihal.includes(searchTerm) || pihak.includes(searchTerm);
        const matchStatus = statusTerm === '' || status === statusTerm;

        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('keyup', filterRows);
document.getElementById('statusFilter').addEventListener('change', filterRows);
</script>
@endsection
