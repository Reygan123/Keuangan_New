@extends('layouts.admin.app')
@section('title', 'Manajemen Aset Tetap & Penyusutan')
@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white">Manajemen Aset Tetap & Penyusutan</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Kelola data aset tetap dan proses penyusutan bulanan</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                @if($selectedUsahaId)
                <a href="{{ route('admin.penyusutan.create', ['usaha_id' => $selectedUsahaId]) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Aset
                </a>
                @endif
                <form method="POST" action="{{ route('admin.penyusutan.proses') }}" class="inline-flex gap-2">
                    @csrf
                    <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                    <input type="month" name="bulan" class="px-3 py-2 text-xs sm:text-sm bg-slate-800 border border-slate-700 text-white rounded-lg focus:outline-none focus:border-blue-500" value="{{ now()->format('Y-m') }}">
                    <button type="submit" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Proses
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-3 sm:p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="usaha_id" onchange="window.location.href='{{ route('admin.penyusutan.index') }}?usaha_id=' + this.value" class="flex-1 px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                <input type="text" id="searchInput" placeholder="Cari nama aset..." class="flex-1 px-3 py-2 text-xs sm:text-sm bg-slate-700/50 border border-slate-600/50 text-white placeholder-slate-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                <button onclick="document.getElementById('searchInput').value=''; filterTable();" class="px-3 py-2 text-xs sm:text-sm bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">Reset</button>
            </div>
        </div>

        @if($selectedUsahaId)
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead class="bg-slate-900/50 border-b border-slate-700/60">
                        <tr>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-slate-300 font-semibold">Nama Aset</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-slate-300 font-semibold hidden sm:table-cell">Perolehan</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-right text-slate-300 font-semibold">Harga Beli</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-center text-slate-300 font-semibold hidden md:table-cell">Umur</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-right text-slate-300 font-semibold hidden lg:table-cell">Akumulasi</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-right text-slate-300 font-semibold">Nilai Buku</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-center text-slate-300 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60" id="tableBody">
                        @foreach($asetTetap as $aset)
                        <tr class="hover:bg-slate-700/30 transition-colors searchable-row" data-search="{{ strtolower($aset->uraian) }}">
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-white font-medium truncate">{{ $aset->uraian }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-slate-300 hidden sm:table-cell text-xs">{{ $aset->tgl_perolehan }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-right text-slate-300">{{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-center text-slate-300 hidden md:table-cell">{{ $aset->umur_ekonomis }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-right text-slate-300 hidden lg:table-cell">{{ number_format($aset->total_akumulasi, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-right font-semibold text-emerald-400">{{ number_format($aset->nilai_buku, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3 text-center">
                                <div class="flex gap-1 justify-center">
                                    <a href="{{ route('admin.penyusutan.riwayat', $aset->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 transition-colors" title="Riwayat">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.penyusutan.edit', $aset->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded bg-amber-600/20 hover:bg-amber-600/40 text-amber-400 transition-colors" title="Edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.penyusutan.destroy', $aset->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aset ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded bg-red-600/20 hover:bg-red-600/40 text-red-400 transition-colors" title="Hapus">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
            <p class="text-slate-400">Belum ada data aset tetap</p>
        </div>
        @endif

        @else
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-8 text-center">
            <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk melihat data aset tetap</p>
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
