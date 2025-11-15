@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Daftar Invoice</h1>
            <a href="{{ route('admin.invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Invoice
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

        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari nomor invoice..." class="w-full px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 placeholder-slate-500">
            </div>
            <select id="statusFilter" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
                <option value="overdue">Overdue</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Reset</button>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-700/50 border-b border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">No</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Nomor Invoice</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Pelanggan/Supplier</th>
                            <th class="px-4 py-3 text-right text-slate-300 font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-right text-slate-300 font-semibold">Total</th>
                            <th class="px-4 py-3 text-left text-slate-300 font-semibold">Status</th>
                            <th class="px-4 py-3 text-center text-slate-300 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($invoices as $key => $invoice)
                        <tr class="hover:bg-slate-700/30 transition invoice-row" data-status="{{ $invoice->status_invoice }}">
                            <td class="px-4 py-3 text-slate-300">{{ $invoices->firstItem() + $key }}</td>
                            <td class="px-4 py-3 text-slate-200 font-medium">{{ $invoice->nomor_invoice }}</td>
                            <td class="px-4 py-3 text-slate-300">
                                @if($invoice->transaksi->pelanggan)
                                    <span class="text-xs px-2 py-1 bg-blue-900/50 text-blue-200 rounded">Pelanggan</span>
                                    {{ $invoice->transaksi->pelanggan->nama }}
                                @elseif($invoice->transaksi->supplier)
                                    <span class="text-xs px-2 py-1 bg-amber-900/50 text-amber-200 rounded">Supplier</span>
                                    {{ $invoice->transaksi->supplier->nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-slate-300">Rp {{ number_format($invoice->transaksi->jumlah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-200 font-medium">Rp {{ number_format($invoice->transaksi->jumlah + ($invoice->jumlah_pajak ?? 0), 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @switch($invoice->status_invoice)
                                    @case('pending')
                                        <span class="text-xs px-2 py-1 bg-amber-900/50 text-amber-200 rounded">Pending</span>
                                        @break
                                    @case('paid')
                                        <span class="text-xs px-2 py-1 bg-green-900/50 text-green-200 rounded">Paid</span>
                                        @break
                                    @case('cancelled')
                                        <span class="text-xs px-2 py-1 bg-red-900/50 text-red-200 rounded">Cancelled</span>
                                        @break
                                    @case('overdue')
                                        <span class="text-xs px-2 py-1 bg-slate-700 text-slate-200 rounded">Overdue</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 rounded transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-600/20 hover:bg-amber-600/40 text-amber-300 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                            <td colspan="7" class="px-4 py-6 text-center text-slate-400">Tidak ada data invoice</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
            <div class="px-4 py-4 border-t border-slate-700 bg-slate-700/20">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterRows();
}

function filterRows() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusTerm = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.invoice-row');

    rows.forEach(row => {
        const invoiceNo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const status = row.getAttribute('data-status');

        const matchSearch = invoiceNo.includes(searchTerm);
        const matchStatus = statusTerm === '' || status === statusTerm;

        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('keyup', filterRows);
document.getElementById('statusFilter').addEventListener('change', filterRows);
</script>
@endsection
