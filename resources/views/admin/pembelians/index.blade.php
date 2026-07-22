@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Pembelian</h1>
            <p class="text-slate-400 text-sm mt-1">Daftar transaksi pembelian</p>
        </div>
        @if($selectedUsahaId)
        <a href="{{ route('admin.pembelians.create', ['usaha_id' => $selectedUsahaId]) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow text-sm">
            Tambah Pembelian
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 rounded-lg flex justify-between items-center text-sm">
            {{ session('success') }}
            <button type="button" class="opacity-70 hover:opacity-100" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    <div class="bg-slate-800/40 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4 mb-4">
        <form method="GET" id="filterForm" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-slate-400 text-xs font-semibold mb-1">Usaha</label>
                <select name="usaha_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-slate-400 text-xs font-semibold mb-1">Cari Supplier</label>
                <input type="text" name="search" placeholder="Cari supplier..." value="{{ request('search') }}" oninput="document.getElementById('filterForm').submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-slate-400 text-xs font-semibold mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-slate-400 text-xs font-semibold mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
                <a href="{{ route('admin.pembelians.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm h-10 flex items-center">Reset</a>
            </div>
        </form>
    </div>

    @if($selectedUsahaId)
    <div class="bg-slate-800/40 backdrop-blur-sm rounded-lg border border-slate-700/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700 text-sm">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">ID</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Tanggal</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Label</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Supplier</th>
                        <th class="px-4 py-3 text-left text-slate-300 uppercase text-xs font-medium">Total</th>
                        <th class="px-4 py-3 text-center text-slate-300 uppercase text-xs font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @forelse($transaksis as $t)
                    <tr class="hover:bg-slate-700/40 transition">
                        <td class="px-4 py-3 text-slate-200">{{ $t->id }}</td>
                        <td class="px-4 py-3 text-slate-200">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-200 text-xs"><span class="px-2 py-1 bg-blue-600/30 text-blue-300 rounded">{{ $t->label->nama_label ?? '-' }}</span></td>
                        <td class="px-4 py-3 text-slate-200">{{ $t->supplier->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-200 font-semibold">Rp{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.pembelians.show', $t->id) }}" class="px-2 py-1 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs">Lihat</a>
                                <a href="{{ route('admin.pembelians.edit', $t->id) }}" class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs">Edit</a>
                                <form action="{{ route('admin.pembelians.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada data transaksi pembelian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-slate-800/40 backdrop-blur-sm rounded-lg border border-slate-700/60 p-8 text-center">
        <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk melihat transaksi pembelian</p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let timeoutId;

    document.querySelector('input[name="search"]').addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 500);
    });
});
</script>
@endsection
