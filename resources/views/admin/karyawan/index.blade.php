@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Data Karyawan</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Data karyawan untuk keperluan slip gaji dan payroll.</p>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <form action="{{ route('admin.karyawan.index') }}" method="GET" class="w-full md:w-auto flex">
                <select name="usaha_id" onchange="this.form.submit()"
                    class="w-full md:w-auto bg-white border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] transition-all cursor-pointer shadow-[0_2px_8px_rgba(15,23,42,0.02)]">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
            </form>
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2.5 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px] whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Karyawan</span>
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white border border-[#EEF2F6] rounded-[20px] overflow-hidden shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Posisi</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">No Rekening</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @forelse($karyawans as $k)
                    <tr class="hover:bg-[#F8FAFC]/50 transition-colors">
                        <td class="px-6 py-4 text-[13px] font-semibold text-[#0F172A]">{{ $k->nama }}</td>
                        <td class="px-6 py-4 text-[13px] font-medium text-[#334155]">{{ $k->posisi }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B]">{{ $k->divisi ?? '-' }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B]">{{ $k->no_rekening ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($k->status_kepegawaian)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#EEF2FF] text-[#3B41E3]">
                                {{ $k->status_kepegawaian }}
                            </span>
                            @else
                            <span class="text-[#94A3B8]">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button onclick='editKaryawan(@json($k))'
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#F8FAFC] text-[#3B41E3] hover:bg-[#EEF2FF] transition-colors border border-[#EEF2F6] hover:border-[#C7D2FE]" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.karyawan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus karyawan ini?')" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#FEF2F2] text-[#E11D48] hover:bg-[#FEE2E2] transition-colors border border-[#FECDD3]" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-[#F8FAFC] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-[#EEF2F6]">
                                <svg class="w-8 h-8 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-[16px] font-bold text-[#0F172A] mb-1">Belum Ada Data Karyawan</h3>
                            <p class="text-[13px] text-[#64748B]">Klik "Tambah Karyawan" untuk mulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] w-full max-w-md shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-[#EEF2F6] flex justify-between items-center bg-white rounded-t-[24px] sticky top-0 z-10">
            <h3 class="text-[18px] font-bold text-[#0F172A]">Tambah Karyawan</h3>
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#0F172A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form action="{{ route('admin.karyawan.store') }}" method="POST" id="formTambahKaryawan">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $usahaSelected }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Posisi <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="posisi" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Divisi</label>
                        <input type="text" name="divisi" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">No Rekening</label>
                        <input type="text" name="no_rekening" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">NIK</label>
                        <input type="text" name="nik" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">NPWP</label>
                        <input type="text" name="npwp" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                            <option value="">-- Pilih Status --</option>
                            <option value="TETAP">Tetap</option>
                            <option value="KONTRAK">Kontrak</option>
                            <option value="PROBATION">Probation</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-[#EEF2F6] bg-white rounded-b-[24px] flex justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">Batal</button>
            <button type="submit" form="formTambahKaryawan" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="hidden fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] w-full max-w-md shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-[#EEF2F6] flex justify-between items-center bg-white rounded-t-[24px] sticky top-0 z-10">
            <h3 class="text-[18px] font-bold text-[#0F172A]">Edit Karyawan</h3>
            <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#0F172A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="formEdit" method="POST">
                @csrf @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama" id="edit_nama" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Posisi <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="posisi" id="edit_posisi" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Divisi</label>
                        <input type="text" name="divisi" id="edit_divisi" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">No Rekening</label>
                        <input type="text" name="no_rekening" id="edit_no_rekening" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">NIK</label>
                        <input type="text" name="nik" id="edit_nik" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">NPWP</label>
                        <input type="text" name="npwp" id="edit_npwp" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Status Kepegawaian</label>
                        <select name="status_kepegawaian" id="edit_status_kepegawaian" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                            <option value="">-- Pilih Status --</option>
                            <option value="TETAP">Tetap</option>
                            <option value="KONTRAK">Kontrak</option>
                            <option value="PROBATION">Probation</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" id="edit_tanggal_bergabung" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>
                </div>
            </form>
        </div>
        
        <div class="p-6 border-t border-[#EEF2F6] bg-white rounded-b-[24px] flex justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">Batal</button>
            <button type="submit" form="formEdit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">Update</button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #CBD5E1;
        border-radius: 20px;
    }
</style>

<script>
function editKaryawan(k) {
    document.getElementById('formEdit').action = `/admin/karyawan/${k.id}`;
    document.getElementById('edit_nama').value = k.nama || '';
    document.getElementById('edit_posisi').value = k.posisi || '';
    document.getElementById('edit_divisi').value = k.divisi || '';
    document.getElementById('edit_no_rekening').value = k.no_rekening || '';
    document.getElementById('edit_nik').value = k.nik || '';
    document.getElementById('edit_npwp').value = k.npwp || '';
    document.getElementById('edit_status_kepegawaian').value = k.status_kepegawaian || '';
    document.getElementById('edit_tanggal_bergabung').value = k.tanggal_bergabung ? k.tanggal_bergabung.split('T')[0] : '';
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>
@endsection
