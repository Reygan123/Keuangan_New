@extends('layouts.admin.app')

@section('title', 'Pengeluaran Kilat - PT Hexagon Karyatama Indonesia')

@section('content')
<div class="w-full space-y-6 pb-12" x-data="quickExpenseApp()">

    <!-- Header Section (Apple HIG) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50 border border-indigo-100/80 text-[11px] font-bold text-indigo-700">
                    <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Input Cepat & Integrasi Cloud</span>
                </span>
                <span class="text-xs text-slate-400 font-medium">&bull; PT Hexagon Karyatama Indonesia</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengeluaran Kilat</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Pencatatan pengeluaran harian instan by kode, QR scanner & arsip cloud Google Drive sesuai kategori dan kode COA.
            </p>
        </div>

        <!-- Top Actions & Usaha Switcher -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Usaha Switcher -->
            <form method="GET" action="{{ route('admin.quick-expense.index') }}" id="usahaFilterForm" class="flex-shrink-0">
                <select name="usaha_id" onchange="document.getElementById('usahaFilterForm').submit()" 
                        class="bg-white border border-slate-200/80 text-slate-800 text-xs sm:text-sm font-semibold rounded-xl px-3 py-2 shadow-2xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>
                            🏢 {{ $usaha->nama }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Google Drive Status Pill Button -->
            <button type="button" @click="showGdriveModal = true"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold border transition-all active:scale-95 shadow-2xs {{ $isGdriveReady ? 'bg-emerald-50 text-emerald-800 border-emerald-200/80 hover:bg-emerald-100/80' : 'bg-slate-50 text-slate-700 border-slate-200/80 hover:bg-slate-100' }}">
                <span class="w-2 h-2 rounded-full {{ $isGdriveReady ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                <span>{{ $isGdriveReady ? 'Google Drive Aktif' : 'Atur Google Drive' }}</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
            </button>

            <!-- Link Ke Pengeluaran Lengkap -->
            <a href="{{ route('admin.kasbank.index', 'pengeluaran') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 shadow-2xs active:scale-95 transition-all">
                <span>Daftar Lengkap</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- Alert Success / Error Messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/80 text-emerald-800 text-xs sm:text-sm font-semibold flex items-start gap-3 shadow-2xs animate-fade-in">
            <div class="w-5 h-5 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50/90 border border-rose-200/80 text-rose-800 text-xs sm:text-sm font-semibold flex items-start gap-3 shadow-2xs">
            <div class="w-5 h-5 rounded-lg bg-rose-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div class="flex-1">{{ session('error') }}</div>
        </div>
    @endif

    <!-- SECTION 1: PRESET CHIPS CEPAT (INPUT BY KODE & PRESET) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Pintasan Pengeluaran Terpopuler (Klik untuk Pilih Cepat)
            </span>
            <span class="text-[11px] text-slate-400 font-medium hidden sm:inline">Shortcut Kode COA</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
            @foreach($presets as $p)
                <button type="button" 
                        @click="selectPreset('{{ $p['akun_id'] }}', '{{ $p['kode'] }}', '{{ addslashes($p['akun_name']) }}', '{{ addslashes($p['label']) }}')"
                        :class="selectedAkunId == '{{ $p['akun_id'] }}' ? 'ring-2 ring-indigo-600 bg-indigo-50/80 border-indigo-300' : 'bg-slate-50/80 hover:bg-slate-100/90 border-slate-200/70'"
                        class="p-3 rounded-2xl border text-left transition-all duration-150 active:scale-95 group flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="inline-flex px-1.5 py-0.5 rounded-md bg-white border border-slate-200 font-mono text-[10px] font-bold text-slate-600 group-hover:text-indigo-600">
                                {{ $p['kode'] }}
                            </span>
                            <span class="w-2 h-2 rounded-full" 
                                  :class="selectedAkunId == '{{ $p['akun_id'] }}' ? 'bg-indigo-600' : 'bg-slate-300 group-hover:bg-indigo-400'"></span>
                        </div>
                        <p class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 leading-tight">
                            {{ $p['label'] }}
                        </p>
                    </div>
                    <p class="text-[10px] text-slate-400 truncate mt-2 font-medium">
                        {{ $p['akun_name'] }}
                    </p>
                </button>
            @endforeach
        </div>
    </div>

    <!-- SECTION 2: FORM UTAMA PENGELUARAN KILAT -->
    <form action="{{ route('admin.quick-expense.store') }}" method="POST" enctype="multipart/form-data" id="quickExpenseForm" @submit="handleSubmit">
        @csrf
        <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
        <input type="hidden" name="mobile_token" :value="mobileUploadToken">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT PANEL: DATA TRANSAKSI FINANSIAL (7 Kolom) -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                            1
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">Rincian Finansial & Kode COA</h2>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">Debit Beban &bull; Kredit Kas</span>
                </div>

                <!-- 1. Pilihan Akun Beban (By Kode COA) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Akun Pengeluaran / Beban (COA) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-indigo-600 font-medium" x-show="selectedKodeCoa">
                            Kode: <span class="font-mono font-bold" x-text="selectedKodeCoa"></span>
                        </span>
                    </div>

                    <div class="relative">
                        <select name="akun_beban_id" id="akun_beban_select" x-model="selectedAkunId" @change="onAkunChange" required
                                class="w-full bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/90 text-slate-900 text-xs sm:text-sm font-semibold rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">-- Pilih Akun Beban / Cari Kode COA --</option>
                            @foreach($akunBeban as $ab)
                                <option value="{{ $ab->id }}" data-kode="{{ $ab->kode }}" data-name="{{ $ab->name }}">
                                    [{{ $ab->kode ?: '-' }}] {{ $ab->name }} ({{ $ab->klasifikasi }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Pilih melalui tombol preset di atas atau cari akun beban berdasarkan kode COA.
                    </p>
                </div>

                <!-- 2. Nominal Pengeluaran (Highlight Besar) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nominal Pengeluaran (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-2xl border border-slate-200/90 bg-slate-50/50 focus-within:bg-white focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition-all">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg sm:text-xl font-bold text-slate-400 font-mono">
                            Rp
                        </span>
                        <input type="text" id="displayJumlah" x-model="displayJumlah" @input="formatRupiah" required placeholder="0" autofocus
                               class="w-full pl-12 sm:pl-14 pr-4 py-3 sm:py-3.5 text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono bg-transparent outline-none rounded-2xl tracking-tight">
                        <input type="hidden" name="jumlah" :value="rawJumlah">
                    </div>
                    <!-- Quick Nominal Buttons -->
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <span class="text-[10px] text-slate-400 py-1 font-semibold">Cepat:</span>
                        <button type="button" @click="setNominal(50000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-mono font-semibold active:scale-95 transition-all">50.000</button>
                        <button type="button" @click="setNominal(100000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-mono font-semibold active:scale-95 transition-all">100.000</button>
                        <button type="button" @click="setNominal(250000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-mono font-semibold active:scale-95 transition-all">250.000</button>
                        <button type="button" @click="setNominal(500000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-mono font-semibold active:scale-95 transition-all">500.000</button>
                        <button type="button" @click="setNominal(1000000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-mono font-semibold active:scale-95 transition-all">1.000.000</button>
                    </div>
                </div>

                <!-- 3. Grid: Sumber Dana (Kas/Bank) & Tanggal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Sumber Dana (Kas / Bank) <span class="text-rose-500">*</span>
                        </label>
                        <select name="akun_payment_id" required
                                class="w-full bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/90 text-slate-900 text-xs sm:text-sm font-semibold rounded-2xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            @foreach($akunKasBank as $ak)
                                <option value="{{ $ak->id }}">
                                    [{{ $ak->kode ?: 'KAS' }}] {{ $ak->name }} (Saldo: Rp {{ number_format($ak->saldo, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Transaksi <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                               class="w-full bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/90 text-slate-900 text-xs sm:text-sm font-semibold rounded-2xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- 4. Keterangan Pengeluaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Keterangan / Catatan Singkat
                    </label>
                    <input type="text" name="keterangan" x-model="keterangan" placeholder="Contoh: Bensin operasional kendaraan logistik, pembelian snack meeting"
                           class="w-full bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/90 text-slate-900 text-xs sm:text-sm rounded-2xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>

            </div>

            <!-- RIGHT PANEL: BUKTI TRANSFER & FITUR QR SCANNER (5 Kolom) -->
            <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-7 shadow-xs flex flex-col justify-between space-y-5">
                
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                2
                            </div>
                            <h2 class="text-sm font-bold text-slate-900">Bukti Transfer & Scan QR</h2>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400">Sinkron Google Drive</span>
                    </div>

                    <!-- Method Tab Switcher (Upload PC vs Scan HP) -->
                    <div class="flex rounded-2xl bg-slate-100 p-1 mb-4">
                        <button type="button" @click="activeUploadTab = 'file'" 
                                :class="activeUploadTab === 'file' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'"
                                class="flex-1 py-2 text-xs rounded-xl transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Upload File</span>
                        </button>
                        <button type="button" @click="openQrScanner()" 
                                :class="activeUploadTab === 'qr' ? 'bg-white text-indigo-600 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'"
                                class="flex-1 py-2 text-xs rounded-xl transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>📱 Scan QR via HP</span>
                        </button>
                    </div>

                    <!-- TAB 1: FILE DRAG & DROP AREA -->
                    <div x-show="activeUploadTab === 'file'" class="space-y-3">
                        <div class="relative border-2 border-dashed rounded-2xl p-6 text-center transition-all cursor-pointer"
                             :class="isDragging ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-200/90 hover:border-slate-300 bg-slate-50/50'"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleDrop"
                             @click="document.getElementById('fileInput').click()">
                            
                            <input type="file" name="bukti_file" id="fileInput" @change="handleFileSelect" accept="image/*,application/pdf" class="hidden">
                            
                            <div x-show="!previewUrl" class="space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto shadow-2xs">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Tarik bukti transfer ke sini atau klik untuk memilih</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, PDF atau WebP (Maks. 15MB)</p>
                                </div>
                            </div>

                            <!-- Preview File Terpilih -->
                            <div x-show="previewUrl" class="space-y-2" @click.stop>
                                <div class="relative max-h-48 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center">
                                    <img :src="previewUrl" class="max-h-48 object-contain rounded-xl" alt="Preview Bukti">
                                    <button type="button" @click="clearFile" class="absolute top-2 right-2 p-1.5 bg-slate-900/70 hover:bg-slate-900 text-white rounded-full backdrop-blur-sm active:scale-95 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 font-mono truncate text-center" x-text="fileName"></p>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: QR CODE SCANNER (PAIRING HP) -->
                    <div x-show="activeUploadTab === 'qr'" class="space-y-3">
                        <div class="bg-slate-50/80 rounded-2xl border border-slate-200/80 p-4 text-center space-y-3">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-semibold text-slate-700 shadow-2xs">
                                <span class="w-2 h-2 rounded-full" :class="mobileUploaded ? 'bg-emerald-500' : 'bg-indigo-500 animate-pulse'"></span>
                                <span x-text="mobileUploaded ? '✓ Foto Berhasil Diterima' : 'Menunggu Kamera HP...'"></span>
                            </div>

                            <!-- QR Code Preview -->
                            <div x-show="!mobileUploaded" class="flex flex-col items-center justify-center py-2">
                                <div class="bg-white p-2.5 rounded-2xl border border-slate-200/80 shadow-xs mb-2">
                                    <template x-if="qrCodeImgUrl">
                                        <img :src="qrCodeImgUrl" alt="QR Code Upload" class="w-40 h-40 object-contain rounded-xl">
                                    </template>
                                    <template x-if="!qrCodeImgUrl">
                                        <div class="w-40 h-40 flex items-center justify-center text-xs text-slate-400">
                                            Menyiapkan QR...
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs font-bold text-slate-900">Arahkan Kamera HP ke QR Code Ini</p>
                                <p class="text-[11px] text-slate-500 max-w-xs mt-0.5">
                                    Buka kamera HP Anda, scan kode di atas untuk langsung memotret bukti transfer tanpa perlu kirim ke WA.
                                </p>

                                <div class="mt-3 flex items-center gap-2">
                                    <button type="button" @click="copyMobileLink" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 text-[11px] font-semibold rounded-xl border border-slate-200/80 active:scale-95 transition-all flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span x-text="linkCopied ? 'Tersalin!' : 'Salin Tautan HP'"></span>
                                    </button>
                                    <a :href="mobileUploadUrl" target="_blank" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-indigo-600 text-[11px] font-semibold rounded-xl border border-slate-200/80 active:scale-95 transition-all">
                                        Buka di Tab Baru &rarr;
                                    </a>
                                </div>
                            </div>

                            <!-- Mobile Uploaded Photo Preview -->
                            <div x-show="mobileUploaded" class="py-2 space-y-2">
                                <div class="relative max-h-48 rounded-xl overflow-hidden bg-white border border-slate-200 flex items-center justify-center">
                                    <img :src="mobilePreviewUrl" class="max-h-48 object-contain rounded-xl" alt="Foto dari HP">
                                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-emerald-600 text-white text-[10px] font-bold shadow-xs">
                                        Foto dari HP
                                    </div>
                                    <button type="button" @click="resetQrScanner" class="absolute top-2 right-2 p-1.5 bg-slate-900/70 hover:bg-slate-900 text-white rounded-full active:scale-95 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <p class="text-xs text-emerald-700 font-bold">✓ Bukti transfer berhasil sinkron dari kamera HP Anda!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Hierarki Google Drive -->
                    <div class="mt-4 p-3 rounded-2xl bg-indigo-50/50 border border-indigo-100/70 flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-lg bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="text-[11px] text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-900">Arsip Cloud Terstruktur:</span>
                            Dokumen akan otomatis tersimpan di folder Google Drive: <br>
                            <span class="font-mono text-indigo-700">/Pengeluaran/<span x-text="selectedKodeCoa || 'COA'"></span>/<span x-text="currentMonthYear"></span></span>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT ACTION BUTTON -->
                <div class="pt-2">
                    <button type="submit" id="btnSubmitTrx" :disabled="isSubmitting"
                            class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 active:scale-98 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-extrabold text-sm sm:text-base rounded-2xl shadow-sm transition-all flex items-center justify-center gap-2">
                        <template x-if="!isSubmitting">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Simpan & Catat Pengeluaran (Enter)</span>
                            </div>
                        </template>
                        <template x-if="isSubmitting">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <span>Menyimpan & Mengunggah ke Google Drive...</span>
                            </div>
                        </template>
                    </button>
                    <p class="text-[11px] text-slate-400 text-center mt-2">
                        Jurnal Umum & saldo kas akan otomatis terbarui secara real-time.
                    </p>
                </div>

            </div>

        </div>
    </form>

    <!-- SECTION 3: RIWAYAT PENGELUARAN HARI INI & STATUS ARSIP GOOGLE DRIVE -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight">Riwayat Pengeluaran Terakhir</h2>
                <p class="text-xs text-slate-500">Daftar transaksi pengeluaran dan status arsip Google Drive unit usaha ini.</p>
            </div>
            <span class="text-xs font-semibold text-slate-400 font-mono">Total {{ $recentExpenses->count() }} Transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50/50">
                        <th class="py-3 px-3.5 rounded-l-xl">Kode Trx</th>
                        <th class="py-3 px-3.5">Tanggal</th>
                        <th class="py-3 px-3.5">Akun Beban (COA)</th>
                        <th class="py-3 px-3.5">Kas / Bank</th>
                        <th class="py-3 px-3.5">Keterangan</th>
                        <th class="py-3 px-3.5 text-right">Nominal (Rp)</th>
                        <th class="py-3 px-3.5 text-center">Bukti / GDrive</th>
                        <th class="py-3 px-3.5 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentExpenses as $rx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3.5 font-mono font-bold text-slate-900 whitespace-nowrap">
                                {{ $rx->kode_transaksi ?: ('TRX-' . $rx->id) }}
                            </td>
                            <td class="py-3 px-3.5 text-slate-600 whitespace-nowrap">
                                {{ date('d/m/Y', strtotime($rx->tanggal)) }}
                            </td>
                            <td class="py-3 px-3.5 font-medium text-slate-900">
                                <span class="font-mono text-indigo-600 text-[11px] font-bold">[{{ $rx->akunLawan?->kode ?: '-' }}]</span>
                                <span>{{ $rx->akunLawan?->name }}</span>
                            </td>
                            <td class="py-3 px-3.5 text-slate-600 text-xs">
                                {{ $rx->akunPayment?->name }}
                            </td>
                            <td class="py-3 px-3.5 text-slate-500 max-w-xs truncate text-xs">
                                {{ $rx->keterangan }}
                            </td>
                            <td class="py-3 px-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                Rp {{ number_format($rx->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                @if($rx->gdrive_view_link)
                                    <a href="{{ $rx->gdrive_view_link }}" target="_blank" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200/80 text-[11px] font-bold transition-all shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                                        <span>✓ Google Drive</span>
                                    </a>
                                @elseif($rx->bukti_transfer)
                                    <a href="{{ asset('storage/' . $rx->bukti_transfer) }}" target="_blank" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200/80 text-[11px] font-semibold transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>File Lokal</span>
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                <a href="{{ route('admin.kasbank.show', ['tipe' => 'pengeluaran', 'kasbank' => $rx->id]) }}" 
                                   class="inline-flex items-center px-2 py-1 bg-white hover:bg-slate-100 text-indigo-600 rounded-lg text-xs font-semibold border border-slate-200/80 shadow-2xs transition-all">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-xs text-slate-400">
                                Belum ada transaksi pengeluaran pada unit usaha ini. Silakan input pengeluaran pertama menggunakan form di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: PENGATURAN GOOGLE DRIVE INTEGRATION -->
    <div x-cloak x-show="showGdriveModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.away="showGdriveModal = false"
             class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl max-w-xl w-full p-6 sm:p-8 space-y-6 max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                        ☁️
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Konfigurasi Google Drive</h3>
                        <p class="text-xs text-slate-500">PT Hexagon Karyatama Indonesia &bull; Arsip Keuangan Cloud</p>
                    </div>
                </div>
                <button type="button" @click="showGdriveModal = false" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Status Koneksi Saat Ini -->
            <div class="p-4 rounded-2xl border {{ $isGdriveReady ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-700' }} flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full {{ $isGdriveReady ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400' }}"></span>
                    <div>
                        <p class="text-xs font-bold">{{ $isGdriveReady ? 'Google Drive Terhubung' : 'Google Drive Belum Siap' }}</p>
                        <p class="text-[11px] text-slate-500">Metode: <span class="font-semibold">{{ $gdriveAuthMethod }}</span></p>
                    </div>
                </div>
                @if($isOAuthConnected)
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[10px] font-bold">5 TB Kuota Aktif</span>
                @endif
            </div>

            <!-- Form Simpan Pengaturan GDrive -->
            <form action="{{ route('admin.quick-expense.gdrive-settings') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Root Folder ID Google Drive <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="folder_id" value="{{ $gdriveFolderId }}" required placeholder="Contoh: 1EAZ4j3_7E9fQFptPBIXH4ffbqGVR680e"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs sm:text-sm rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                        ID folder dari URL Google Drive Anda (karakter setelah <span class="font-mono text-slate-600">drive.google.com/drive/folders/<strong>[ID_FOLDER]</strong></span>).
                    </p>
                </div>

                <!-- Bagian OAuth 2.0 Web Client (Disarankan untuk Drive 5 TB) -->
                <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                            🔑 OAuth 2.0 Web Client (Disarankan untuk Akun 5 TB)
                        </span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-indigo-100 text-indigo-700 font-bold">Bypass Quota 0 Byte</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Client ID</label>
                            <input type="text" name="client_id" value="{{ $gdriveClientId }}" placeholder="Contoh: 11407...apps.googleusercontent.com"
                                   class="w-full bg-white border border-slate-200 text-slate-900 font-mono text-xs rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Client Secret</label>
                            <input type="password" name="client_secret" value="{{ $gdriveClientSecret }}" placeholder="GOCSPX-..."
                                   class="w-full bg-white border border-slate-200 text-slate-900 font-mono text-xs rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Salin Redirect URI -->
                    <div class="pt-1">
                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Authorized Redirect URI (Masukkan di Google Cloud Console):</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ route('admin.quick-expense.google.callback') }}" id="redirectUriInput"
                                   class="w-full bg-slate-100 border border-slate-200 text-slate-600 font-mono text-[11px] rounded-lg px-2.5 py-1.5 select-all">
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('redirectUriInput').value); alert('Redirect URI berhasil disalin!');"
                                    class="px-2.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 shadow-2xs flex-shrink-0 active:scale-95">
                                Salin
                            </button>
                        </div>
                    </div>

                    @if(!empty($gdriveClientId))
                        <div class="pt-2">
                            <a href="{{ route('admin.quick-expense.google.redirect') }}" 
                               class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs active:scale-98 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>{{ $isOAuthConnected ? 'Hubungkan Ulang / Ganti Akun Google' : 'Login & Otorisasi Akun Google (5 TB)' }}</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Bagian Service Account (Opsional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        File Service Account JSON (Opsional / Alternatif)
                    </label>
                    <input type="file" name="credentials_file" accept=".json"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl px-3.5 py-2 outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="showGdriveModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl active:scale-95 transition-all">
                        Tutup
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs active:scale-95 transition-all">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Interactive Alpine.js Quick Expense State & Scanner Logic -->
<script>
function quickExpenseApp() {
    return {
        selectedAkunId: '',
        selectedKodeCoa: '',
        selectedAkunName: '',
        displayJumlah: '',
        rawJumlah: 0,
        keterangan: '',
        currentMonthYear: '{{ date('Y-m') }}',
        
        // Upload & QR State
        activeUploadTab: 'file',
        isDragging: false,
        previewUrl: null,
        fileName: '',
        isSubmitting: false,

        // QR Mobile Scanner State
        mobileUploadToken: null,
        mobileUploadUrl: '',
        qrCodeImgUrl: null,
        mobileUploaded: false,
        mobilePreviewUrl: null,
        pollingInterval: null,
        linkCopied: false,

        // Modal
        showGdriveModal: false,

        selectPreset(akunId, kode, name, label) {
            this.selectedAkunId = akunId;
            this.selectedKodeCoa = kode;
            this.selectedAkunName = name;
            if (!this.keterangan) {
                this.keterangan = 'Pengeluaran ' + label;
            }
            // Update select element
            const selectEl = document.getElementById('akun_beban_select');
            if (selectEl) {
                selectEl.value = akunId;
            }
            // Auto-focus nominal input
            const nominalInput = document.getElementById('displayJumlah');
            if (nominalInput) {
                nominalInput.focus();
            }
        },

        onAkunChange(e) {
            const select = e.target;
            const selectedOpt = select.options[select.selectedIndex];
            if (selectedOpt) {
                this.selectedKodeCoa = selectedOpt.dataset.kode || '';
                this.selectedAkunName = selectedOpt.dataset.name || '';
            }
        },

        formatRupiah(e) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            this.rawJumlah = val ? parseInt(val, 10) : 0;
            this.displayJumlah = this.rawJumlah ? this.rawJumlah.toLocaleString('id-ID') : '';
        },

        setNominal(amount) {
            this.rawJumlah = amount;
            this.displayJumlah = amount.toLocaleString('id-ID');
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.processSelectedFile(file);
        },

        handleDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer.files[0];
            if (!file) return;
            const fileInput = document.getElementById('fileInput');
            if (fileInput) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            }
            this.processSelectedFile(file);
        },

        processSelectedFile(file) {
            this.fileName = file.name;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (evt) => {
                    this.previewUrl = evt.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.previewUrl = '{{ asset("images/pdf-icon.png") }}';
            }
        },

        clearFile() {
            const fileInput = document.getElementById('fileInput');
            if (fileInput) fileInput.value = '';
            this.previewUrl = null;
            this.fileName = '';
        },

        async openQrScanner() {
            this.activeUploadTab = 'qr';
            if (!this.mobileUploadToken) {
                try {
                    const res = await fetch("{{ route('admin.quick-expense.generate-token') }}");
                    const data = await res.json();
                    this.mobileUploadToken = data.token;
                    this.mobileUploadUrl = data.mobile_url;
                    // QR Code generator URL
                    this.qrCodeImgUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=8&data=${encodeURIComponent(data.mobile_url)}`;
                    this.startPollingMobileUpload();
                } catch (err) {
                    console.error('Gagal generate token QR:', err);
                }
            }
        },

        startPollingMobileUpload() {
            if (this.pollingInterval) clearInterval(this.pollingInterval);
            this.pollingInterval = setInterval(async () => {
                if (!this.mobileUploadToken || this.mobileUploaded) return;

                try {
                    const checkUrl = "{{ url('admin/quick-expense/check-token') }}/" + this.mobileUploadToken;
                    const res = await fetch(checkUrl);
                    const data = await res.json();

                    if (data.status === 'uploaded') {
                        this.mobileUploaded = true;
                        this.mobilePreviewUrl = data.preview_url;
                        clearInterval(this.pollingInterval);
                    }
                } catch (e) {
                    // silent fail
                }
            }, 2500);
        },

        resetQrScanner() {
            this.mobileUploaded = false;
            this.mobilePreviewUrl = null;
            this.mobileUploadToken = null;
            this.qrCodeImgUrl = null;
            if (this.pollingInterval) clearInterval(this.pollingInterval);
            this.openQrScanner();
        },

        copyMobileLink() {
            if (!this.mobileUploadUrl) return;
            navigator.clipboard.writeText(this.mobileUploadUrl);
            this.linkCopied = true;
            setTimeout(() => { this.linkCopied = false; }, 2000);
        },

        handleSubmit(e) {
            if (!this.rawJumlah || this.rawJumlah <= 0) {
                alert('Silakan masukkan nominal pengeluaran yang valid.');
                e.preventDefault();
                return;
            }
            this.isSubmitting = true;
        }
    }
}
</script>
@endsection
