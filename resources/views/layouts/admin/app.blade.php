<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F8FAFC">
    <title>Keuangan - {{ config('app.name', 'Hexagon Inc.') }}</title>
    
    <!-- Prevent Search Engine Indexing & Robot Crawling (Internal Only) -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts: Sora / SF-like typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Script Tailwind Play CDN with Apple Design tokens -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Sora"', '-apple-system', 'BlinkMacSystemFont', '"SF Pro Text"', 'sans-serif'],
                    },
                    colors: {
                        dashboard: '#F8FAFC',
                        card: '#FFFFFF',
                    },
                    boxShadow: {
                        'apple-card': '0 1px 3px 0 rgba(15, 23, 42, 0.02), 0 1px 2px -1px rgba(15, 23, 42, 0.02)',
                        'apple-hover': '0 10px 24px -4px rgba(15, 23, 42, 0.06), 0 4px 10px -2px rgba(15, 23, 42, 0.03)',
                        'apple-glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    },
                    borderRadius: {
                        'apple': '16px',
                        'apple-lg': '20px',
                        'apple-xl': '24px',
                    }
                }
            }
        }
    </script>
    
    <script>
        // Mencegah FOUC / transition layout shift pada sidebar saat load pertama
        document.documentElement.classList.add('no-transitions');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                document.documentElement.classList.remove('no-transitions');
            });
        });
    </script>

    <style>
        body { 
            font-family: 'Sora', -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif; 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .no-transitions, .no-transitions * { transition: none !important; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 antialiased selection:bg-indigo-500/15 selection:text-indigo-600 min-h-screen" 
      x-data="{ sidebarExpanded: true, sidebarHovered: false }">

    {{-- Ambient Apple Depth Lighting (Subtle top gradient glow) --}}
    <div class="fixed top-0 left-0 right-0 h-64 bg-gradient-to-b from-indigo-50/40 via-slate-50/20 to-transparent pointer-events-none -z-10"></div>

    {{-- Main Shell --}}
    <div class="flex min-h-screen relative">
        @include('admin.partials.sidebar')

        {{-- Main Content Viewport --}}
        <div class="flex-1 flex flex-col min-w-0">
            
            {{-- Mobile Header (Apple Translucent Bar) --}}
            <header class="lg:hidden apple-glass-header px-5 py-3.5 flex items-center justify-between sticky top-0 z-30">
                <button @click="sidebarExpanded = !sidebarExpanded" 
                    type="button"
                    class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 active:scale-95 rounded-xl transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/hexagon-icon.png') }}" class="w-7 h-7 object-contain" alt="Hexagon Inc.">
                    <h1 class="text-sm font-bold text-slate-900 tracking-tight">Hexagon Inc.</h1>
                </div>
                <div class="w-9"></div>
            </header>

            {{-- Page Content Slot: Memiliki default statis lg:ml-72 agar nol pergeseran (zero layout shift) saat load --}}
            <main :class="sidebarExpanded ? 'lg:ml-72' : 'lg:ml-20'" 
                  class="flex-1 lg:ml-72 transition-[margin] duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] p-4 sm:p-6 lg:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>