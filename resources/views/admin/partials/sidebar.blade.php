<!-- Sidebar Overlay for Mobile -->
<div x-cloak x-show="sidebarExpanded && window.innerWidth < 1024" 
     x-transition.opacity 
     @click="sidebarExpanded = false" 
     class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm z-40 lg:hidden"></div>

<aside :class="sidebarExpanded ? 'translate-x-0 w-72' : (sidebarHovered ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 lg:w-20')"
    class="bg-white border-r border-[#EEF2F6] flex flex-col h-[100dvh] fixed left-0 top-0 transition-all duration-300 z-50 shadow-[0_20px_50px_-10px_rgba(15,23,42,0.12)] lg:shadow-none"
    @mouseenter="if(window.innerWidth >= 1024) sidebarHovered = true"
    @mouseleave="if(window.innerWidth >= 1024) sidebarHovered = false">

    <div class="px-5 py-6 border-b border-[#EEF2F6] flex-shrink-0 flex items-center h-[80px] relative">
        <div class="flex items-center gap-3 w-full">
            <div class="w-10 h-10 bg-[#3B41E3] rounded-xl flex items-center justify-center font-bold text-lg text-white flex-shrink-0 shadow-md">
                H
            </div>
            <h1 class="text-[18px] font-bold text-[#0F172A] whitespace-nowrap transition-opacity duration-300"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Hexagon</h1>
        </div>

        <!-- Toggle Collapse Button -->
<button @click="sidebarExpanded = !sidebarExpanded; sidebarHovered = false;" 
        class="bg-white border border-[#EEF2F6] shadow-md w-7 h-7 lg:w-6 lg:h-6 rounded-full flex items-center justify-center text-[#64748B] hover:text-[#0F172A] hover:border-[#C7D2FE] z-[60] transition-all duration-300"
        :class="[!sidebarExpanded ? 'fixed left-3 top-5 lg:absolute lg:-right-3 lg:top-7 lg:left-auto' : 'absolute -right-3 top-7']">
    <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="sidebarExpanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
    </svg>
