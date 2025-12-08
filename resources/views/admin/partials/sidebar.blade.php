<aside :class="sidebarExpanded ? 'translate-x-0' : '-translate-x-full'"
    class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col min-h-screen fixed left-0 top-0 lg:fixed lg:translate-x-0 transition-transform duration-300 z-40 lg:max-h-screen">

    <!-- Header -->
    <div class="px-6 py-6 border-b border-slate-800 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center font-bold text-lg">
                K
            </div>
            <h1 class="text-xl font-bold text-white">Keuangan</h1>
        </div>
    </div>

    <!-- Scrollable Navigation -->
    <nav class="flex-1 overflow-y-auto scrollbar-hide">
        <div class="px-4 py-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Dashboard</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4h4"></path>
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
        </div>

        <!-- Master Data Section -->
        <div class="px-4 py-6 border-t border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Master Data</p>

            <a href="{{ route('admin.usahas.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium">Usaha</span>
            </a>

            <a href="{{ route('admin.pelanggans.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M3 20.394c0-2.084.934-3.943 2.418-5.247.5-.424 1.099-.804 1.777-1.004a6 6 0 0111.618 0c.678.2 1.277.58 1.777 1.004C20.066 16.45 21 18.31 21 20.394">
                    </path>
                </svg>
                <span class="text-sm font-medium">Pelanggan</span>
            </a>

            <a href="{{ route('admin.suppliers.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m0 0l8-4 8 4m0 0v10l-8 4-8-4V7m8 4v10m-8-4l8 4 8-4"></path>
                </svg>
                <span class="text-sm font-medium">Suppliers</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <span class="text-sm font-medium">Produk</span>
            </a>

            <a href="{{ route('admin.kategori_hpps.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Kategori HPP</span>
            </a>

            <a href="{{ route('admin.kategori_hpp_tambahan.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span class="text-sm font-medium">Kategori HPP Tambahan</span>
            </a>
        </div>

        <!-- Konfigurasi Section -->
        <div class="px-4 py-6 border-t border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Konfigurasi</p>

            <a href="{{ route('admin.akuns.coa') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
                <span class="text-sm font-medium">Daftar Akun Keuangan</span>
            </a>

            <a href="{{ route('admin.akuns.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path>
                </svg>
                <span class="text-sm font-medium">Akun Pembayaran</span>
            </a>

            <a href="{{ route('admin.label_transaksis.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Label Transaksi</span>
            </a>

            <a href="{{ route('admin.aturan_automations.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-sm font-medium">Aturan</span>
            </a>

            <details class="group mt-2">
                <summary
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition cursor-pointer">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium flex-1">Dokumen</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180 flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </summary>
                <div class="mt-1 space-y-1 ml-6 border-l-2 border-slate-700 pl-2">
                    <a href="{{ route('admin.invoices.index') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Invoice</a>
                    <a href="{{ route('admin.receipts.index') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Receipt</a>
                    <a href="{{ route('admin.kuitansi.index') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Kuitansi</a>
                    <a href="{{ route('admin.nota.index') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Nota</a>
                </div>
            </details>
        </div>

        <!-- Transaksi Section -->
        <div class="px-4 py-6 border-t border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Transaksi</p>

            <a href="{{ route('admin.penjualans.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Penjualan</span>
            </a>

            <details class="group mt-2">
                <summary
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition cursor-pointer">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="text-sm font-medium flex-1">Pembelian & Produksi</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180 flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </summary>
                <div class="mt-1 space-y-1 ml-6 border-l-2 border-slate-700 pl-2">
                    <a href="{{ route('admin.pembelians.index', 'penerimaan') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Pembelian</a>
                    <a href="{{ route('admin.produksi.create', 'pengeluaran') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Produksi</a>
                </div>
            </details>

            <details class="group mt-2">
                <summary
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition cursor-pointer">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path>
                    </svg>
                    <span class="text-sm font-medium flex-1">Kas & Bank</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180 flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </summary>
                <div class="mt-1 space-y-1 ml-6 border-l-2 border-slate-700 pl-2">
                    <a href="{{ route('admin.kasbank.index', 'penerimaan') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Penerimaan
                        Kas</a>
                    <a href="{{ route('admin.kasbank.index', 'pengeluaran') }}"
                        class="block px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-lg transition">Pengeluaran
                        Kas</a>
                </div>
            </details>

            <a href="{{ route('admin.pembayaran-dimuka.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Pembayaran Dimuka</span>
            </a>

            <a href="{{ route('admin.mutasi_rekening.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                    </path>
                </svg>
                <span class="text-sm font-medium">Mutasi Rekening</span>
            </a>

            <a href="{{ route('admin.penyusutan.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-sm font-medium">Penyusutan</span>
            </a>
        </div>

        <!-- Laporan Keuangan Section -->
        <div class="px-4 py-6 border-t border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Laporan Keuangan</p>

            <a href="{{ route('admin.laporan.laba_rugi') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Laba Rugi</span>
            </a>

            <a href="{{ route('admin.laporan.neraca') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                    </path>
                </svg>
                <span class="text-sm font-medium">Neraca</span>
            </a>

            <a href="{{ route('admin.laporan.arus_kas') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                    </path>
                </svg>
                <span class="text-sm font-medium">Arus Kas</span>
            </a>

            <a href="{{ route('admin.laporan.buku_kas') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <span class="text-sm font-medium">Buku Kas</span>
            </a>

            <a href="{{ route('admin.laporan.jurnal_umum') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Jurnal Umum</span>
            </a>
        </div>
    </nav>

    <!-- User Profile & Logout (Fixed at bottom) -->
    <div class="px-4 py-4 border-t border-slate-800 space-y-2 flex-shrink-0">
        <div
            class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-slate-800 hover:bg-slate-700 transition cursor-pointer">
            <div
                class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->role ?? 'Admin' }}</p>
            </div>
            <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-red-500/20 hover:text-red-400 transition text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
