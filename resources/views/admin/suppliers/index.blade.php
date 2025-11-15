@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 ml-0">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-200">Daftar Supplier</h1>
        <button onclick="document.getElementById('addModal').showModal()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-lg text-sm font-medium transition-colors">
            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-slate-700/50 text-slate-100 rounded-lg text-sm border border-slate-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" id="searchInput" placeholder="Cari nama, email, atau telepon..." class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        </div>
        <button onclick="resetSearch()" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 border border-slate-600 text-slate-100 rounded-lg text-sm font-medium transition-colors">
            Reset
        </button>
    </div>

    <div class="overflow-x-auto bg-slate-800/50 rounded-lg border border-slate-700">
        <table class="w-full text-xs sm:text-sm text-slate-300">
            <thead class="bg-slate-700/50 border-b border-slate-600">
                <tr>
                    <th class="px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Nama</th>
                    <th class="px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Email</th>
                    <th class="hidden sm:table-cell px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Telepon</th>
                    <th class="hidden lg:table-cell px-3 sm:px-4 py-3 text-left font-semibold text-slate-200">Alamat</th>
                    <th class="px-3 sm:px-4 py-3 text-center font-semibold text-slate-200">Aksi</th>
                </tr>
            </thead>
            <tbody id="supplierTable">
                @foreach ($suppliers as $supplier)
                    <tr class="border-b border-slate-700 hover:bg-slate-700/30 transition-colors supplier-row">
                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-slate-200">{{ $supplier->nama }}</td>
                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-slate-300 text-xs sm:text-sm break-all">{{ $supplier->email }}</td>
                        <td class="hidden sm:table-cell px-3 sm:px-4 py-2 sm:py-3 text-slate-300">{{ $supplier->telepon }}</td>
                        <td class="hidden lg:table-cell px-3 sm:px-4 py-2 sm:py-3 text-slate-400 truncate">{{ $supplier->alamat }}</td>
                        <td class="px-3 sm:px-4 py-2 sm:py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="editSupplier({{ $supplier }})" class="px-2 sm:px-3 py-1 bg-amber-600/90 hover:bg-amber-600 active:bg-amber-700 text-slate-100 rounded text-xs font-medium transition-colors">
                                    <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 sm:px-3 py-1 bg-red-600/90 hover:bg-red-600 active:bg-red-700 text-slate-100 rounded text-xs font-medium transition-colors">
                                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
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

<dialog id="addModal" class="rounded-lg p-4 sm:p-6 w-11/12 sm:w-1/2 bg-slate-800 border border-slate-700 max-h-screen overflow-y-auto">
    <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4">
        @csrf
        <h2 class="text-lg sm:text-xl font-semibold text-slate-200 mb-4">Tambah Supplier</h2>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Nama</label>
            <input name="nama" placeholder="Nama" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Email</label>
            <input name="email" placeholder="Email" type="email" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Telepon</label>
            <input name="telepon" placeholder="Telepon" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Alamat</label>
            <textarea name="alamat" placeholder="Alamat" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" rows="2"></textarea>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Keterangan</label>
            <textarea name="keterangan" placeholder="Keterangan" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" rows="2"></textarea>
        </div>
        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="addModal.close()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded text-sm font-medium transition-colors">Batal</button>
            <button type="submit" class="px-4 py-2 bg-slate-600 hover:bg-slate-500 text-slate-100 rounded text-sm font-medium transition-colors">Simpan</button>
        </div>
    </form>
</dialog>

<dialog id="editModal" class="rounded-lg p-4 sm:p-6 w-11/12 sm:w-1/2 bg-slate-800 border border-slate-700 max-h-screen overflow-y-auto">
    <form method="POST" id="editForm" class="space-y-4">
        @csrf
        @method('PUT')
        <h2 class="text-lg sm:text-xl font-semibold text-slate-200 mb-4">Edit Supplier</h2>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Nama</label>
            <input name="nama" id="editNama" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Email</label>
            <input name="email" id="editEmail" type="email" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Telepon</label>
            <input name="telepon" id="editTelepon" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Alamat</label>
            <textarea name="alamat" id="editAlamat" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" rows="2"></textarea>
        </div>
        <div>
            <label class="block text-xs sm:text-sm text-slate-300 mb-2">Keterangan</label>
            <textarea name="keterangan" id="editKeterangan" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded text-slate-100 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500" rows="2"></textarea>
        </div>
        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="editModal.close()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded text-sm font-medium transition-colors">Batal</button>
            <button type="submit" class="px-4 py-2 bg-slate-600 hover:bg-slate-500 text-slate-100 rounded text-sm font-medium transition-colors">Update</button>
        </div>
    </form>
</dialog>

<script>
function editSupplier(data) {
    document.getElementById('editNama').value = data.nama
    document.getElementById('editEmail').value = data.email
    document.getElementById('editTelepon').value = data.telepon
    document.getElementById('editAlamat').value = data.alamat ?? ''
    document.getElementById('editKeterangan').value = data.keterangan ?? ''
    const form = document.getElementById('editForm')
    form.action = `/admin/suppliers/${data.id}`
    document.getElementById('editModal').showModal()
}

function filterSuppliers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase()
    const rows = document.querySelectorAll('.supplier-row')

    rows.forEach(row => {
        const text = row.textContent.toLowerCase()
        row.style.display = text.includes(searchTerm) ? '' : 'none'
    })
}

function resetSearch() {
    document.getElementById('searchInput').value = ''
    filterSuppliers()
}

document.getElementById('searchInput')?.addEventListener('keyup', filterSuppliers)
</script>
@endsection