</button>
    </div>

    <!-- Scrollable Navigation -->
    <nav class="flex-1 overflow-y-auto scrollbar-hide py-5 space-y-6" id="sidebar-nav">

        <!-- 1. Dashboard -->
        <div class="px-4">
            <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2 px-2 transition-opacity duration-300"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Dashboard</p>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F172A] text-white shadow-md' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]' }}" title="Dashboard">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4h4"></path>
                </svg>
                <span class="text-[14px] font-semibold whitespace-nowrap transition-opacity duration-300"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Dashboard</span>
            </a>
        </div>

        <!-- 2. Master Data -->
        <div class="px-4">
            <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2 px-2 transition-opacity duration-300"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Master Data</p>

            <div class="space-y-1">
                @php
                $masterMenu = [
                ['route' => 'admin.usahas.index', 'label' => 'Usaha', 'icon' => 'M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z'],
                ['route' => 'admin.pelanggans.index', 'label' => 'Pelanggan', 'icon' => 'M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M3 20.394c0-2.084.934-3.943 2.418-5.247.5-.424 1.099-.804 1.777-1.004a6 6 0 0111.618 0c.678.2 1.277.58 1.777 1.004C20.066 16.45 21 18.31 21 20.394'],
                ['route' => 'admin.suppliers.index', 'label' => 'Suppliers', 'icon' => 'M20 7l-8-4-8 4m0 0l8-4 8 4m0 0v10l-8 4-8-4V7m8 4v10m-8-4l8 4 8-4'],
                ['route' => 'admin.products.index', 'label' => 'Produk', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                ['route' => 'admin.kategori_hpps.index', 'label' => 'Kategori HPP', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'admin.kategori_hpp_tambahan.index', 'label' => 'Kategori Tambahan', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6']
                ];
                @endphp
                @foreach($masterMenu as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs($menu['route']) ? 'bg-[#0F172A] text-white shadow-md' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]' }}" title="{{ $menu['label'] }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="text-[14px] font-medium whitespace-nowrap transition-opacity duration-300"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 3. Konfigurasi -->
        <div class="px-4">
            <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2 px-2 transition-opacity duration-300"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Konfigurasi</p>

            <div class="space-y-1">
                @php
                $configMenu = [
                ['route' => 'admin.akuns.coa', 'label' => 'Daftar Akun', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                ['route' => 'admin.akuns.index', 'label' => 'Akun Pembayaran', 'icon' => 'M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z'],
                ['route' => 'admin.label_transaksis.index', 'label' => 'Label Transaksi', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'admin.aturan_automations.index', 'label' => 'Aturan', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                ['route' => 'admin.aturan_pajaks.index', 'label' => 'Aturan Pajak', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2v14a2 2 0 002 2z']
                ];
                @endphp
                @foreach($configMenu as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs($menu['route']) ? 'bg-[#0F172A] text-white shadow-md' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]' }}" title="{{ $menu['label'] }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="text-[14px] font-medium whitespace-nowrap transition-opacity duration-300"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach

                <!-- Dropdown Dokumen -->
                <div x-data="{ open: false }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]" title="Dokumen">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-[14px] font-medium flex-1 text-left whitespace-nowrap transition-opacity duration-300"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Dokumen</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-[42px] pr-3 py-1 space-y-1 mt-1" :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.invoices.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Invoice</a>
                        <a href="{{ route('admin.receipts.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Receipt</a>
                        <a href="{{ route('admin.kuitansi.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Kuitansi</a>
                        <a href="{{ route('admin.nota.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Nota</a>
                    </div>
                </div>

                <!-- Dropdown Surat -->
                <div x-data="{ open: false }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]" title="Surat">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-[14px] font-medium flex-1 text-left whitespace-nowrap transition-opacity duration-300"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Surat</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-[42px] pr-3 py-1 space-y-1 mt-1" :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.surat-penyerahan.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Surat Penyerahan</a>
                        <a href="{{ route('admin.surat-pemberitahuan.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Surat Pemberitahuan</a>
                        <a href="{{ route('admin.surat-pernyataan.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Surat Pernyataan</a>
                        <a href="{{ route('admin.berita-acara.index') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Berita Acara</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Transaksi -->
        <div class="px-4">
            <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2 px-2 transition-opacity duration-300"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Transaksi</p>

            <div class="space-y-1">
                <a href="{{ route('admin.penjualans.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.penjualans.*') ? 'bg-[#0F172A] text-white shadow-md' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]' }}" title="Penjualan">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-[14px] font-medium whitespace-nowrap transition-opacity duration-300"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Penjualan</span>
                </a>

                <!-- Dropdown Pembelian & Produksi -->
                <div x-data="{ open: false }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]" title="Pembelian & Produksi">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="text-[14px] font-medium flex-1 text-left whitespace-nowrap transition-opacity duration-300"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Beli & Produksi</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-[42px] pr-3 py-1 space-y-1 mt-1" :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.pembelians.index', 'penerimaan') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Pembelian</a>
                        <a href="{{ route('admin.produksi.create', 'pengeluaran') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Produksi</a>
                    </div>
                </div>

                <!-- Dropdown Kas & Bank -->
                <div x-data="{ open: false }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]" title="Kas & Bank">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                        <span class="text-[14px] font-medium flex-1 text-left whitespace-nowrap transition-opacity duration-300"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Kas & Bank</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-[42px] pr-3 py-1 space-y-1 mt-1" :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.kasbank.index', 'penerimaan') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Penerimaan Kas</a>
                        <a href="{{ route('admin.kasbank.index', 'pengeluaran') }}" class="block px-3 py-2 text-[13px] font-medium text-[#64748B] hover:text-[#3B41E3] hover:bg-[#EEF2FF] rounded-lg transition-colors">Pengeluaran Kas</a>
                    </div>
                </div>

                @php
                $transaksiMenu = [
                ['route' => 'admin.pembayaran-dimuka.index', 'label' => 'Pembayaran Dimuka', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['route' => 'admin.mutasi_rekening.index', 'label' => 'Mutasi Rekening', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                ['route' => 'admin.penyusutan.index', 'label' => 'Penyusutan', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z']
                ];
                @endphp
                @foreach($transaksiMenu as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs($menu['route']) ? 'bg-[#0F172A] text-white shadow-md' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]' }}" title="{{ $menu['label'] }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="text-[14px] font-medium whitespace-nowrap transition-opacity duration-300"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 5. Laporan Keuangan -->
        <div class="px-4 pb-4">
            <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2 px-2 transition-opacity duration-300"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Laporan Keuangan</p>

            <div class="space-y-1">
                @php
                $laporanMenu = [
                ['route' => 'admin.laporan.pajak', 'label' => 'Pajak', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2v14a2 2 0 002 2z'],
                ['route' => 'admin.laporan.laba_rugi', 'label' => 'Laba Rugi', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'admin.laporan.neraca', 'label' => 'Neraca', 'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3'],
                ['route' => 'admin.laporan.perubahan_ekuitas', 'label' => 'Perubahan Ekuitas', 'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3'],
                ['route' => 'admin.laporan.neraca_lajur', 'label' => 'Neraca Lajur', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['route' => 'admin.laporan.arus_kas', 'label' => 'Arus Kas', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                ['route' => 'admin.laporan.buku_kas', 'label' => 'Buku Kas', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route' => 'admin.laporan.buku_besar', 'label' => 'Buku Besar', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route' => 'admin.laporan.jurnal_umum', 'label' => 'Jurnal Umum', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['route' => 'admin.jurnal-penyesuaian', 'label' => 'Jurnal Penyesuaian', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z']
                ];
                @endphp
                @foreach($laporanMenu as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs($menu['route']) ? 'bg-[#0F172A] text-white shadow-md' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#0F172A]' }}" title="{{ $menu['label'] }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="text-[14px] font-medium whitespace-nowrap transition-opacity duration-300"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

    </nav>

    <!-- User Profile & Logout -->
    <div class="p-4 border-t border-[#EEF2F6] flex-shrink-0 bg-white">
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl transition-opacity duration-300"
            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">
            <div class="w-9 h-9 bg-[#E0E7FF] text-[#3B41E3] rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm border border-[#C7D2FE]">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[14px] font-bold text-[#0F172A] truncate leading-tight">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-[12px] text-[#64748B] truncate leading-tight">{{ Auth::user()->role ?? 'Admin' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[#64748B] hover:bg-[#FEF2F2] hover:text-[#E11D48] transition-colors" title="Logout">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="text-[14px] font-medium whitespace-nowrap transition-opacity duration-300"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Logout</span>
            </button>
        </form>
    </div>
</aside>