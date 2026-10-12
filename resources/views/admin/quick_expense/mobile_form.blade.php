<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Foto Bukti Pengeluaran - PT Hexagon Karyatama Indonesia</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Strict Anti-Crawler / Internal Enterprise Directives -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen flex flex-col justify-between p-4 selection:bg-indigo-500/20">

    <!-- Header -->
    <header class="text-center pt-3 pb-4">
        <div class="flex justify-center mb-3">
            <img src="{{ asset('images/logo-hexagon.png') }}" class="h-7 w-auto object-contain" alt="Hexagon Inc.">
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100/80 text-indigo-700 text-xs font-bold mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Pairing Komputer Aktif</span>
        </div>
        <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Kirim Bukti Transfer / Nota</h1>
        <p class="text-xs text-slate-500 mt-0.5">PT Hexagon Karyatama Indonesia</p>
    </header>

    <!-- Main Card -->
    <main class="flex-1 max-w-sm w-full mx-auto flex flex-col justify-center">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-5">
            
            <!-- Upload / Camera Area -->
            <div id="dropArea" class="relative">
                <input type="file" id="cameraInput" accept="image/*,application/pdf" capture="environment" class="hidden">
                
                <!-- Placeholder / Initial State -->
                <div id="initialState" onclick="document.getElementById('cameraInput').click()" 
                     class="border-2 border-dashed border-indigo-200 hover:border-indigo-400 bg-indigo-50/40 rounded-2xl p-8 text-center cursor-pointer transition-all active:scale-98">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mx-auto mb-3 shadow-sm shadow-indigo-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="block text-sm font-bold text-slate-900">Jepret Kamera / Pilih File</span>
                    <span class="block text-xs text-slate-500 mt-1">Ketuk untuk mengambil foto bukti transfer atau struk nota fisik</span>
                </div>

                <!-- Preview State (Hidden initially) -->
                <div id="previewState" class="hidden">
                    <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 max-h-80 flex items-center justify-center">
                        <img id="imagePreview" src="" alt="Preview Bukti" class="w-full object-contain max-h-80 rounded-2xl">
                        <button type="button" onclick="resetFile()" class="absolute top-2 right-2 p-2 bg-slate-900/70 hover:bg-slate-900 text-white rounded-full backdrop-blur-sm shadow-md active:scale-95 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <p id="fileNameText" class="text-xs text-slate-500 text-center mt-2 truncate font-mono"></p>
                </div>
            </div>

            <!-- Upload Action Button -->
            <button id="btnKirim" type="button" onclick="uploadFoto()" disabled
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-bold text-sm rounded-2xl shadow-sm active:scale-98 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span id="btnText">Kirim ke Komputer</span>
            </button>

            <!-- Success State Overlay -->
            <div id="successState" class="hidden text-center py-4 space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Berhasil Terkirim!</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Foto bukti pengeluaran telah langsung tampil di layar komputer Anda. Anda dapat kembali ke layar komputer untuk menyelesaikan transaksi.
                </p>
                <button type="button" onclick="resetFile()" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 pt-2">
                    Kirim foto lainnya &rarr;
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-[11px] text-slate-400 py-2">
        Sesi Token: <span class="font-mono text-slate-600">{{ $token }}</span>
    </footer>

    <script>
        const token = "{{ $token }}";
        const cameraInput = document.getElementById('cameraInput');
        const initialState = document.getElementById('initialState');
        const previewState = document.getElementById('previewState');
        const imagePreview = document.getElementById('imagePreview');
        const fileNameText = document.getElementById('fileNameText');
        const btnKirim = document.getElementById('btnKirim');
        const btnText = document.getElementById('btnText');
        const successState = document.getElementById('successState');
        let selectedFile = null;

        cameraInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            selectedFile = file;
            fileNameText.textContent = file.name;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    imagePreview.src = evt.target.result;
                    initialState.classList.add('hidden');
                    previewState.classList.remove('hidden');
                    btnKirim.disabled = false;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                initialState.classList.add('hidden');
                previewState.classList.remove('hidden');
                btnKirim.disabled = false;
            }
        });

        function resetFile() {
            cameraInput.value = '';
            selectedFile = null;
            initialState.classList.remove('hidden');
            previewState.classList.add('hidden');
            successState.classList.add('hidden');
            btnKirim.classList.remove('hidden');
            btnKirim.disabled = true;
            btnText.textContent = 'Kirim ke Komputer';
        }

        async function uploadFoto() {
            if (!selectedFile) return;

            btnKirim.disabled = true;
            btnText.textContent = 'Mengirim ke Komputer...';

            const formData = new FormData();
            formData.append('foto', selectedFile);

            try {
                const response = await fetch("{{ route('quick-expense.mobile.upload', ['token' => $token]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    previewState.classList.add('hidden');
                    btnKirim.classList.add('hidden');
                    successState.classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal mengunggah foto. Silakan coba lagi.');
                    btnKirim.disabled = false;
                    btnText.textContent = 'Kirim ke Komputer';
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan: ' + err.message);
                btnKirim.disabled = false;
                btnText.textContent = 'Kirim ke Komputer';
            }
        }
    </script>
</body>
</html>
