<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Keuangan Internal - PT Hexagon Karyatama Indonesia</title>
    
    <!-- Prevent Search Engine Indexing & Robot Crawling (Internal Only) -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Typography: Plus Jakarta Sans / Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-indigo-500/10 selection:text-indigo-600 min-h-screen flex flex-col">
    
    <!-- Top Navigation (Apple Glass) -->
    <header class="fixed w-full top-0 z-50 apple-glass-header transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Brand & Company Identifier (Official Hexagon Logo) -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-hexagon.png') }}" class="h-8 sm:h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105" alt="Hexagon Inc. - PT Hexagon Karyatama Indonesia">
            </a>
            
            <!-- Quick Links & Auth CTA -->
            <div class="flex items-center gap-3 sm:gap-4">
                <nav class="hidden md:flex items-center gap-6 mr-2 text-xs sm:text-[13px] font-semibold text-slate-600">
                    <a href="#fitur" class="hover:text-indigo-600 transition-colors">Modul Sistem</a>
                    <a href="#tata-kelola" class="hover:text-indigo-600 transition-colors">Tata Kelola</a>
                    <a href="#keamanan" class="hover:text-indigo-600 transition-colors">Keamanan</a>
                </nav>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="apple-button apple-button-primary inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] shadow-xs">
                            <span>Buka Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="apple-button apple-button-primary inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk ke Portal</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 pt-24 sm:pt-32">
        <!-- Hero Section -->
        <section class="relative overflow-hidden px-4 sm:px-6 lg:px-8 pt-8 sm:pt-14 pb-16 sm:pb-24 text-center">
            <!-- Subtle Radial Gradient Background Glow -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>
            
            <div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">
                <!-- Status Pill Badge -->
                <div data-animate-stagger class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 backdrop-blur-md border border-slate-200/80 shadow-[0_1px_3px_rgba(15,23,42,0.03)] text-xs font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Portal Finansial Terintegrasi &bull; Internal Enterprise</span>
                </div>

                <!-- Main Headline -->
                <div data-animate-stagger class="space-y-3">
                    <h1 class="apple-display-title text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.12]">
                        Sentralisasi Keuangan & Akuntansi <br class="hidden sm:inline"/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-600">
                            PT Hexagon Karyatama Indonesia
                        </span>
                    </h1>
                    <p class="text-sm sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                        Platform manajemen keuangan internal yang dirancang untuk otomasi pembukuan, pengawasan arus kas, kepatuhan perpajakan, dan penyusunan laporan keuangan standar SAK ETAP secara presisi.
                    </p>
                </div>

                <!-- Action Button Group -->
                <div data-animate-stagger class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="apple-button apple-button-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl text-sm shadow-sm">
                            <span>Buka Dashboard Utama</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="apple-button apple-button-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk ke Akun Pegawai</span>
                        </a>
                    @endauth
                    
                    <a href="#fitur" class="apple-button apple-button-secondary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-sm border border-slate-200/80 shadow-2xs">
                        <span>Pelajari Modul Sistem</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>

                <!-- Interactive Snapshot Mockup -->
                <div data-animate-stagger class="pt-6 sm:pt-10 max-w-5xl mx-auto">
                    <div class="apple-glass-card rounded-3xl border border-slate-200/80 p-4 sm:p-6 shadow-[0_20px_50px_rgba(15,23,42,0.06)]">
                        <!-- macOS Window Header Bar -->
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 text-left">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                <span class="ml-2 text-xs font-semibold text-slate-400 hidden sm:inline">Hexagon Financial Dashboard &bull; Live Preview</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200/60 text-[11px] font-semibold text-slate-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                <span>PT Hexagon Karyatama Indonesia</span>
                            </div>
                        </div>

                        <!-- 4 Quick KPI Snapshot Cards -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-left">
                            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Neraca</p>
                                <p class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight">100% Seimbang</p>
                                <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Terverifikasi Otomatis
                                </p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Standar Akuntansi</p>
                                <p class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight">SAK ETAP</p>
                                <p class="text-[11px] text-indigo-600 font-semibold mt-1">Laporan Siap Audit</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Multi-Entitas</p>
                                <p class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight">Terpadu</p>
                                <p class="text-[11px] text-slate-500 font-medium mt-1">Konsolidasi Unit Bisnis</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kepatuhan Fiskal</p>
                                <p class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight">PPh & PPN</p>
                                <p class="text-[11px] text-teal-600 font-semibold mt-1">Kalkulasi Otomatis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Modules Section -->
        <section id="fitur" class="py-16 sm:py-24 bg-white border-y border-slate-200/60 scroll-mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-2 block">Cakupan Fungsional</span>
                    <h2 class="text-2xl sm:text-4xl font-bold text-slate-900 tracking-tight">
                        Modul Keuangan Terintegrasi
                    </h2>
                    <p class="text-sm sm:text-base text-slate-500 mt-2">
                        Dirancang khusus untuk mendukung alur kerja akuntan, tim operasional, serta manajemen PT Hexagon Karyatama Indonesia.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Module 1: Laporan Finansial -->
                    <div data-animate-stagger class="apple-glass-card rounded-3xl border border-slate-200/70 p-6 sm:p-7 group">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mb-5 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Laporan Finansial Lengkap</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                            Laba Rugi, Neraca, Arus Kas, Perubahan Ekuitas, serta Neraca Lajur (Worksheet) 10 kolom yang selalu terhitung secara akurat.
                        </p>
                    </div>
                    
                    <!-- Module 2: Automasi Jurnal -->
                    <div data-animate-stagger class="apple-glass-card rounded-3xl border border-slate-200/70 p-6 sm:p-7 group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 mb-5 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Automasi & Jurnal Terpadu</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                            Pencatatan Jurnal Umum & Penyesuaian dengan aturan otomatisasi debit-kredit berdasarkan label transaksi tanpa risiko salah input.
                        </p>
                    </div>
                    
                    <!-- Module 3: Multi-Entitas & Master Data -->
                    <div data-animate-stagger class="apple-glass-card rounded-3xl border border-slate-200/70 p-6 sm:p-7 group">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600 mb-5 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Multi-Usaha & Master Data</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                            Manajemen unit usaha terpisah, pelanggan, supplier, inventaris produk, serta kalkulasi HPP dan biaya produksi tambahan.
                        </p>
                    </div>
                    
                    <!-- Module 4: Kepatuhan Pajak -->
                    <div data-animate-stagger class="apple-glass-card rounded-3xl border border-slate-200/70 p-6 sm:p-7 group">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 mb-5 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Manajemen Pajak & Fiskal</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                            Penetapan aturan PPh & PPN otomatis pada setiap transaksi niaga, rekapitulasi pajak bulanan, dan kepatuhan administrasi fiskal.
                        </p>
                    </div>
                    
                    <!-- Module 5: Payroll & Slip Gaji -->
                    <div data-animate-stagger class="apple-glass-card rounded-3xl border border-slate-200/70 p-6 sm:p-7 group">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 mb-5 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Payroll & Slip Gaji Digital</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                            Database karyawan internal, perhitungan take home pay, tunjangan, potongan, serta pembuatan slip gaji digital siap cetak dan unduh.
                        </p>
                    </div>
                    
                    <!-- Module 6: Administrasi Dokumen Niaga -->
                    <div data-animate-stagger class="apple-glass-card rounded-3xl border border-slate-200/70 p-6 sm:p-7 group">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 mb-5 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Dokumen Niaga & Surat Resmi</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                            Penerbitan Invoice, Receipt, Kuitansi, Nota, Surat Pernyataan, Surat Pemberitahuan, dan Berita Acara berformat resmi PT Hexagon.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Governance & Security Section -->
        <section id="tata-kelola" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-14 text-white relative overflow-hidden shadow-2xl border border-slate-800">
                    <!-- Subtle Glow Shapes -->
                    <div class="absolute -top-32 -right-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Standar Keamanan Enterprise</span>
                        </div>
                        <h2 class="text-2xl sm:text-4xl font-bold tracking-tight text-white mb-4">
                            Integritas Data, Audit Trail & Hak Akses Terlindungi
                        </h2>
                        <p class="text-xs sm:text-base text-slate-300 leading-relaxed mb-8">
                            Akses sistem sepenuhnya dibatasi untuk pegawai, akuntan, dan jajaran direksi PT Hexagon Karyatama Indonesia. Setiap perubahan transaksi tersimpan dengan jejak audit yang dapat dipertanggungjawabkan demi transparansi tata kelola perusahaan.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 text-left" id="keamanan">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                                <p class="text-sm font-bold text-white mb-1">Enkripsi Data</p>
                                <p class="text-xs text-slate-400">Proteksi kredensial dan rekaman saldo dengan standar perbankan modern.</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                                <p class="text-sm font-bold text-white mb-1">Audit Trail Jurnal</p>
                                <p class="text-xs text-slate-400">Riwayat penginputan dan penyesuaian tersinkronisasi otomatis.</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                                <p class="text-sm font-bold text-white mb-1">Pemisahan Entitas</p>
                                <p class="text-xs text-slate-400">Isolasi pembukuan antar unit usaha untuk independensi laporan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200/60 bg-white py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-hexagon.png') }}" class="h-8 w-auto object-contain" alt="Hexagon Inc.">
                <div>
                    <p class="text-[11px] text-slate-400">
                        Sistem Informasi Manajemen Keuangan &bull; Internal Use Only
                    </p>
                </div>
            </div>
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} PT Hexagon Karyatama Indonesia. Seluruh hak cipta dilindungi.
            </p>
        </div>
    </footer>

</body>
</html>
