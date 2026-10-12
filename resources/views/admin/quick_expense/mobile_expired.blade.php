<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi QR Kedaluwarsa - PT Hexagon Karyatama Indonesia</title>

    <!-- Strict Anti-Crawler / Internal Enterprise Directives -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200/80 p-8 text-center shadow-lg">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo-hexagon.png') }}" class="h-7 w-auto object-contain" alt="Hexagon Inc.">
        </div>
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-900 mb-2">Sesi QR Telah Kedaluwarsa</h2>
        <p class="text-sm text-slate-600 mb-6">
            Kode QR pairing ini sudah kedaluwarsa atau telah digunakan. Silakan buka kembali fitur Pengeluaran Kilat di layar komputer Anda untuk mendapatkan QR baru.
        </p>
        <div class="text-xs text-slate-400 font-medium">
            PT Hexagon Karyatama Indonesia &bull; Sistem Finansial Internal
        </div>
    </div>
</body>
</html>
