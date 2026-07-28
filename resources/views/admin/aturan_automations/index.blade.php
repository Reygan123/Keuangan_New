@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Aturan Otomatisasi</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola aturan automasi transaksi</p>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Aturan</span>
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="flex flex-col sm:flex-row gap-3">
            @if(count($usahas) > 1)
            <select id="usahaFilter" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                <option value="">Semua Usaha</option>
                @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                @endforeach
            </select>
            @endif
            
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari label, debit, atau kredit..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>

            <div class="flex gap-2">
                <button onclick="applyFilter()" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white rounded-xl text-[13px] font-semibold transition-colors duration-200">
                    Filter
                </button>
                <button onclick="resetFilters()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] rounded-xl text-[13px] font-semibold transition-colors duration-200 border border-[#EEF2F6] flex items-center justify-center">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Label</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Akun Debit</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Akun Kredit</th>
                        @if(count($usahas) > 1)
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]" id="tableBody">
                    @forelse ($aturans as $aturan)
                        <tr class="searchable-row hover:bg-[#F8FAFC] transition-colors" data-search="{{ strtolower($aturan->label->nama_label . ' ' . $aturan->akunDebit->name . ' ' . $aturan->akunKredit->name) }}" data-usaha="{{ $aturan->usaha_id }}">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0F172A] text-[13px]">{{ $aturan->label->nama_label }}</div>
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#64748B]">{{ $aturan->akunDebit->name }}</td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#64748B]">{{ $aturan->akunKredit->name }}</td>
                            @if(count($usahas) > 1)
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $aturan->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editAturan({{ json_encode($aturan) }})" class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-lg transition-colors text-[12px] border border-[#FDE68A]">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.aturan_automations.destroy', $aturan->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus aturan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-lg transition-colors text-[12px] border border-[#FECDD3]">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($usahas) > 1 ? 5 : 4 }}" class="px-6 py-8 text-center text-[13px] text-[#94A3B8]">Belum ada aturan otomatisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="px-6 py-8 text-center text-[13px] text-[#94A3B8] hidden">
            Tidak ada aturan yang sesuai dengan filter Anda.
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-md bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form method="POST" action="{{ route('admin.aturan_automations.store') }}" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center sticky top-0 bg-white z-10">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Tambah Aturan Baru</h2>
            <button type="button" onclick="document.getElementById('addModal').close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-5">
                @if(count($usahas) > 1)
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                    <select name="usaha_id" id="addUsahaId" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
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
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Label Transaksi <span class="text-[#E11D48]">*</span></label>
                    <select name="label_id" id="addLabelId" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Label --</option>
                        @if(isset($usahaSelected) && count($labels) > 0)
                            @foreach ($labels as $label)
                                <option value="{{ $label->id }}">{{ $label->nama_label }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Debit <span class="text-[#E11D48]">*</span></label>
                    <select name="akun_debit_id" id="addDebitId" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun --</option>
                        @if(isset($usahaSelected) && count($akuns) > 0)
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Kredit <span class="text-[#E11D48]">*</span></label>
                    <select name="akun_kredit_id" id="addKreditId" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun --</option>
                        @if(isset($usahaSelected) && count($akuns) > 0)
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC] sticky bottom-0">
            <button type="button" onclick="document.getElementById('addModal').close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editModal" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-md bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form method="POST" id="editForm" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        @method('PUT')
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center sticky top-0 bg-white z-10">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Edit Aturan</h2>
            <button type="button" onclick="document.getElementById('editModal').close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-5">
                @if(count($usahas) > 1)
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                    <select name="usaha_id" id="editUsahaId" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
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
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Label Transaksi <span class="text-[#E11D48]">*</span></label>
                    <select name="label_id" id="editLabel" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Label --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Debit <span class="text-[#E11D48]">*</span></label>
                    <select name="akun_debit_id" id="editDebit" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Akun Kredit <span class="text-[#E11D48]">*</span></label>
                    <select name="akun_kredit_id" id="editKredit" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun --</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC] sticky bottom-0">
            <button type="button" onclick="document.getElementById('editModal').close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
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
