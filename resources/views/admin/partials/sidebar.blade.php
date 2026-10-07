<!-- Sidebar Overlay for Mobile (Apple-style Dimming Scrim) -->
<div x-cloak x-show="sidebarExpanded && window.innerWidth < 1024" 
     x-transition:enter="transition-opacity ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarExpanded = false" 
     class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-40 lg:hidden"></div>

<!-- Sidebar Aside Container -->
<aside :class="sidebarExpanded ? 'translate-x-0 w-72' : (sidebarHovered ? 'translate-x-0 w-72 shadow-2xl ring-1 ring-slate-900/5' : '-translate-x-full lg:translate-x-0 lg:w-20')"
    class="bg-white/95 backdrop-blur-xl backdrop-saturate-150 border-r border-slate-200/70 flex flex-col h-[100dvh] fixed left-0 top-0 transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] z-50 select-none"
    @mouseenter="if(window.innerWidth >= 1024) sidebarHovered = true"
    @mouseleave="if(window.innerWidth >= 1024) sidebarHovered = false">

    <!-- Header & Native Apple Sidebar Toggle -->
    <div class="h-16 px-4 border-b border-slate-200/60 flex items-center justify-between flex-shrink-0 relative">
        <!-- Expanded / Hovered View: Brand + Collapse Icon Button -->
        <div class="flex items-center justify-between w-full min-w-0"
             :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : 'flex'">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center font-bold text-sm text-white shadow-xs ring-1 ring-white/20 flex-shrink-0">
                    H
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[14px] font-bold text-slate-900 tracking-tight leading-none">Hexagon</span>
                    <span class="text-[10.5px] font-medium text-slate-400 mt-1 leading-none tracking-wide">Keuangan & Akuntansi</span>
                </div>
            </div>
            <!-- macOS / iPadOS Style Sidebar Toggle Button -->
            <button @click="sidebarExpanded = false; sidebarHovered = false;" 
                type="button"
                title="Ciutkan Sidebar"
                class="hidden lg:flex w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 active:scale-95 items-center justify-center transition-all duration-150 flex-shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="1.8" />
                    <path d="M9 4v16" stroke-width="1.8" />
                </svg>
            </button>
        </div>

        <!-- Collapsed View (Desktop Icon Bar): Centered Expand Button -->
        <div class="w-full flex items-center justify-center"
             :class="(!sidebarExpanded && !sidebarHovered) ? 'flex' : 'hidden'">
            <button @click="sidebarExpanded = true; sidebarHovered = false;" 
                type="button"
                title="Perluas Sidebar"
                class="w-8 h-8 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50/80 active:scale-95 flex items-center justify-center transition-all duration-150">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="1.8" />
                    <path d="M9 4v16" stroke-width="1.8" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Scrollable Navigation (Apple-style system rhythm & grouping) -->
    <nav class="flex-1 overflow-y-auto scrollbar-hide py-3 px-2.5 space-y-4" id="sidebar-nav">

        <!-- Top Pinned Item: Dashboard (Clean, no redundant header) -->
        <div>
            @php $isDashActive = request()->routeIs('admin.dashboard'); @endphp
            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isDashActive ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                title="Dashboard">
                <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isDashActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4h4"></path>
                </svg>
                <span class="whitespace-nowrap transition-opacity duration-200"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Dashboard</span>
            </a>
        </div>

        <!-- Hairline Divider -->
        <div class="border-t border-slate-200/50 my-1 mx-1"
             :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : 'block'"></div>

        <!-- 1. MASTER DATA -->
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3 transition-opacity duration-200 select-none"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Master Data</p>

            <div class="space-y-0.5">
                @php
                $masterMenu = [
                    ['route' => 'admin.usahas.index', 'pattern' => 'admin.usahas.*', 'label' => 'Usaha', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['route' => 'admin.pelanggans.index', 'pattern' => 'admin.pelanggans.*', 'label' => 'Pelanggan', 'icon' => 'M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M3 20.394c0-2.084.934-3.943 2.418-5.247.5-.424 1.099-.804 1.777-1.004a6 6 0 0111.618 0c.678.2 1.277.58 1.777 1.004C20.066 16.45 21 18.31 21 20.394'],
                    ['route' => 'admin.suppliers.index', 'pattern' => 'admin.suppliers.*', 'label' => 'Suppliers', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['route' => 'admin.products.index', 'pattern' => 'admin.products.*', 'label' => 'Produk', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    ['route' => 'admin.kategori_hpps.index', 'pattern' => 'admin.kategori_hpps.*', 'label' => 'Kategori HPP', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                    ['route' => 'admin.kategori_hpp_tambahan.index', 'pattern' => 'admin.kategori_hpp_tambahan.*', 'label' => 'Kategori Tambahan', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                    ['route' => 'admin.karyawan.index', 'pattern' => 'admin.karyawan.*', 'label' => 'Karyawan', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['route' => 'admin.slip-gaji.index', 'pattern' => 'admin.slip-gaji.*', 'label' => 'Slip Gaji', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ];
                @endphp
                @foreach($masterMenu as $menu)
                @php $isActive = request()->routeIs($menu['pattern']); @endphp
                <a href="{{ route($menu['route']) }}"
                    class="group flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                    title="{{ $menu['label'] }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 2. KONFIGURASI -->
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3 transition-opacity duration-200 select-none"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Konfigurasi</p>

            <div class="space-y-0.5">
                @php
                $configMenu = [
                    ['route' => 'admin.akuns.coa', 'pattern' => 'admin.akuns.coa', 'label' => 'Daftar Akun', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                    ['route' => 'admin.akuns.index', 'pattern' => 'admin.akuns.index', 'label' => 'Akun Pembayaran', 'icon' => 'M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z'],
                    ['route' => 'admin.label_transaksis.index', 'pattern' => 'admin.label_transaksis.*', 'label' => 'Label Transaksi', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                    ['route' => 'admin.aturan_automations.index', 'pattern' => 'admin.aturan_automations.*', 'label' => 'Aturan', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                    ['route' => 'admin.aturan_pajaks.index', 'pattern' => 'admin.aturan_pajaks.*', 'label' => 'Aturan Pajak', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2v14a2 2 0 002 2z'],
                ];
                @endphp
                @foreach($configMenu as $menu)
                @php $isActive = request()->routeIs($menu['pattern']); @endphp
                <a href="{{ route($menu['route']) }}"
                    class="group flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                    title="{{ $menu['label'] }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach

                <!-- Dropdown Dokumen (Apple-style clean disclosure) -->
                @php
                $isDokumenActive = request()->routeIs(['admin.invoices.*', 'admin.receipts.*', 'admin.kuitansi.*', 'admin.nota.*']);
                @endphp
                <div x-data="{ open: {{ $isDokumenActive ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        type="button"
                        class="group w-full flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isDokumenActive ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                        title="Dokumen">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isDokumenActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="flex-1 text-left whitespace-nowrap transition-opacity duration-200"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Dokumen</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 ease-out" 
                            :class="[open ? 'rotate-180' : '', (!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100']" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Indented Items (No harsh lines) -->
                    <div x-show="open" 
                        x-transition:enter="transition-all ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition-all ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="pl-8 pr-1 space-y-0.5 mt-0.5" 
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.invoices.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.invoices.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Invoice</a>
                        <a href="{{ route('admin.receipts.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.receipts.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Receipt</a>
                        <a href="{{ route('admin.kuitansi.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.kuitansi.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Kuitansi</a>
                        <a href="{{ route('admin.nota.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.nota.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Nota</a>
                    </div>
                </div>

                <!-- Dropdown Surat -->
                @php
                $isSuratActive = request()->routeIs(['admin.surat-penyerahan.*', 'admin.surat-pemberitahuan.*', 'admin.surat-pernyataan.*', 'admin.berita-acara.*']);
                @endphp
                <div x-data="{ open: {{ $isSuratActive ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        type="button"
                        class="group w-full flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isSuratActive ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                        title="Surat">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isSuratActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="flex-1 text-left whitespace-nowrap transition-opacity duration-200"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Surat</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 ease-out" 
                            :class="[open ? 'rotate-180' : '', (!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100']" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Indented Items (No harsh lines) -->
                    <div x-show="open" 
                        x-transition:enter="transition-all ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition-all ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="pl-8 pr-1 space-y-0.5 mt-0.5" 
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.surat-penyerahan.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.surat-penyerahan.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Surat Penyerahan</a>
                        <a href="{{ route('admin.surat-pemberitahuan.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.surat-pemberitahuan.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Surat Pemberitahuan</a>
                        <a href="{{ route('admin.surat-pernyataan.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.surat-pernyataan.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Surat Pernyataan</a>
                        <a href="{{ route('admin.berita-acara.index') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.berita-acara.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Berita Acara</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. TRANSAKSI -->
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3 transition-opacity duration-200 select-none"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Transaksi</p>

            <div class="space-y-0.5">
                @php $isPenjualanActive = request()->routeIs('admin.penjualans.*'); @endphp
                <a href="{{ route('admin.penjualans.index') }}"
                    class="group flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isPenjualanActive ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                    title="Penjualan">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isPenjualanActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Penjualan</span>
                </a>

                <!-- Dropdown Pembelian & Produksi -->
                @php
                $isBeliActive = request()->routeIs(['admin.pembelians.*', 'admin.produksi.*']);
                @endphp
                <div x-data="{ open: {{ $isBeliActive ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        type="button"
                        class="group w-full flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isBeliActive ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                        title="Beli & Produksi">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isBeliActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="flex-1 text-left whitespace-nowrap transition-opacity duration-200"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Beli & Produksi</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 ease-out" 
                            :class="[open ? 'rotate-180' : '', (!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100']" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Indented Items (No harsh lines) -->
                    <div x-show="open" 
                        x-transition:enter="transition-all ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition-all ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="pl-8 pr-1 space-y-0.5 mt-0.5" 
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.pembelians.index', 'penerimaan') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.pembelians.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Pembelian</a>
                        <a href="{{ route('admin.produksi.create', 'pengeluaran') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ request()->routeIs('admin.produksi.*') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Produksi</a>
                    </div>
                </div>

                <!-- Dropdown Kas & Bank -->
                @php
                $isKasBankActive = request()->routeIs('admin.kasbank.*');
                @endphp
                <div x-data="{ open: {{ $isKasBankActive ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(window.innerWidth >= 1024) { sidebarExpanded = true; sidebarHovered = false; }"
                        type="button"
                        class="group w-full flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isKasBankActive ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                        title="Kas & Bank">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isKasBankActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                        <span class="flex-1 text-left whitespace-nowrap transition-opacity duration-200"
                            :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Kas & Bank</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 ease-out" 
                            :class="[open ? 'rotate-180' : '', (!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100']" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Indented Items (No harsh lines) -->
                    <div x-show="open" 
                        x-transition:enter="transition-all ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition-all ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="pl-8 pr-1 space-y-0.5 mt-0.5" 
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'hidden' : ''">
                        <a href="{{ route('admin.kasbank.index', 'penerimaan') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ (request()->routeIs('admin.kasbank.*') && request()->route('jenis') == 'penerimaan') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Penerimaan Kas</a>
                        <a href="{{ route('admin.kasbank.index', 'pengeluaran') }}" 
                            class="block px-2.5 py-1 text-[12.5px] rounded-lg transition-all duration-150 active:scale-[0.98] {{ (request()->routeIs('admin.kasbank.*') && request()->route('jenis') == 'pengeluaran') ? 'text-indigo-600 font-semibold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 font-medium' }}">Pengeluaran Kas</a>
                    </div>
                </div>

                @php
                $transaksiMenu = [
                    ['route' => 'admin.pembayaran-dimuka.index', 'pattern' => 'admin.pembayaran-dimuka.*', 'label' => 'Pembayaran Dimuka', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'admin.mutasi_rekening.index', 'pattern' => 'admin.mutasi_rekening.*', 'label' => 'Mutasi Rekening', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ['route' => 'admin.penyusutan.index', 'pattern' => 'admin.penyusutan.*', 'label' => 'Penyusutan', 'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6'],
                ];
                @endphp
                @foreach($transaksiMenu as $menu)
                @php $isActive = request()->routeIs($menu['pattern']); @endphp
                <a href="{{ route($menu['route']) }}"
                    class="group flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                    title="{{ $menu['label'] }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 4. LAPORAN KEUANGAN -->
        <div class="pb-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3 transition-opacity duration-200 select-none"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">Laporan Keuangan</p>

            <div class="space-y-0.5">
                @php
                $laporanMenu = [
                    ['route' => 'admin.laporan.pajak', 'pattern' => 'admin.laporan.pajak', 'label' => 'Pajak', 'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
                    ['route' => 'admin.laporan.laba_rugi', 'pattern' => 'admin.laporan.laba_rugi', 'label' => 'Laba Rugi', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                    ['route' => 'admin.laporan.neraca', 'pattern' => 'admin.laporan.neraca', 'label' => 'Neraca', 'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3'],
                    ['route' => 'admin.laporan.perubahan_ekuitas', 'pattern' => 'admin.laporan.perubahan_ekuitas', 'label' => 'Perubahan Ekuitas', 'icon' => 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.laporan.neraca_lajur', 'pattern' => 'admin.laporan.neraca_lajur', 'label' => 'Neraca Lajur', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'admin.laporan.arus_kas', 'pattern' => 'admin.laporan.arus_kas', 'label' => 'Arus Kas', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                    ['route' => 'admin.laporan.buku_kas', 'pattern' => 'admin.laporan.buku_kas', 'label' => 'Buku Kas', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['route' => 'admin.laporan.buku_besar', 'pattern' => 'admin.laporan.buku_besar', 'label' => 'Buku Besar', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['route' => 'admin.laporan.jurnal_umum', 'pattern' => 'admin.laporan.jurnal_umum', 'label' => 'Jurnal Umum', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'admin.jurnal-penyesuaian', 'pattern' => 'admin.jurnal-penyesuaian*', 'label' => 'Jurnal Penyesuaian', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ];
                @endphp
                @foreach($laporanMenu as $menu)
                @php $isActive = request()->routeIs($menu['pattern']); @endphp
                <a href="{{ route($menu['route']) }}"
                    class="group flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-[13px] transition-all duration-150 active:scale-[0.98] {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                    :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center px-0' : ''"
                    title="{{ $menu['label'] }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 transition-colors {{ $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                        :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

    </nav>

    <!-- User Account Footer (Apple macOS System Settings style) -->
    <div class="p-2.5 border-t border-slate-200/60 flex-shrink-0 bg-white/80">
        <div class="flex items-center gap-2.5 p-2 rounded-xl transition-all duration-150 hover:bg-slate-100/70"
            :class="(!sidebarExpanded && !sidebarHovered) ? 'justify-center p-1.5' : ''">
            <!-- Avatar Squircle -->
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs ring-2 ring-white select-none">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <!-- Name & Role -->
            <div class="flex-1 min-w-0 transition-opacity duration-200"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">
                <p class="text-[13px] font-semibold text-slate-800 truncate leading-tight">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-[11px] text-slate-400 capitalize truncate leading-tight mt-0.5">{{ Auth::user()->role ?? 'Administrator' }}</p>
            </div>
            <!-- Logout Action -->
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 transition-opacity duration-200"
                :class="(!sidebarExpanded && !sidebarHovered) ? 'opacity-0 hidden' : 'opacity-100'">
                @csrf
                <button type="submit" 
                    title="Keluar"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 active:scale-95 flex items-center justify-center transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>