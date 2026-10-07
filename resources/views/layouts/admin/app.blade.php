<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan - {{ config('app.name', 'Hexagon') }}</title>
    
    <!-- Google Fonts: Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Script Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Sora"', 'sans-serif'],
                    },
                    colors: {
                        dashboard: '#F8FAFC',
                        card: '#FFFFFF',
                    },
                    boxShadow: {
                        'apple-card': '0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02)',
                        'apple-hover': '0 4px 12px 0 rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif; -webkit-font-smoothing: antialiased; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 antialiased" x-data="{ sidebarExpanded: true, sidebarHovered: false }">

    {{-- Sidebar Container --}}
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Mobile Header --}}
            <div class="lg:hidden bg-white/80 backdrop-blur-xl border-b border-slate-200/60 px-5 py-3.5 flex items-center justify-between sticky top-0 z-30">
                <button @click="sidebarExpanded = !sidebarExpanded" 
                    class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 active:scale-95 rounded-xl transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center font-bold text-xs shadow-xs">H</div>
                    <h1 class="text-base font-bold text-slate-900 tracking-tight">Hexagon</h1>
                </div>
                <div class="w-9"></div> <!-- Spacer untuk menyeimbangkan posisi -->
            </div>

            {{-- Page Content --}}
            <main :class="sidebarExpanded ? 'lg:ml-72' : 'lg:ml-20'" 
                  class="flex-1 transition-[margin] duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>