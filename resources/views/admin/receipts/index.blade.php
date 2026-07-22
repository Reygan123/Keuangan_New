@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h1 class="text-xl font-semibold text-slate-100">Receipt</h1>
        <div class="flex gap-2">
            @if($usahas->count() > 1)
            <select id="usahaFilter" class="px-3 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded focus:outline-none focus:border-slate-600" onchange="filterByUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
            <a href="{{ route('admin.receipts.create', ['usaha_id' => $currentUsaha?->id]) }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">+ Tambah</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3 bg-green-500 bg-opacity-20 text-green-300 text-sm rounded border border-green-500 border-opacity-30">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-3 p-3 bg-red-500 bg-opacity-20 text-red-300 text-sm rounded border border-red-500 border-opacity-30">{{ session('error') }}</div>
    @endif

    <div class="mb-3 flex flex-col sm:flex-row gap-2">
        <input type="text" id="searchInput" placeholder="Cari nomor receipt..." class="flex-1 bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500 placeholder-slate-400">
        <button onclick="resetSearch()" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">Reset</button>
    </div>

    @if($receipts->count() > 0)
    <div class="overflow-x-auto rounded border border-slate-700">
        <table class="w-full text-sm">
            <thead class="bg-slate-800">
                <tr class="border-b border-slate-700">
                    <th class="px-3 py-2 text-left text-slate-300 font-medium">No Receipt</th>
                    <th class="px-3 py-2 text-left text-slate-300 font-medium">Tanggal</th>
                    <th class="px-3 py-2 text-left text-slate-300 font-medium hidden sm:table-cell">Pihak</th>
                    <th class="px-3 py-2 text-right text-slate-300 font-medium">Total</th>
                    <th class="px-3 py-2 text-right text-slate-300 font-medium hidden md:table-cell">Kembalian</th>
                    <th class="px-3 py-2 text-center text-slate-300 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($receipts as $key => $receipt)
                <tr class="border-b border-slate-700 hover:bg-slate-800 hover:bg-opacity-50 transition">
                    <td class="px-3 py-2 text-slate-300 font-medium">{{ $receipt->nomor_receipt }}</td>
                    <td class="px-3 py-2 text-slate-300 text-xs">{{ \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y') }}</td>
                    <td class="px-3 py-2 text-slate-300 text-xs hidden sm:table-cell">
                        @if($receipt->transaksi->pelanggan)
                            Pelanggan
                        @elseif($receipt->transaksi->supplier)
                            Supplier
                        @else
                            Umum
                        @endif
                    </td>
                    <td class="px-3 py-2 text-slate-300 text-right text-xs">Rp {{ number_format($receipt->transaksi->jumlah, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-slate-300 text-right text-xs hidden md:table-cell">Rp {{ number_format($receipt->kembalian, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-center">
                        <div class="flex justify-center gap-1 flex-wrap">
                            <a href="{{ route('admin.receipts.show', $receipt->id) }}" class="px-2 py-1 bg-slate-700 hover:bg-blue-600 text-slate-100 text-xs rounded transition">Lihat</a>
                            <a href="{{ route('admin.receipts.edit', $receipt->id) }}" class="px-2 py-1 bg-slate-700 hover:bg-amber-600 text-slate-100 text-xs rounded transition">Edit</a>
                            <form action="{{ route('admin.receipts.destroy', $receipt->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 bg-slate-700 hover:bg-red-600 text-slate-100 text-xs rounded transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-3 py-3 text-center text-slate-400 text-sm">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $receipts->links() }}</div>
    @else
    <div class="bg-slate-800 border border-slate-700 rounded p-6 text-center">
        <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-400 mb-2">Tidak ada receipt</p>
        <a href="{{ route('admin.receipts.create', ['usaha_id' => $currentUsaha?->id]) }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition inline-block">Tambah Receipt</a>
    </div>
    @endif
</div>

<script>
function filterByUsaha() {
    const usahaId = document.getElementById('usahaFilter').value;
    window.location.href = '{{ route("admin.receipts.index") }}?usaha_id=' + usahaId;
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

function resetSearch() {
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('#tableBody tr').forEach(row => row.style.display = '');
}
</script>
@endsection
