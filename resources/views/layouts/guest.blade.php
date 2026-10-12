<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hexagon Keuangan') }}</title>

        <!-- Strict Anti-Crawler / Internal Enterprise Directives -->
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

        <!-- Favicon & Touch Icons -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

        <!-- Google Fonts: Sora / SF-like typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F8FAFC] text-slate-900 antialiased selection:bg-indigo-500/15 selection:text-indigo-600 min-h-screen">
        <!-- Ambient Apple Radial Gradient Glow -->
        <div class="fixed inset-0 pointer-events-none -z-10 flex items-center justify-center overflow-hidden">
            <div class="w-[600px] h-[500px] bg-gradient-to-tr from-indigo-500/10 via-violet-500/5 to-transparent blur-[120px] rounded-full"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center p-4">
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
