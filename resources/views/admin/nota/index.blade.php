@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h1 class="text-xl font-semibold text-slate-100">Nota</h1>
        <a href="{{ route('admin.nota.create') }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition">+ Tambah</a>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3 bg-green-500 bg-opacity-20 text-green-300 text-sm rounded border border-green-500 border-opacity-30">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <input type="text" id="searchInput" placeholder="Cari nomor nota..." class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-sm px-3 py-2 rounded focus:outline-none focus:border-slate-500 placeholder-slate-400">
    </div>

    <div class="overflow-x-auto rounded border border-slate-700">
        <table class="w-full text-sm">
            <thead class="bg-slate-800">
                <tr class="border-b border-slate-700">
                    <th class="px-3 py-2 text-left text-slate-300 font-medium">No Nota</th>
                    <th class="px-3 py-2 text-left text-slate-300 font-medium hidden sm:table-cell">Jenis</th>
                    <th class="px-3 py-2 text-left text-slate-300 font-medium">Transaksi</th>
                    <th class="px-3 py-2 text-center text-slate-300 font-medium">Tunai</th>
                    <th class="px-3 py-2 text-center text-slate-300 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($notas as $nota)
                <tr class="border-b border-slate-700 hover:bg-slate-800 hover:bg-opacity-50 transition">
                    <td class="px-3 py-2 text-slate-300 font-medium">{{ $nota->nomor_nota }}</td>
                    <td class="px-3 py-2 text-slate-300 text-xs hidden sm:table-cell">{{ $nota->jenis_nota }}</td>
                    <td class="px-3 py-2 text-slate-300 text-xs">{{ $nota->transaksi->keterangan ?? '-' }}</td>
                    <td class="px-3 py-2 text-center text-slate-300 text-xs">{{ $nota->is_tunai ? 'Ya' : 'Tidak' }}</td>
                    <td class="px-3 py-2 text-center">
                        <div class="flex justify-center gap-1">
                            <a href="{{ route('admin.nota.edit', $nota->id) }}" class="px-2 py-1 bg-slate-700 hover:bg-amber-600 text-slate-100 text-xs rounded transition">Edit</a>
                            <form action="{{ route('admin.nota.destroy', $nota->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 bg-slate-700 hover:bg-red-600 text-slate-100 text-xs rounded transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-3 text-center text-slate-400 text-sm">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>
@endsection
