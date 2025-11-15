@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h1 class="text-xl font-semibold text-slate-100">Receipt</h1>
        <a href="{{ route('admin.receipts.create') }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">+ Tambah</a>
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
</div>

<script>
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
