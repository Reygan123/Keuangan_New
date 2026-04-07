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

            <a href="{{ route('super_admin.dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('super_admin.dashboard') ? 'bg-blue-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
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

            <a href="{{ route('super_admin.users.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition mt-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium">Pembuatan akun sistem</span>
            </a>



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
