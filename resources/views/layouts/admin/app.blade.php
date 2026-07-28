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
                        dashboard: '#F5F7FA',
                        card: '#FFFFFF',
                    },
                    boxShadow: {
                        'card-md': '0 4px 16px rgba(15, 23, 42, 0.03)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Sora', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-dashboard text-[#0F172A] antialiased" x-data="{ sidebarExpanded: true }">

    {{-- Sidebar Container --}}
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Mobile Header --}}
            <div class="lg:hidden bg-white border-b border-[#EEF2F6] px-6 py-4 flex items-center justify-between shadow-sm sticky top-0 z-30">
                <button @click="sidebarExpanded = !sidebarExpanded" class="p-2 text-[#64748B] hover:bg-[#F1F5F9] rounded-xl transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-[#0F172A]">Keuangan</h1>
                <div class="w-8"></div> <!-- Spacer untuk menyeimbangkan posisi -->
            </div>

            {{-- Page Content --}}
            <main class="flex-1 lg:ml-[280px] p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>