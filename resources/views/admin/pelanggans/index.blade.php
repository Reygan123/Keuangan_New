@extends('layouts.admin.app')

@section('content')
<div class="p-4 md:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-slate-200">Daftar Pelanggan</h1>
        <button onclick="document.getElementById('addModal').showModal()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition whitespace-nowrap">+ Tambah</button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 text-green-100 rounded text-sm border border-green-700">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-col gap-3">
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="searchInput" placeholder="Cari nama, email, atau telepon..." class="flex-1 px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-400 focus:outline-none focus:border-blue-500">
            <button onclick="resetSearch()" class="px-4 py-2 bg-slate-700 text-slate-200 text-sm rounded hover:bg-slate-600 transition whitespace-nowrap">Reset</button>
        </div>
    </div>

    <div class="bg-slate-800 rounded-lg shadow-lg overflow-hidden border border-slate-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-slate-200">
                <thead class="bg-slate-700 border-b border-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-100">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-100">Email</th>
                        <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-100">Telepon</th>
                        <th class="hidden lg:table-cell px-4 py-3 text-left font-semibold text-slate-100">Alamat</th>
                        <th class="px-4 py-3 text-center font-semibold text-slate-100">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach ($pelanggans as $pelanggan)
                        <tr class="border-b border-slate-700 hover:bg-slate-750 transition searchable" data-search="{{ strtolower($pelanggan->nama . ' ' . $pelanggan->email . ' ' . $pelanggan->telepon) }}">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-100">{{ $pelanggan->nama }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-slate-300 truncate">{{ $pelanggan->email }}</div>
                            </td>
                            <td class="hidden md:table-cell px-4 py-3 text-slate-300">{{ $pelanggan->telepon }}</td>
                            <td class="hidden lg:table-cell px-4 py-3 text-slate-400 truncate max-w-xs">{{ $pelanggan->alamat }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <button onclick="editPelanggan({{ $pelanggan }})" class="px-3 py-1 bg-amber-600 text-white text-xs rounded hover:bg-amber-700 transition">Edit</button>
                                    <form action="{{ route('admin.pelanggans.destroy', $pelanggan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-700 text-white text-xs rounded hover:bg-red-800 transition">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="noResults" class="hidden text-center py-8 text-slate-400">
                <div class="text-sm">Tidak ada data yang cocok</div>
            </div>
        </div>
    </div>
</div>

<dialog id="addModal" class="rounded-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 bg-slate-800 border border-slate-700 shadow-xl max-h-screen overflow-y-auto">
    <form method="POST" action="{{ route('admin.pelanggans.store') }}" class="space-y-4">
        @csrf
        <h2 class="text-lg md:text-xl font-semibold text-slate-100 mb-4">Tambah Pelanggan</h2>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Nama</label>
            <input name="nama" placeholder="Nama" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500" required>
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Email</label>
            <input name="email" placeholder="Email" type="email" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Telepon</label>
            <input name="telepon" placeholder="Telepon" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Alamat</label>
            <textarea name="alamat" placeholder="Alamat" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500 resize-none" rows="3"></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="addModal.close()" class="px-4 py-2 text-sm bg-slate-700 text-slate-200 rounded hover:bg-slate-600 transition">Batal</button>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">Simpan</button>
        </div>
    </form>
</dialog>

<dialog id="editModal" class="rounded-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 bg-slate-800 border border-slate-700 shadow-xl max-h-screen overflow-y-auto">
    <form method="POST" id="editForm" class="space-y-4">
        @csrf
        @method('PUT')
        <h2 class="text-lg md:text-xl font-semibold text-slate-100 mb-4">Edit Pelanggan</h2>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Nama</label>
            <input name="nama" id="editNama" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500" required>
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Email</label>
            <input name="email" id="editEmail" type="email" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Telepon</label>
            <input name="telepon" id="editTelepon" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Alamat</label>
            <textarea name="alamat" id="editAlamat" class="w-full px-3 py-2 text-sm bg-slate-700 text-slate-100 border border-slate-600 rounded placeholder-slate-500 focus:outline-none focus:border-blue-500 resize-none" rows="3"></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="editModal.close()" class="px-4 py-2 text-sm bg-slate-700 text-slate-200 rounded hover:bg-slate-600 transition">Batal</button>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">Update</button>
        </div>
    </form>
</dialog>

<script>
function editPelanggan(data) {
    document.getElementById('editNama').value = data.nama
    document.getElementById('editEmail').value = data.email
    document.getElementById('editTelepon').value = data.telepon
    document.getElementById('editAlamat').value = data.alamat ?? ''
    const form = document.getElementById('editForm')
    form.action = `/admin/pelanggans/${data.id}`
    document.getElementById('editModal').showModal()
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.searchable');
    let visibleCount = 0;

    rows.forEach(row => {
        if (row.dataset.search.includes(searchValue)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
});

function resetSearch() {
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('.searchable').forEach(row => row.style.display = '');
    document.getElementById('noResults').classList.add('hidden');
}
</script>
@endsection
