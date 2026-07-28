@extends('layouts.admin.app')
@section('title', 'Manajemen Aset Tetap & Penyusutan')
@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Manajemen Aset Tetap & Penyusutan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data aset tetap dan proses penyusutan bulanan</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                @if($selectedUsahaId)
                <a href="{{ route('admin.penyusutan.create', ['usaha_id' => $selectedUsahaId]) }}" class="inline-flex items-center justify-center px-4 py-2.5 text-xs sm:text-sm bg-[#005198] hover:bg-[#003d73] text-white rounded-xl font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Aset
                </a>
                @endif
                <form method="POST" action="{{ route('admin.penyusutan.proses') }}" class="inline-flex gap-2">
                    @csrf
                    <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                    <input type="month" name="bulan" class="px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-sm" value="{{ now()->format('Y-m') }}">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 text-xs sm:text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-colors shadow-sm whitespace-nowrap">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Proses
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="usaha_id" onchange="window.location.href='{{ route('admin.penyusutan.index') }}?usaha_id=' + this.value" class="flex-1 px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                <input type="text" id="searchInput" placeholder="Cari nama aset..." class="flex-1 px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <button onclick="document.getElementById('searchInput').value=''; filterTable();" class="px-4 py-2 text-xs sm:text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors border border-slate-200">Reset</button>
            </div>
        </div>

        @if($selectedUsahaId)
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-left text-slate-600 font-semibold uppercase tracking-wider">Nama Aset</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-slate-600 font-semibold uppercase tracking-wider hidden sm:table-cell">Perolehan</th>
                            <th class="px-3 sm:px-4 py-3 text-right text-slate-600 font-semibold uppercase tracking-wider">Harga Beli</th>
                            <th class="px-3 sm:px-4 py-3 text-center text-slate-600 font-semibold uppercase tracking-wider hidden md:table-cell">Umur</th>
                            <th class="px-3 sm:px-4 py-3 text-right text-slate-600 font-semibold uppercase tracking-wider hidden lg:table-cell">Akumulasi</th>
                            <th class="px-3 sm:px-4 py-3 text-right text-slate-600 font-semibold uppercase tracking-wider">Nilai Buku</th>
                            <th class="px-3 sm:px-4 py-3 text-center text-slate-600 font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="tableBody">
                        @foreach($asetTetap as $aset)
                        <tr class="hover:bg-slate-50/80 transition-colors searchable-row" data-search="{{ strtolower($aset->uraian) }}">
                            <td class="px-3 sm:px-4 py-3.5 text-slate-900 font-medium truncate">{{ $aset->uraian }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-slate-600 hidden sm:table-cell text-xs">{{ $aset->tgl_perolehan }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-right text-slate-800 font-mono">{{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-center text-slate-700 hidden md:table-cell">{{ $aset->umur_ekonomis }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-right text-slate-800 font-mono hidden lg:table-cell">{{ number_format($aset->total_akumulasi, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-right font-semibold text-emerald-600 font-mono">{{ number_format($aset->nilai_buku, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-3.5 text-center">
                                <div class="flex gap-1.5 justify-center">
                                    <a href="{{ route('admin.penyusutan.riwayat', $aset->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200/80 transition-colors" title="Riwayat">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.penyusutan.edit', $aset->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.penyusutan.destroy', $aset->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aset ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 transition-colors" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

        @if($asetTetap->isEmpty())
        <div class="mt-6 text-center py-12">
            <p class="text-slate-500">Belum ada data aset tetap</p>
        </div>
        @endif

        @else
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk melihat data aset tetap</p>
        </div>
        @endif
    </div>
</div>

<script>
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.searchable-row');

    rows.forEach(row => {
        const searchText = row.getAttribute('data-search');
        row.style.display = searchText.includes(searchInput) ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('keyup', filterTable);
</script>
@endsection
