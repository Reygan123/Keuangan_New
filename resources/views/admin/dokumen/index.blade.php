@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-slate-200">Dokumen</h1>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-lg p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.dokumen.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Jenis Dokumen</label>
                    <select name="type" class="w-full bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-500">
                        <option value="">Semua Dokumen</option>
                        <option value="kuitansi" {{ request('type')=='kuitansi'?'selected':'' }}>Kuitansi</option>
                        <option value="nota" {{ request('type')=='nota'?'selected':'' }}>Nota</option>
                        <option value="receipt" {{ request('type')=='receipt'?'selected':'' }}>Receipt</option>
                        <option value="invoice" {{ request('type')=='invoice'?'selected':'' }}>Invoice</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Cari Nomor</label>
                    <input type="text" id="searchInput" placeholder="Cari nomor dokumen..." class="w-full bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-500">
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="flex-1 bg-blue-700 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded border border-blue-600 transition">Filter</button>
                    <a href="{{ route('admin.dokumen.index') }}" class="flex-1 bg-amber-700 hover:bg-amber-600 text-white text-sm px-4 py-2 rounded border border-amber-600 transition text-center">Reset</a>
                </div>
            </div>
        </form>
    </div>

    @if ($data->isEmpty())
        <div class="text-slate-400 text-center py-12 bg-slate-800 border border-slate-700 rounded-lg">
            Tidak ada data untuk ditampilkan
        </div>
    @else
        <div class="overflow-x-auto bg-slate-800 border border-slate-700 rounded-lg">
            <table class="w-full text-sm text-slate-300">
                <thead class="bg-slate-700 border-b border-slate-600">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Nomor Dokumen</th>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Tanggal</th>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Jenis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach ($data as $item)
                        <tr class="hover:bg-slate-700 transition dokumen-row">
                            <td class="px-3 sm:px-4 py-3 text-slate-300">
                                @if($type === 'kuitansi')
                                    {{ $item->nomor_kuitansi }}
                                @elseif($type === 'nota')
                                    {{ $item->nomor_nota }}
                                @elseif($type === 'receipt')
                                    {{ $item->nomor_receipt }}
                                @elseif($type === 'invoice')
                                    {{ $item->nomor_invoice }}
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 py-3 text-slate-400">
                                @if($type === 'kuitansi')
                                    {{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('d M Y') }}
                                @elseif($type === 'invoice')
                                    {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 py-3">
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded bg-slate-700 text-slate-200">
                                    @if($type === 'kuitansi')
                                        Kuitansi
                                    @elseif($type === 'nota')
                                        Nota
                                    @elseif($type === 'receipt')
                                        Receipt
                                    @elseif($type === 'invoice')
                                        Invoice
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.dokumen-row').forEach(row => {
        const nomor = row.querySelector('td:first-child').textContent.toLowerCase();
        row.style.display = nomor.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endsection
