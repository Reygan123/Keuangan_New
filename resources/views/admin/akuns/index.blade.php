@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Daftar Akun</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola daftar akun Anda</p>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Akun</span>
        </button>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row gap-3">
                @if (isset($usahas) && count($usahas) > 1)
                <select id="usahaFilter" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">Semua Usaha</option>
                    @foreach ($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                @endif
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari akun..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <select id="filterKlasifikasi" class="flex-1 appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer">
                    <option value="">Semua Klasifikasi</option>
                    <option value="ASET">ASET</option>
                    <option value="KEWAJIBAN">KEWAJIBAN</option>
                    <option value="EKUITAS">EKUITAS</option>
                    <option value="PENDAPATAN">PENDAPATAN</option>
                    <option value="BEBAN">BEBAN</option>
                </select>

                <select id="filterAktivitas" class="flex-1 appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer">
                    <option value="">Semua Aktivitas</option>
                    <option value="OPERASI">OPERASI</option>
                    <option value="INVESTASI">INVESTASI</option>
                    <option value="PENDANAAN">PENDANAAN</option>
                    <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                </select>

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
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Nama Akun</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Saldo</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Klasifikasi</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Sub Klasifikasi</th>
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Aktivitas Kas</th>
                        <th class="hidden md:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Kelompok</th>
                        @if (isset($usahas) && count($usahas) > 1)
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]" id="tableBody">
                    @forelse ($akuns as $akun)
                        <tr class="hover:bg-[#F8FAFC] transition-colors data-row"
                            data-search="{{ strtolower($akun->name . ' ' . $akun->id . ' ' . ($akun->kode ?? '')) }}"
                            data-klasifikasi="{{ $akun->klasifikasi }}" data-aktivitas="{{ $akun->aktivitas_kas }}"
                            data-usaha="{{ $akun->usaha_id }}">
                            <td class="px-6 py-4 text-[13px] font-medium text-[#64748B]">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#64748B] font-mono">{{ $akun->kode ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0F172A] text-[13px]">{{ $akun->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ number_format($akun->saldo, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-lg uppercase tracking-wide
                                @if ($akun->klasifikasi == 'ASET') bg-[#EFF6FF] text-[#3B82F6]
                                @elseif($akun->klasifikasi == 'KEWAJIBAN') bg-[#FEF2F2] text-[#EF4444]
                                @elseif($akun->klasifikasi == 'EKUITAS') bg-[#ECFDF5] text-[#10B981]
                                @elseif($akun->klasifikasi == 'PENDAPATAN') bg-[#F0FDF4] text-[#22C55E]
                                @else bg-[#FFF7ED] text-[#F97316] @endif">
                                    {{ $akun->klasifikasi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-lg uppercase tracking-wide
                                @if ($akun->sub_klasifikasi == 'LANCAR') bg-[#EFF6FF] text-[#3B82F6]
                                @elseif($akun->sub_klasifikasi == 'TETAP') bg-[#F0F9FF] text-[#0EA5E9]
                                @elseif($akun->sub_klasifikasi == 'JANGKA PANJANG') bg-[#FEF2F2] text-[#EF4444]
                                @else bg-transparent text-transparent @endif">
                                    {{ $akun->sub_klasifikasi }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $akun->aktivitas_kas }}
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $akun->nama_kelompok ?? '-' }}
                            </td>
                            @if (isset($usahas) && count($usahas) > 1)
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $akun->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.laporan.grafik_transaksi', ['usaha_id' => $akun->usaha_id, 'akun_id' => $akun->id]) }}" class="px-3 py-1.5 bg-[#F0FDF4] hover:bg-[#DCFCE7] text-[#16A34A] font-semibold rounded-lg transition-colors text-[12px] border border-[#BBF7D0]">
                                        Grafik
                                    </a>
                                    <button onclick="editAkun({{ json_encode($akun) }})" class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-lg transition-colors text-[12px] border border-[#FDE68A]">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.akuns.destroy', $akun->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
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
                            <td colspan="{{ (isset($usahas) && count($usahas) > 1) ? 10 : 9 }}" class="px-6 py-8 text-center text-[13px] text-[#94A3B8]">Belum ada akun yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="px-6 py-8 text-center text-[13px] text-[#94A3B8] hidden">
            Tidak ada akun yang sesuai dengan filter Anda.
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-2xl bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form method="POST" action="{{ route('admin.akuns.store') }}" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center sticky top-0 bg-white z-10">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Tambah Akun Baru</h2>
            <button type="button" onclick="addModal.close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-6">
                <input type="hidden" name="kode_otomatis" value="1">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if (isset($usahas) && count($usahas) > 1)
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                        <select name="usaha_id" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">-- Pilih Usaha --</option>
                            @foreach ($usahas as $usaha)
                                <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @elseif(isset($usahas) && count($usahas) > 0)
                    <input type="hidden" name="usaha_id" value="{{ $usahas->first()->id ?? '' }}">
                    @endif

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Akun <span class="text-[#E11D48]">*</span></label>
                        <input name="name" placeholder="Masukkan nama akun" required
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Saldo Awal <span class="text-[#E11D48]">*</span></label>
                        <input name="saldo" placeholder="0" type="number" step="0.01" required
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Klasifikasi <span class="text-[#E11D48]">*</span></label>
                        <select name="klasifikasi" id="addKlasifikasi" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Klasifikasi</option>
                            <option value="ASET">ASET</option>
                            <option value="KEWAJIBAN">KEWAJIBAN</option>
                            <option value="EKUITAS">EKUITAS</option>
                            <option value="PENDAPATAN">PENDAPATAN</option>
                            <option value="BEBAN">BEBAN</option>
                        </select>
                    </div>

                    <div id="addSubKlasifikasiWrapper" style="display: none;">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Sub Klasifikasi</label>
                        <select name="sub_klasifikasi" id="addSubKlasifikasi"
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Sub Klasifikasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Aktivitas Kas <span class="text-[#E11D48]">*</span></label>
                        <select name="aktivitas_kas" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Aktivitas Kas</option>
                            <option value="OPERASI">OPERASI</option>
                            <option value="INVESTASI">INVESTASI</option>
                            <option value="PENDANAAN">PENDANAAN</option>
                            <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Kelompok <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama_kelompok" placeholder="Nama kelompok" required
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC] sticky bottom-0">
            <button type="button" onclick="addModal.close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editModal" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-2xl bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form method="POST" id="editForm" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        @method('PUT')
        
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center sticky top-0 bg-white z-10">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Edit Akun</h2>
            <button type="button" onclick="editModal.close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kode Akun <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="kode" id="editKode" required
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Akun <span class="text-[#E11D48]">*</span></label>
                        <input name="name" id="editName" required
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Klasifikasi <span class="text-[#E11D48]">*</span></label>
                        <select name="klasifikasi" id="editKlasifikasi" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Klasifikasi</option>
                            <option value="ASET">ASET</option>
                            <option value="KEWAJIBAN">KEWAJIBAN</option>
                            <option value="EKUITAS">EKUITAS</option>
                            <option value="PENDAPATAN">PENDAPATAN</option>
                            <option value="BEBAN">BEBAN</option>
                        </select>
                    </div>

                    <div id="editSubKlasifikasiWrapper" style="display: none;">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Sub Klasifikasi</label>
                        <select name="sub_klasifikasi" id="editSubKlasifikasi"
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Sub Klasifikasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Aktivitas Kas <span class="text-[#E11D48]">*</span></label>
                        <select name="aktivitas_kas" id="editAktivitasKas" required
                                class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Aktivitas Kas</option>
                            <option value="OPERASI">OPERASI</option>
                            <option value="INVESTASI">INVESTASI</option>
                            <option value="PENDANAAN">PENDANAAN</option>
                            <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Kelompok <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama_kelompok" id="editNamaKelompok" required
                               class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC] sticky bottom-0">
            <button type="button" onclick="editModal.close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Update
            </button>
        </div>
    </form>
</dialog>

<script>
    const subKlasifikasiOptions = {
        'ASET': ['LANCAR', 'TETAP'],
        'KEWAJIBAN': ['LANCAR', 'JANGKA PANJANG'],
        'EKUITAS': ['MODAL'],
        'PENDAPATAN': [''],
        'BEBAN': ['']
    };

    function updateSubKlasifikasi(klasifikasiSelect, subKlasifikasiSelect, wrapperElement, currentValue = '') {
        const klasifikasi = klasifikasiSelect.value;
        subKlasifikasiSelect.innerHTML = '<option value="">Pilih Sub Klasifikasi</option>';

        if (klasifikasi === 'ASET' || klasifikasi === 'KEWAJIBAN') {
            wrapperElement.style.display = 'block';
            subKlasifikasiSelect.setAttribute('required', 'required');

            if (subKlasifikasiOptions[klasifikasi]) {
                subKlasifikasiOptions[klasifikasi].forEach(option => {
                    if (option !== '') {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = option;
                        if (option === currentValue) {
                            opt.selected = true;
                        }
                        subKlasifikasiSelect.appendChild(opt);
                    }
                });
            }
        } else {
            wrapperElement.style.display = 'none';
            subKlasifikasiSelect.removeAttribute('required');
            subKlasifikasiSelect.value = '';
        }
    }

    document.getElementById('addKlasifikasi').addEventListener('change', function() {
        updateSubKlasifikasi(
            this,
            document.getElementById('addSubKlasifikasi'),
            document.getElementById('addSubKlasifikasiWrapper')
        );
    });

    document.getElementById('editKlasifikasi').addEventListener('change', function() {
        updateSubKlasifikasi(
            this,
            document.getElementById('editSubKlasifikasi'),
            document.getElementById('editSubKlasifikasiWrapper')
        );
    });

    function editAkun(data) {
        document.getElementById('editKode').value = data.kode || '';
        document.getElementById('editName').value = data.name;
        document.getElementById('editKlasifikasi').value = data.klasifikasi;
        document.getElementById('editAktivitasKas').value = data.aktivitas_kas;
        document.getElementById('editNamaKelompok').value = data.nama_kelompok || '';

        updateSubKlasifikasi(
            document.getElementById('editKlasifikasi'),
            document.getElementById('editSubKlasifikasi'),
            document.getElementById('editSubKlasifikasiWrapper'),
            data.sub_klasifikasi || ''
        );

        const form = document.getElementById('editForm');
        form.action = `/admin/akuns/${data.id}`;
        document.getElementById('editModal').showModal();
    }

    function applyFilter() {
        const usahaFilter = document.getElementById('usahaFilter');
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const klasifikasi = document.getElementById('filterKlasifikasi').value;
        const aktivitas = document.getElementById('filterAktivitas').value;
        const usahaId = usahaFilter ? usahaFilter.value : '';
        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const matchSearch = row.dataset.search.includes(searchValue);
            const matchKlasifikasi = !klasifikasi || row.dataset.klasifikasi === klasifikasi;
            const matchAktivitas = !aktivitas || row.dataset.aktivitas === aktivitas;
            const matchUsaha = !usahaId || row.dataset.usaha == usahaId;

            if (matchSearch && matchKlasifikasi && matchAktivitas && matchUsaha) {
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
        document.getElementById('filterKlasifikasi').value = '';
        document.getElementById('filterAktivitas').value = '';
        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter) {
            usahaFilter.value = '';
        }
        applyFilter();
    }

    function filterTable() {
        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter && usahaFilter.value) {
            const usahaId = usahaFilter.value;
            window.location.href = '{{ route('admin.akuns.index') }}?usaha_id=' + usahaId;
        } else {
            applyFilter();
        }
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilter);
    document.getElementById('filterKlasifikasi').addEventListener('change', applyFilter);
    document.getElementById('filterAktivitas').addEventListener('change', applyFilter);
    const usahaFilter = document.getElementById('usahaFilter');
    if (usahaFilter) {
        usahaFilter.addEventListener('change', filterTable);
    }
</script>
@endsection
