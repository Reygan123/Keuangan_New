<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan - {{ config('app.name', 'Hexagon') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            /* Internet Explorer 10+ */
            scrollbar-width: none;
            /* Firefox */
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
            /* Safari and Chrome */
        }
    </style>
</head>

<body class="bg-slate-950 text-white" x-data="{ sidebarExpanded: true }">

    {{-- Sidebar Container --}}
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Mobile Header --}}
            <div class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-3 flex items-center justify-between">
                <button @click="sidebarExpanded = !sidebarExpanded" class="p-2 hover:bg-slate-800 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold">Keuangan</h1>
            </div>

            {{-- Page Content --}}
            <main class="flex-1 lg:ml-64 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
