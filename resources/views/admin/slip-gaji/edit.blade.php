@extends('layouts.admin.app')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] tracking-tight">Edit Slip Gaji</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Perbarui rincian slip gaji No. <strong class="text-[#0F172A]">{{ $slipGaji->nomor_slip }}</strong></p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('admin.slip-gaji.preview-pdf', $slipGaji) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 text-[13px] font-medium text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] hover:bg-[#DBEAFE] rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Preview PDF
            </a>
            <a href="{{ route('admin.slip-gaji.index', ['usaha_id' => $usahaSelected]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-[13px] font-medium text-[#475569] bg-white border border-[#E2E8F0] hover:bg-[#F8FAFC] rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#B91C1C] rounded-[18px] text-[13px] font-medium shadow-sm">
        <div class="font-semibold mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/><circle cx="12" cy="16" r="0.5" fill="currentColor"/></svg>
            Terdapat beberapa kesalahan pengisian:
        </div>
        <ul class="list-disc pl-5 space-y-0.5 text-[12.5px]">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.slip-gaji.update', $slipGaji) }}" method="POST" class="space-y-6" id="slipGajiForm">
        @csrf
        @method('PUT')

        <!-- 1. INFORMASI DASAR & PAYROLL META -->
        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)] space-y-5">
            <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>
                    <h2 class="text-[15px] font-bold text-[#0F172A]">1. Informasi Karyawan & Periode Payroll</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-[#64748B] font-medium bg-[#F1F5F9] px-2.5 py-1 rounded-md">No. {{ $slipGaji->nomor_slip }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Karyawan <span class="text-[#E11D48]">*</span></label>
                    <select name="karyawan_id" id="karyawan_id" required class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all cursor-pointer">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawans as $k)
                        <option value="{{ $k->id }}" data-nama="{{ $k->nama }}" data-posisi="{{ $k->posisi }}" {{ old('karyawan_id', $slipGaji->karyawan_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }} ({{ $k->posisi }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Periode Payroll <span class="text-[#E11D48]">*</span></label>
                    <input type="month" name="periode" required value="{{ old('periode', $slipGaji->periode ? \Carbon\Carbon::parse($slipGaji->periode)->format('Y-m') : '') }}"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Status Slip</label>
                    <select name="status" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all cursor-pointer">
                        <option value="draft" {{ old('status', $slipGaji->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="final" {{ old('status', $slipGaji->status) == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Status KPI</label>
                    <input type="text" name="status_kpi" list="status_kpi_options" value="{{ old('status_kpi', $slipGaji->status_kpi ?? 'TERCAPAI - INSENTIF PENUH') }}"
                        placeholder="Misal: TERCAPAI - INSENTIF PENUH"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                    <datalist id="status_kpi_options">
                        <option value="TERCAPAI - INSENTIF PENUH">
                        <option value="BELUM MENCAPAI 90%">
                        <option value="TIDAK TERCAPAI">
                    </datalist>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Upah Tetap</label>
                    <input type="date" name="tanggal_ttd" value="{{ old('tanggal_ttd', $slipGaji->tanggal_ttd ? \Carbon\Carbon::parse($slipGaji->tanggal_ttd)->format('Y-m-d') : '') }}"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                    <p class="text-[11px] text-[#64748B] mt-1">Default: Tanggal 10 setiap bulan</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Insentif KPI</label>
                    <input type="date" name="tanggal_insentif_kpi" value="{{ old('tanggal_insentif_kpi', $slipGaji->tanggal_insentif_kpi ? \Carbon\Carbon::parse($slipGaji->tanggal_insentif_kpi)->format('Y-m-d') : '') }}"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                    <p class="text-[11px] text-[#64748B] mt-1">Jumat minggu ke-3</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tanggal Lembur / Reimburse</label>
                    <input type="date" name="tanggal_lembur_reimburse" value="{{ old('tanggal_lembur_reimburse', $slipGaji->tanggal_lembur_reimburse ? \Carbon\Carbon::parse($slipGaji->tanggal_lembur_reimburse)->format('Y-m-d') : '') }}"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                    <p class="text-[11px] text-[#64748B] mt-1">Tanggal 1</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Cut-off Lembur</label>
                    <input type="date" name="cutoff_lembur" value="{{ old('cutoff_lembur', $slipGaji->cutoff_lembur ? \Carbon\Carbon::parse($slipGaji->cutoff_lembur)->format('Y-m-d') : '') }}"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                    <p class="text-[11px] text-[#64748B] mt-1">Cut-off tanggal 25</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Laporan Finance</label>
                    <input type="text" name="laporan_finance" value="{{ old('laporan_finance', $slipGaji->laporan_finance) }}"
                        placeholder="Catatan laporan finance (misal: Tanggal 27)"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#3B82F6] focus:ring-2 focus:ring-[#3B82F6]/20 focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- 2. PENGHASILAN DAN PENGGANTIAN BIAYA -->
        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)] space-y-5">
            <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                    <h2 class="text-[15px] font-bold text-[#0F172A]">2. Penghasilan & Penggantian Biaya</h2>
                </div>
                <span class="text-[11px] text-[#059669] font-semibold bg-[#ECFDF5] px-2.5 py-1 rounded-md">Total Dana Masuk</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                        Upah Tetap <span class="text-[#E11D48]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="gaji_pokok" id="gaji_pokok" required value="{{ old('gaji_pokok', $slipGaji->gaji_pokok) }}"
                            class="calc-in w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Gaji Pokok + Porsi KPI Tetap</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                        Insentif KPI
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="insentif" id="insentif" value="{{ old('insentif', $slipGaji->insentif) }}"
                            class="calc-in w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Variabel (penuh jika KPI ≥ 90%)</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                        Lembur
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="lembur" id="lembur" value="{{ old('lembur', $slipGaji->lembur) }}"
                            class="calc-in w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Kompensasi lembur</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                        Reimbursement
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="reimbursement" id="reimbursement" value="{{ old('reimbursement', $slipGaji->reimbursement) }}"
                            class="calc-in w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Penggantian biaya operasional</p>
                </div>
            </div>

            <!-- Subtotal Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="p-3.5 bg-[#F0FDF4] border border-[#DCFCE7] rounded-xl flex items-center justify-between">
                    <span class="text-[12px] font-semibold text-[#166534]">Pendapatan Bruto (Upah + Insentif):</span>
                    <span id="preview_bruto" class="text-[14px] font-bold text-[#15803D]">Rp 0</span>
                </div>
                <div class="p-3.5 bg-[#ECFDF5] border border-[#A7F3D0] rounded-xl flex items-center justify-between">
                    <span class="text-[12px] font-semibold text-[#065F46]">Total Dana Masuk:</span>
                    <span id="preview_total_masuk" class="text-[15px] font-extrabold text-[#047857]">Rp 0</span>
                </div>
            </div>
        </div>

        <!-- 3. POTONGAN KARYAWAN -->
        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)] space-y-5">
            <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span>
                    <h2 class="text-[15px] font-bold text-[#0F172A]">3. Potongan Karyawan</h2>
                </div>
                <span class="text-[11px] text-[#DC2626] font-semibold bg-[#FEF2F2] px-2.5 py-1 rounded-md">Total Potongan</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JHT Karyawan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="bpjs_jht" id="bpjs_jht" value="{{ old('bpjs_jht', $slipGaji->bpjs_jht) }}"
                            class="calc-out w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-2 focus:ring-[#EF4444]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Iuran BPJS Ketenagakerjaan (2%)</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JP Karyawan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="bpjs_jp" id="bpjs_jp" value="{{ old('bpjs_jp', $slipGaji->bpjs_jp) }}"
                            class="calc-out w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-2 focus:ring-[#EF4444]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Jaminan Pensiun C-Level (1%)</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">BPJS Kesehatan Karyawan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="bpjs_kesehatan" id="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', $slipGaji->bpjs_kesehatan) }}"
                            class="calc-out w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-2 focus:ring-[#EF4444]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Iuran BPJS Kesehatan (1%)</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">PPh 21</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="pph21" id="pph21" value="{{ old('pph21', $slipGaji->pph21) }}"
                            class="calc-out w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-2 focus:ring-[#EF4444]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Pajak penghasilan</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Potongan Lain Sah</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="potongan_lainnya" id="potongan_lainnya" value="{{ old('potongan_lainnya', $slipGaji->potongan_lainnya) }}"
                            class="calc-out w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-2 focus:ring-[#EF4444]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Kasbon / potongan sah tertulis</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Keterangan Potongan Lain</label>
                    <input type="text" name="keterangan_potongan_lainnya" value="{{ old('keterangan_potongan_lainnya', $slipGaji->keterangan_potongan_lainnya) }}"
                        class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#EF4444] focus:ring-2 focus:ring-[#EF4444]/20 focus:bg-white transition-all"
                        placeholder="Misal: Cicilan Kasbon">
                </div>
            </div>

            <!-- Subtotal Potongan Banner -->
            <div class="p-3.5 bg-[#FEF2F2] border border-[#FEE2E2] rounded-xl flex items-center justify-between">
                <span class="text-[12px] font-semibold text-[#991B1B]">Total Potongan Karyawan:</span>
                <span id="preview_total_potongan" class="text-[15px] font-extrabold text-[#DC2626]">Rp 0</span>
            </div>
        </div>

        <!-- PREVIEW BERSIH DITERIMA KARYAWAN -->
        <div class="p-6 bg-gradient-to-r from-[#1E3A8A] to-[#2563EB] rounded-[22px] text-white shadow-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[12px] font-bold uppercase tracking-wider text-blue-200">Perhitungan Akhir</span>
                <h3 class="text-[18px] font-extrabold mt-0.5">BERSIH DITERIMA KARYAWAN</h3>
                <p class="text-[12px] text-blue-100">Total Dana Masuk dikurangi Total Potongan Karyawan</p>
            </div>
            <div class="text-left sm:text-right">
                <span id="preview_thp" class="text-[28px] font-black tracking-tight">Rp 0</span>
            </div>
        </div>

        <!-- 4. KONTRIBUSI PERUSAHAAN (TIDAK MENGURANGI GAJI) -->
        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)] space-y-5">
            <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#64748B]"></span>
                    <h2 class="text-[15px] font-bold text-[#0F172A]">4. Kontribusi Perusahaan</h2>
                </div>
                <span class="text-[11px] text-[#475569] font-semibold bg-[#F1F5F9] px-2.5 py-1 rounded-md">Tidak Mengurangi Gaji</span>
            </div>

            <p class="text-[12px] text-[#64748B]">Bagian iuran yang ditanggung oleh perusahaan untuk jaminan sosial dan kesehatan karyawan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JHT Perusahaan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="jht_perusahaan" id="jht_perusahaan" value="{{ old('jht_perusahaan', $slipGaji->jht_perusahaan) }}"
                            class="calc-company w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#64748B] focus:ring-2 focus:ring-[#64748B]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">3.7% dari Upah Tetap</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JKK Perusahaan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="jkk_perusahaan" id="jkk_perusahaan" value="{{ old('jkk_perusahaan', $slipGaji->jkk_perusahaan) }}"
                            class="calc-company w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#64748B] focus:ring-2 focus:ring-[#64748B]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">0.24% risiko rendah</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JKM Perusahaan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="jkm_perusahaan" id="jkm_perusahaan" value="{{ old('jkm_perusahaan', $slipGaji->jkm_perusahaan) }}"
                            class="calc-company w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#64748B] focus:ring-2 focus:ring-[#64748B]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">0.3% dari Upah Tetap</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JP Perusahaan (C-Level)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="jp_perusahaan" id="jp_perusahaan" value="{{ old('jp_perusahaan', $slipGaji->jp_perusahaan) }}"
                            class="calc-company w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#64748B] focus:ring-2 focus:ring-[#64748B]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">2% beban CEO/COO</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">JKP</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="jkp_perusahaan" id="jkp_perusahaan" value="{{ old('jkp_perusahaan', $slipGaji->jkp_perusahaan) }}"
                            class="calc-company w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#64748B] focus:ring-2 focus:ring-[#64748B]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">Informasi kompensasi PHK</p>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">BPJS Kesehatan Perusahaan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-[13px] font-bold text-[#64748B]">Rp</span>
                        <input type="number" step="0.01" name="bpjs_kesehatan_perusahaan" id="bpjs_kesehatan_perusahaan" value="{{ old('bpjs_kesehatan_perusahaan', $slipGaji->bpjs_kesehatan_perusahaan) }}"
                            class="calc-company w-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-semibold focus:outline-none focus:border-[#64748B] focus:ring-2 focus:ring-[#64748B]/20 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-[#64748B] mt-1">4% dari dasar iuran</p>
                </div>
            </div>

            <!-- Subtotal Beban Perusahaan Banner -->
            <div class="p-3.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl flex items-center justify-between">
                <span class="text-[12px] font-semibold text-[#475569]">Total Beban Kontribusi Perusahaan:</span>
                <span id="preview_beban_perusahaan" class="text-[14px] font-bold text-[#1E293B]">Rp 0</span>
            </div>
        </div>

        <!-- 5. TANDA TANGAN & PENGESAHAN -->
        <div class="bg-white border border-[#EEF2F6] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)] space-y-5">
            <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#8B5CF6]"></span>
                    <h2 class="text-[15px] font-bold text-[#0F172A]">5. Otorisasi & Tanda Tangan</h2>
                </div>
                <span class="text-[11px] text-[#6B21A8] font-semibold bg-[#FAF5FF] px-2.5 py-1 rounded-md">Footer Dokumen</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="p-4 bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl space-y-4">
                    <span class="text-[12px] font-bold text-[#334155] uppercase tracking-wider block">Dibuat oleh (Finance/HR)</span>
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Penandatangan</label>
                        <input type="text" name="nama_penandatangan" value="{{ old('nama_penandatangan', $slipGaji->nama_penandatangan) }}"
                            class="w-full bg-white border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#8B5CF6] focus:ring-2 focus:ring-[#8B5CF6]/20 transition-all" placeholder="Misal: Finna HRD">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jabatan Penandatangan</label>
                        <input type="text" name="jabatan_penandatangan" value="{{ old('jabatan_penandatangan', $slipGaji->jabatan_penandatangan ?? 'Finance & HR Manager') }}"
                            class="w-full bg-white border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#8B5CF6] focus:ring-2 focus:ring-[#8B5CF6]/20 transition-all" placeholder="Misal: Finance & HR Manager">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kota / Tempat</label>
                        <input type="text" name="tempat_ttd" value="{{ old('tempat_ttd', $slipGaji->tempat_ttd ?? 'Cimahi') }}"
                            class="w-full bg-white border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#8B5CF6] focus:ring-2 focus:ring-[#8B5CF6]/20 transition-all" placeholder="Misal: Cimahi">
                    </div>
                </div>

                <div class="p-4 bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl space-y-4">
                    <span class="text-[12px] font-bold text-[#334155] uppercase tracking-wider block">Mengetahui (Karyawan)</span>
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Penerima / Karyawan</label>
                        <input type="text" name="nama_penerima_ttd" id="nama_penerima_ttd" value="{{ old('nama_penerima_ttd', $slipGaji->nama_penerima_ttd ?: ($slipGaji->karyawan->nama ?? '')) }}"
                            class="w-full bg-white border border-[#E2E8F0] text-[#0F172A] rounded-xl px-4 py-2 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#8B5CF6] focus:ring-2 focus:ring-[#8B5CF6]/20 transition-all" placeholder="Nama karyawan penerima">
                        <p class="text-[11px] text-[#64748B] mt-1">Otomatis terisi saat memilih karyawan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EEF2F6]">
            <a href="{{ route('admin.slip-gaji.index', ['usaha_id' => $usahaSelected]) }}" 
               class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#475569] font-semibold rounded-xl transition text-[13px]">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-semibold rounded-xl transition text-[13px] shadow-[0_4px_12px_rgba(37,99,235,0.25)] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Perbarui Slip Gaji
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const karyawanSelect = document.getElementById('karyawan_id');
    const namaPenerimaInput = document.getElementById('nama_penerima_ttd');

    if (karyawanSelect) {
        karyawanSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected && selected.dataset.nama) {
                namaPenerimaInput.value = selected.dataset.nama;
            }
        });
    }

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function recalculate() {
        // Earnings
        const upahTetap = parseFloat(document.getElementById('gaji_pokok')?.value || 0);
        const insentif = parseFloat(document.getElementById('insentif')?.value || 0);
        const lembur = parseFloat(document.getElementById('lembur')?.value || 0);
        const reimburse = parseFloat(document.getElementById('reimbursement')?.value || 0);

        const bruto = upahTetap + insentif;
        const totalMasuk = bruto + lembur + reimburse;

        // Deductions
        const jht = parseFloat(document.getElementById('bpjs_jht')?.value || 0);
        const jp = parseFloat(document.getElementById('bpjs_jp')?.value || 0);
        const kes = parseFloat(document.getElementById('bpjs_kesehatan')?.value || 0);
        const pph = parseFloat(document.getElementById('pph21')?.value || 0);
        const lain = parseFloat(document.getElementById('potongan_lainnya')?.value || 0);

        const totalPotongan = jht + jp + kes + pph + lain;
        const thp = totalMasuk - totalPotongan;

        // Company
        const cJht = parseFloat(document.getElementById('jht_perusahaan')?.value || 0);
        const cJkk = parseFloat(document.getElementById('jkk_perusahaan')?.value || 0);
        const cJkm = parseFloat(document.getElementById('jkm_perusahaan')?.value || 0);
        const cJp = parseFloat(document.getElementById('jp_perusahaan')?.value || 0);
        const cJkp = parseFloat(document.getElementById('jkp_perusahaan')?.value || 0);
        const cKes = parseFloat(document.getElementById('bpjs_kesehatan_perusahaan')?.value || 0);

        const totalCompany = cJht + cJkk + cJkm + cJp + cJkp + cKes;

        // Render preview
        document.getElementById('preview_bruto').textContent = formatRupiah(bruto);
        document.getElementById('preview_total_masuk').textContent = formatRupiah(totalMasuk);
        document.getElementById('preview_total_potongan').textContent = formatRupiah(totalPotongan);
        document.getElementById('preview_thp').textContent = formatRupiah(thp);
        document.getElementById('preview_beban_perusahaan').textContent = formatRupiah(totalCompany);
    }

    document.querySelectorAll('.calc-in, .calc-out, .calc-company').forEach(input => {
        input.addEventListener('input', recalculate);
    });

    recalculate();
});
</script>
@endsection
