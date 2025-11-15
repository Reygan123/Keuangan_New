@extends('layouts.admin.app')

@section('content')
<div class="p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
        <div>
            <h1 class="text-lg font-semibold text-slate-100">Aturan Otomatisasi</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola aturan automasi transaksi</p>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="px-3 py-2 bg-blue-700 hover:bg-blue-600 text-white text-xs font-medium rounded transition flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-500 bg-opacity-20 text-green-300 text-xs rounded border border-green-500 border-opacity-30 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-4 flex gap-2">
        <div class="flex-1 relative">
            <input type="text" id="searchInput" placeholder="Cari label, debit, atau kredit..." class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 pl-8 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
            <svg class="w-4 h-4 absolute left-2 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    <div class="overflow-x-auto rounded border border-slate-700">
        <table class="w-full text-xs">
            <thead class="bg-slate-800 border-b border-slate-700">
                <tr>
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium">Label</th>
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium">Akun Debit</th>
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium">Akun Kredit</th>
                    <th class="px-3 py-2.5 text-center text-slate-300 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($aturans as $aturan)
                    <tr class="border-b border-slate-700 hover:bg-slate-800 hover:bg-opacity-50 transition searchable-row" data-search="{{ strtolower($aturan->label->nama_label . ' ' . $aturan->akunDebit->name . ' ' . $aturan->akunKredit->name) }}">
                        <td class="px-3 py-2.5 text-slate-300">{{ $aturan->label->nama_label }}</td>
                        <td class="px-3 py-2.5 text-slate-300">{{ $aturan->akunDebit->name }}</td>
                        <td class="px-3 py-2.5 text-slate-300">{{ $aturan->akunKredit->name }}</td>
                        <td class="px-3 py-2.5 text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="editAturan({{ $aturan }})" class="px-2 py-1 bg-slate-700 hover:bg-amber-600 text-slate-100 text-xs rounded transition" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></path></svg>
                                </button>
                                <form action="{{ route('admin.aturan_automations.destroy', $aturan->id) }}" method="POST" onsubmit="return confirm('Hapus aturan ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-slate-700 hover:bg-red-600 text-slate-100 text-xs rounded transition" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="noResults" class="hidden p-6 text-center text-slate-400 text-xs">
            Tidak ada aturan yang ditemukan
        </div>
    </div>
</div>

<dialog id="addModal" class="rounded-lg p-5 w-11/12 sm:w-96 bg-slate-800 border border-slate-700 backdrop:bg-black backdrop:bg-opacity-50">
    <form method="POST" action="{{ route('admin.aturan_automations.store') }}" class="space-y-3">
        @csrf
        <h2 class="text-base font-semibold text-slate-100 mb-4">Tambah Aturan Baru</h2>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Label Transaksi</label>
            <select name="label_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition" required>
                <option value="">-- Pilih Label --</option>
                @foreach ($labels as $label)
                    <option value="{{ $label->id }}">{{ $label->nama_label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Debit</label>
            <select name="akun_debit_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition" required>
                <option value="">-- Pilih Akun --</option>
                @foreach ($akuns as $akun)
                    <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Kredit</label>
            <select name="akun_kredit_id" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition" required>
                <option value="">-- Pilih Akun --</option>
                @foreach ($akuns as $akun)
                    <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-3">
            <button type="button" onclick="addModal.close()" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-xs rounded transition">Batal</button>
            <button type="submit" class="px-3 py-2 bg-blue-700 hover:bg-blue-600 text-white text-xs font-medium rounded transition">Simpan</button>
        </div>
    </form>
</dialog>

<dialog id="editModal" class="rounded-lg p-5 w-11/12 sm:w-96 bg-slate-850 border border-slate-700 backdrop:bg-black backdrop:bg-opacity-50">
    <form method="POST" id="editForm" class="space-y-3">
        @csrf
        @method('PUT')
        <h2 class="text-base font-semibold text-slate-100 mb-4">Edit Aturan</h2>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Label Transaksi</label>
            <select name="label_id" id="editLabel" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition" required>
                <option value="">-- Pilih Label --</option>
                @foreach ($labels as $label)
                    <option value="{{ $label->id }}">{{ $label->nama_label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Debit</label>
            <select name="akun_debit_id" id="editDebit" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition" required>
                <option value="">-- Pilih Akun --</option>
                @foreach ($akuns as $akun)
                    <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Kredit</label>
            <select name="akun_kredit_id" id="editKredit" class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition" required>
                <option value="">-- Pilih Akun --</option>
                @foreach ($akuns as $akun)
                    <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-3">
            <button type="button" onclick="editModal.close()" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-xs rounded transition">Batal</button>
            <button type="submit" class="px-3 py-2 bg-blue-700 hover:bg-blue-600 text-white text-xs font-medium rounded transition">Update</button>
        </div>
    </form>
</dialog>

<script>
function editAturan(data) {
    document.getElementById('editLabel').value = data.label_id
    document.getElementById('editDebit').value = data.akun_debit_id
    document.getElementById('editKredit').value = data.akun_kredit_id
    const form = document.getElementById('editForm')
    form.action = `/admin/aturan_automations/${data.id}`
    document.getElementById('editModal').showModal()
}

const searchInput = document.getElementById('searchInput')
const tableBody = document.getElementById('tableBody')
const noResults = document.getElementById('noResults')
const rows = document.querySelectorAll('.searchable-row')

searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase()
    let visibleCount = 0

    rows.forEach(row => {
        const searchText = row.getAttribute('data-search')
        if (searchText.includes(query)) {
            row.classList.remove('hidden')
            visibleCount++
        } else {
            row.classList.add('hidden')
        }
    })

    noResults.classList.toggle('hidden', visibleCount > 0)
})
</script>
@endsection
