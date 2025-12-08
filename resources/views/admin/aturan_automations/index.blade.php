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

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-500 bg-opacity-20 text-red-300 text-xs rounded border border-red-500 border-opacity-30 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="mb-4 flex flex-col sm:flex-row gap-2">
        @if(count($usahas) > 1)
        <select id="usahaFilter" class="bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition min-w-[180px]">
            <option value="">Semua Usaha</option>
            @foreach($usahas as $usaha)
                <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
            @endforeach
        </select>
        @endif
        <div class="flex-1 relative">
            <input type="text" id="searchInput" placeholder="Cari label, debit, atau kredit..." class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 pl-8 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
            <svg class="w-4 h-4 absolute left-2 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <button onclick="applyFilter()" class="px-3 py-2 bg-blue-700 hover:bg-blue-600 text-white text-xs font-medium rounded transition whitespace-nowrap">
            Filter
        </button>
        <button onclick="resetFilters()" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-xs rounded transition whitespace-nowrap">
            Reset
        </button>
    </div>

    <div class="overflow-x-auto rounded border border-slate-700">
        <table class="w-full text-xs">
            <thead class="bg-slate-800 border-b border-slate-700">
                <tr>
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium">Label</th>
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium">Akun Debit</th>
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium">Akun Kredit</th>
                    @if(count($usahas) > 1)
                    <th class="px-3 py-2.5 text-left text-slate-300 font-medium hidden lg:table-cell">Usaha</th>
                    @endif
                    <th class="px-3 py-2.5 text-center text-slate-300 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($aturans as $aturan)
                    <tr class="border-b border-slate-700 hover:bg-slate-800 hover:bg-opacity-50 transition searchable-row" data-search="{{ strtolower($aturan->label->nama_label . ' ' . $aturan->akunDebit->name . ' ' . $aturan->akunKredit->name) }}" data-usaha="{{ $aturan->usaha_id }}">
                        <td class="px-3 py-2.5 text-slate-300">{{ $aturan->label->nama_label }}</td>
                        <td class="px-3 py-2.5 text-slate-300">{{ $aturan->akunDebit->name }}</td>
                        <td class="px-3 py-2.5 text-slate-300">{{ $aturan->akunKredit->name }}</td>
                        @if(count($usahas) > 1)
                        <td class="px-3 py-2.5 text-slate-300 hidden lg:table-cell">{{ $aturan->usaha->nama ?? '-' }}</td>
                        @endif
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

        @if(count($usahas) > 1)
        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Usaha</label>
            <select name="usaha_id" id="addUsahaId" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Usaha --</option>
                @foreach ($usahas as $usaha)
                    <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="usaha_id" id="addUsahaId" value="{{ $usahas->first()->id ?? '' }}">
        @endif

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Label Transaksi</label>
            <select name="label_id" id="addLabelId" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Label --</option>
                @if($usahaSelected && count($labels) > 0)
                    @foreach ($labels as $label)
                        <option value="{{ $label->id }}">{{ $label->nama_label }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Debit</label>
            <select name="akun_debit_id" id="addDebitId" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Akun --</option>
                @if($usahaSelected && count($akuns) > 0)
                    @foreach ($akuns as $akun)
                        <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Kredit</label>
            <select name="akun_kredit_id" id="addKreditId" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Akun --</option>
                @if($usahaSelected && count($akuns) > 0)
                    @foreach ($akuns as $akun)
                        <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                    @endforeach
                @endif
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

        @if(count($usahas) > 1)
        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Usaha</label>
            <select name="usaha_id" id="editUsahaId" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Usaha --</option>
                @foreach ($usahas as $usaha)
                    <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="usaha_id" id="editUsahaId" value="{{ $usahas->first()->id ?? '' }}">
        @endif

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Label Transaksi</label>
            <select name="label_id" id="editLabel" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Label --</option>
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Debit</label>
            <select name="akun_debit_id" id="editDebit" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Akun --</option>
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-300 block mb-1.5">Akun Kredit</label>
            <select name="akun_kredit_id" id="editKredit" required class="w-full bg-slate-700 border border-slate-600 text-slate-100 text-xs px-3 py-2 rounded focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 transition">
                <option value="">-- Pilih Akun --</option>
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
    document.getElementById('editLabel').innerHTML = '<option value="">-- Pilih Label --</option>';
    document.getElementById('editDebit').innerHTML = '<option value="">-- Pilih Akun --</option>';
    document.getElementById('editKredit').innerHTML = '<option value="">-- Pilih Akun --</option>';

    const usahaId = data.usaha_id || '{{ $usahaSelected }}';
    const editUsahaId = document.getElementById('editUsahaId');

    if (editUsahaId) {
        editUsahaId.value = usahaId;
    }

    fetch(`/admin/aturan-automations-by-usaha?usaha_id=${usahaId}`)
        .then(response => response.json())
        .then(dataUsaha => {
            dataUsaha.labels.forEach(label => {
                const option = document.createElement('option');
                option.value = label.id;
                option.textContent = label.nama_label;
                if (label.id == data.label_id) {
                    option.selected = true;
                }
                document.getElementById('editLabel').appendChild(option);
            });

            dataUsaha.akuns.forEach(akun => {
                const optionDebit = document.createElement('option');
                optionDebit.value = akun.id;
                optionDebit.textContent = akun.name;
                if (akun.id == data.akun_debit_id) {
                    optionDebit.selected = true;
                }
                document.getElementById('editDebit').appendChild(optionDebit);

                const optionKredit = document.createElement('option');
                optionKredit.value = akun.id;
                optionKredit.textContent = akun.name;
                if (akun.id == data.akun_kredit_id) {
                    optionKredit.selected = true;
                }
                document.getElementById('editKredit').appendChild(optionKredit);
            });
        });

    const form = document.getElementById('editForm');
    form.action = `/admin/aturan_automations/${data.id}`;
    document.getElementById('editModal').showModal();
}

