@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Aturan Otomatisasi</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">Pemetaan otomatis jurnal debit & kredit saat transaksi dicatat</p>
            </div>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Aturan</span>
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-50/80 border border-rose-200/80 text-rose-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col sm:flex-row gap-3">
            @if(count($usahas) > 1)
            <select id="usahaFilter" class="appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer min-w-[180px]">
                <option value="">Semua Usaha</option>
                @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                @endforeach
            </select>
            @endif
            
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari label, akun debit, atau kredit..." class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>

            <div class="flex gap-2">
                <button onclick="applyFilter()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-[13px] font-semibold shadow-xs transition-all duration-150">
                    Filter
                </button>
                <button onclick="resetFilters()" class="px-4 py-2 bg-slate-100/80 hover:bg-slate-200/80 active:scale-95 text-slate-700 rounded-xl text-xs sm:text-[13px] font-semibold border border-slate-200/80 transition-all duration-150 flex items-center justify-center">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/60">
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Label Transaksi</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Akun Debit</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Akun Kredit</th>
                        @if(count($usahas) > 1)
                        <th class="hidden lg:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="tableBody">
                    @forelse ($aturans as $aturan)
                        <tr class="searchable-row hover:bg-slate-50/60 transition-colors" data-search="{{ strtolower($aturan->label->nama_label . ' ' . $aturan->akunDebit->name . ' ' . $aturan->akunKredit->name) }}" data-usaha="{{ $aturan->usaha_id }}">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $aturan->label->nama_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-800">
                                <span class="text-slate-400 font-mono text-xs mr-1">{{ $aturan->akunDebit->kode ?? '' }}</span>
                                {{ $aturan->akunDebit->name }}
                            </td>
                            <td class="px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-800">
                                <span class="text-slate-400 font-mono text-xs mr-1">{{ $aturan->akunKredit->kode ?? '' }}</span>
                                {{ $aturan->akunKredit->name }}
                            </td>
                            @if(count($usahas) > 1)
                            <td class="hidden lg:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">
                                {{ $aturan->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="editAturan({{ json_encode($aturan) }})" class="px-2.5 py-1.5 bg-slate-100/80 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-all text-xs border border-slate-200/60 active:scale-95">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.aturan_automations.destroy', $aturan->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus aturan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50/80 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-all text-xs border border-rose-200/60 active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($usahas) > 1 ? 5 : 4 }}" class="px-6 py-10 text-center text-xs sm:text-[13px] text-slate-400">Belum ada aturan otomatisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="px-6 py-8 text-center text-xs sm:text-[13px] text-slate-400 hidden">
            Tidak ada aturan yang sesuai dengan filter Anda.
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-md bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form method="POST" action="{{ route('admin.aturan_automations.store') }}" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Tambah Aturan Baru</h2>
            </div>
            <button type="button" onclick="document.getElementById('addModal').close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-4">
                @if(count($usahas) > 1)
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha <span class="text-rose-500">*</span></label>
                    <select name="usaha_id" id="addUsahaId" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Label Transaksi <span class="text-rose-500">*</span></label>
                    <select name="label_id" id="addLabelId" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Label --</option>
                        @if(isset($usahaSelected) && count($labels) > 0)
                            @foreach ($labels as $label)
                                <option value="{{ $label->id }}">{{ $label->nama_label }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akun Debit <span class="text-rose-500">*</span></label>
                    <select name="akun_debit_id" id="addDebitId" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun Debit --</option>
                        @if(isset($usahaSelected) && count($akuns) > 0)
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akun Kredit <span class="text-rose-500">*</span></label>
                    <select name="akun_kredit_id" id="addKreditId" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun Kredit --</option>
                        @if(isset($usahaSelected) && count($akuns) > 0)
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50 sticky bottom-0">
            <button type="button" onclick="document.getElementById('addModal').close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editModal" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-md bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form method="POST" id="editForm" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        @method('PUT')
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Edit Aturan Otomatisasi</h2>
            </div>
            <button type="button" onclick="document.getElementById('editModal').close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-4">
                @if(count($usahas) > 1)
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha <span class="text-rose-500">*</span></label>
                    <select name="usaha_id" id="editUsahaId" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Label Transaksi <span class="text-rose-500">*</span></label>
                    <select name="label_id" id="editLabel" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Label --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akun Debit <span class="text-rose-500">*</span></label>
                    <select name="akun_debit_id" id="editDebit" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun Debit --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akun Kredit <span class="text-rose-500">*</span></label>
                    <select name="akun_kredit_id" id="editKredit" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun Kredit --</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50 sticky bottom-0">
            <button type="button" onclick="document.getElementById('editModal').close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Update
            </button>
        </div>
    </form>
</dialog>

<script>
function editAturan(data) {
    document.getElementById('editLabel').innerHTML = '<option value="">-- Pilih Label --</option>';
    document.getElementById('editDebit').innerHTML = '<option value="">-- Pilih Akun --</option>';
    document.getElementById('editKredit').innerHTML = '<option value="">-- Pilih Akun --</option>';

    const usahaId = data.usaha_id || '{{ $usahaSelected ?? "" }}';
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
                if (label.id == data.label_id) option.selected = true;
                document.getElementById('editLabel').appendChild(option);
            });

            dataUsaha.akuns.forEach(akun => {
                const optionDebit = document.createElement('option');
                optionDebit.value = akun.id;
                optionDebit.textContent = akun.name;
                if (akun.id == data.akun_debit_id) optionDebit.selected = true;
                document.getElementById('editDebit').appendChild(optionDebit);

                const optionKredit = document.createElement('option');
                optionKredit.value = akun.id;
                optionKredit.textContent = akun.name;
                if (akun.id == data.akun_kredit_id) optionKredit.selected = true;
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
    if (usahaFilter) usahaFilter.value = '';
    applyFilter();
}

function filterByUsaha() {
    const usahaId = document.getElementById('usahaFilter')?.value || '';
    window.location.href = '{{ route("admin.aturan_automations.index") }}' + (usahaId ? '?usaha_id=' + usahaId : '');
}

document.getElementById('searchInput').addEventListener('input', applyFilter);
const usahaFilter = document.getElementById('usahaFilter');
if (usahaFilter) usahaFilter.addEventListener('change', filterByUsaha);

document.addEventListener('DOMContentLoaded', function() {
    const addUsahaId = document.getElementById('addUsahaId');
    
    function loadUsahaData(usahaId, labelSelectId, debitSelectId, kreditSelectId) {
        if (!usahaId) {
            document.getElementById(labelSelectId).innerHTML = '<option value="">-- Pilih Label --</option>';
            document.getElementById(debitSelectId).innerHTML = '<option value="">-- Pilih Akun --</option>';
            document.getElementById(kreditSelectId).innerHTML = '<option value="">-- Pilih Akun --</option>';
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

                document.getElementById(labelSelectId).innerHTML = labelOptions;
                document.getElementById(debitSelectId).innerHTML = akunOptions;
                document.getElementById(kreditSelectId).innerHTML = akunOptions;
            });
    }

    if (addUsahaId) {
        addUsahaId.addEventListener('change', function() {
            loadUsahaData(this.value, 'addLabelId', 'addDebitId', 'addKreditId');
        });
    }

    const editUsahaId = document.getElementById('editUsahaId');
    if (editUsahaId) {
        editUsahaId.addEventListener('change', function() {
            loadUsahaData(this.value, 'editLabel', 'editDebit', 'editKredit');
        });
    }

    const currentUsahaId = addUsahaId ? addUsahaId.value : '{{ $usahaSelected ?? "" }}';
    if (currentUsahaId) {
        loadUsahaData(currentUsahaId, 'addLabelId', 'addDebitId', 'addKreditId');
    }
});
</script>
@endsection
