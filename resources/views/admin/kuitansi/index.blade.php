@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Daftar Kuitansi</h1>
            <a href="{{ route('admin.kuitansi.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Kuitansi Baru
            </a>
        </div>

        <div class="mb-6 flex gap-3">
            <input type="text" id="searchInput" placeholder="Cari nomor kuitansi..." class="flex-1 px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 placeholder-slate-500">
            <button onclick="resetFilters()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Reset</button>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-700/50 border-b border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Nomor Kuitansi</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Tanggal Pembayaran</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Metode</th>
                            <th class="px-4 py-3 text-right text-slate-300 font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Transaksi</th>
                            <th class="px-4 py-3 text-center text-slate-300 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($kuitansis as $kuitansi)
                        <tr class="hover:bg-slate-700/30 transition kuitansi-row">
                            <td class="px-4 py-3 text-slate-200 font-medium">{{ $kuitansi->nomor_kuitansi }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ \Carbon\Carbon::parse($kuitansi->tanggal_pembayaran)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $kuitansi->metode_pembayaran }}</td>
                            <td class="px-4 py-3 text-right text-slate-200 font-medium">Rp {{ number_format($kuitansi->jumlah_dibayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $kuitansi->transaksi->keterangan ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.kuitansi.show', $kuitansi->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 rounded transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.kuitansi.edit', $kuitansi->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-600/20 hover:bg-amber-600/40 text-amber-300 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.kuitansi.destroy', $kuitansi->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">Tidak ada data kuitansi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('searchInput').value = '';
    filterRows();
}

function filterRows() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.kuitansi-row');

    rows.forEach(row => {
        const nomorKuitansi = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const match = nomorKuitansi.includes(searchTerm);
        row.style.display = match ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('keyup', filterRows);
</script>
@endsection