function applyFilter() {
    const usahaId = document.getElementById('usahaFilter')?.value || '';
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.searchable-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const matchSearch = row.dataset.search.includes(searchValue);
        const matchUsaha = !usahaId || row.dataset.usaha == usahaId;

        if (matchSearch && matchUsaha) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    const usahaFilter = document.getElementById('usahaFilter');
    if (usahaFilter) {
        usahaFilter.value = '';
    }
    applyFilter();
}

function filterByUsaha() {
    const usahaId = document.getElementById('usahaFilter')?.value || '';
    window.location.href = '{{ route("admin.aturan_automations.index") }}' + (usahaId ? '?usaha_id=' + usahaId : '');
}

const searchInput = document.getElementById('searchInput');
const usahaFilter = document.getElementById('usahaFilter');
const tableBody = document.getElementById('tableBody');
const noResults = document.getElementById('noResults');
const rows = document.querySelectorAll('.searchable-row');

searchInput.addEventListener('input', applyFilter);

if (usahaFilter) {
    usahaFilter.addEventListener('change', filterByUsaha);
}

document.addEventListener('DOMContentLoaded', function() {
    const addUsahaId = document.getElementById('addUsahaId');
    if (addUsahaId) {
        addUsahaId.addEventListener('change', function() {
            const usahaId = this.value;
            const addLabelId = document.getElementById('addLabelId');
            const addDebitId = document.getElementById('addDebitId');
            const addKreditId = document.getElementById('addKreditId');

            if (!usahaId) {
                addLabelId.innerHTML = '<option value="">-- Pilih Label --</option>';
                addDebitId.innerHTML = '<option value="">-- Pilih Akun --</option>';
                addKreditId.innerHTML = '<option value="">-- Pilih Akun --</option>';
                return;
            }

            fetch(`/admin/aturan-automations-by-usaha?usaha_id=${usahaId}`)
                .then(response => response.json())
                .then(data => {
                    let labelOptions = '<option value="">-- Pilih Label --</option>';
                    let akunOptions = '<option value="">-- Pilih Akun --</option>';

                    data.labels.forEach(label => {
                        labelOptions += `<option value="${label.id}">${label.nama_label}</option>`;
                    });

                    data.akuns.forEach(akun => {
                        akunOptions += `<option value="${akun.id}">${akun.name}</option>`;
                    });

                    addLabelId.innerHTML = labelOptions;
                    addDebitId.innerHTML = akunOptions;
                    addKreditId.innerHTML = akunOptions;
                });
        });

        const editUsahaId = document.getElementById('editUsahaId');
        if (editUsahaId) {
            editUsahaId.addEventListener('change', function() {
                const usahaId = this.value;
                const editLabel = document.getElementById('editLabel');
                const editDebit = document.getElementById('editDebit');
                const editKredit = document.getElementById('editKredit');

                if (!usahaId) {
                    editLabel.innerHTML = '<option value="">-- Pilih Label --</option>';
                    editDebit.innerHTML = '<option value="">-- Pilih Akun --</option>';
                    editKredit.innerHTML = '<option value="">-- Pilih Akun --</option>';
                    return;
                }

                fetch(`/admin/aturan-automations-by-usaha?usaha_id=${usahaId}`)
                    .then(response => response.json())
                    .then(data => {
                        let labelOptions = '<option value="">-- Pilih Label --</option>';
                        let akunOptions = '<option value="">-- Pilih Akun --</option>';

                        data.labels.forEach(label => {
                            labelOptions += `<option value="${label.id}">${label.nama_label}</option>`;
                        });

                        data.akuns.forEach(akun => {
                            akunOptions += `<option value="${akun.id}">${akun.name}</option>`;
                        });

                        editLabel.innerHTML = labelOptions;
                        editDebit.innerHTML = akunOptions;
                        editKredit.innerHTML = akunOptions;
                    });
            });
        }
    }

    const currentUsahaId = addUsahaId ? addUsahaId.value : '{{ $usahaSelected }}';
    if (currentUsahaId) {
        fetch(`/admin/aturan-automations-by-usaha?usaha_id=${currentUsahaId}`)
            .then(response => response.json())
            .then(data => {
                let labelOptions = '<option value="">-- Pilih Label --</option>';
                let akunOptions = '<option value="">-- Pilih Akun --</option>';

                data.labels.forEach(label => {
                    labelOptions += `<option value="${label.id}">${label.nama_label}</option>`;
                });

                data.akuns.forEach(akun => {
                    akunOptions += `<option value="${akun.id}">${akun.name}</option>`;
                });

                document.getElementById('addLabelId').innerHTML = labelOptions;
                document.getElementById('addDebitId').innerHTML = akunOptions;
                document.getElementById('addKreditId').innerHTML = akunOptions;
            });
    }
});
</script>
@endsection
