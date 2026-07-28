@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-[#F5F7FA] font-sans">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0A192F]">Daftar Berita Acara</h1>
            <a href="{{ route('admin.berita-acara.create', ['usaha_id' => $currentUsaha?->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Berita Acara
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-[#ECFDF5] border border-[#A7F3D0] text-[#059669] text-sm rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button class="text-[#059669] hover:text-[#047857]" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-[#FEF2F2] border border-[#FECDD3] text-[#E11D48] text-sm rounded-lg flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button class="text-[#E11D48] hover:text-[#be123c]" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @endif

        <div class="mb-4 flex flex-col sm:flex-row gap-3">
            @if($usahas->count() > 1)
            <div>
                <select id="usahaFilter" class="px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20" onchange="filterByUsaha()">
                    @foreach($usahas as $usahaItem)
                    <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari nomor surat atau judul..." class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 placeholder-[#94A3B8]">
            </div>
            <select id="statusFilter" class="px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="active">Active</option>
                <option value="archived">Archived</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 bg-white border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#334155] text-sm font-medium rounded-lg transition">Reset</button>
        </div>

        @if($beritaAcaras->count() > 0)
        <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <tr>
                            <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">No</th>
                            <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Nomor Surat</th>
                            <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Judul</th>
                            <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Tanggal</th>
                            <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Jumlah Akun</th>
                            <th class="px-4 py-3 text-left text-[#334155] text-xs font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-center text-[#334155] text-xs font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF2F6]">
                        @forelse($beritaAcaras as $key => $beritaAcara)
                        <tr class="hover:bg-[#F8FAFC] transition berita-row" data-status="{{ $beritaAcara->status }}">
                            <td class="px-4 py-3 text-[#64748B]">{{ $beritaAcaras->firstItem() + $key }}</td>
                            <td class="px-4 py-3 text-[#0F172A] font-medium">{{ $beritaAcara->nomor_surat }}</td>
                            <td class="px-4 py-3 text-[#334155]">{{ Str::limit($beritaAcara->judul, 40) }}</td>
                            <td class="px-4 py-3 text-[#334155]">{{ \Carbon\Carbon::parse($beritaAcara->tanggal_acara)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-[#334155]">{{ $beritaAcara->akuns->count() }} akun</td>
                            <td class="px-4 py-3">
                                @switch($beritaAcara->status)
                                    @case('draft')
                                        <span class="text-xs px-2 py-1 bg-[#F1F5F9] text-[#475569] rounded-full font-semibold">Draft</span>
                                        @break
                                    @case('active')
                                        <span class="text-xs px-2 py-1 bg-[#ECFDF5] text-[#059669] rounded-full font-semibold">Active</span>
                                        @break
                                    @case('archived')
                                        <span class="text-xs px-2 py-1 bg-[#FEF3E2] text-[#B45309] rounded-full font-semibold">Archived</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.berita-acara.show', $beritaAcara->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-[#EAF3FB] hover:bg-[#DCE9F7] text-[#005198] rounded-lg transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.berita-acara.edit', $beritaAcara->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-[#FEF3E2] hover:bg-[#FDE7C6] text-[#B45309] rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.berita-acara.download-pdf', $beritaAcara->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-[#EEF2FF] hover:bg-[#DCE4FF] text-[#3B41E3] rounded-lg transition" title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.berita-acara.destroy', $beritaAcara->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-[#FEF2F2] hover:bg-[#FFE4E6] text-[#E11D48] rounded-lg transition" title="Hapus">
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
                            <td colspan="7" class="px-4 py-6 text-center text-[#94A3B8]">Tidak ada data berita acara</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($beritaAcaras->hasPages())
            <div class="px-4 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC]">
                {{ $beritaAcaras->links() }}
            </div>
            @endif
        </div>
        @else
        <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-8 md:p-12 text-center">
            <svg class="w-12 h-12 md:w-16 md:h-16 text-[#CBD5E1] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-[#334155] text-base md:text-lg mb-3 font-semibold">Tidak ada berita acara</p>
            <p class="text-[#94A3B8] text-sm mb-6">Mulai dengan membuat berita acara baru</p>
            <a href="{{ route('admin.berita-acara.create', ['usaha_id' => $currentUsaha?->id]) }}" class="inline-flex items-center gap-2 bg-[#005198] hover:bg-[#003d73] text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-[#005198]/30 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Berita Acara
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function filterByUsaha() {
    const usahaId = document.getElementById('usahaFilter').value;
    window.location.href = '{{ route("admin.berita-acara.index") }}?usaha_id=' + usahaId;
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterRows();
}

function filterRows() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusTerm = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.berita-row');

    rows.forEach(row => {
        const suratNo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const judul = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const status = row.getAttribute('data-status');

        const matchSearch = suratNo.includes(searchTerm) || judul.includes(searchTerm);
        const matchStatus = statusTerm === '' || status === statusTerm;

        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('keyup', filterRows);
document.getElementById('statusFilter').addEventListener('change', filterRows);
</script>
@endsection