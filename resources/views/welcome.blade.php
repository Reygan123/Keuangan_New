<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceHub - Smart Accounting Solution</title>
    
    <!-- Typography: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F5F7FA] text-[#0F172A] overflow-x-hidden selection:bg-[#3B41E3]/20 selection:text-[#3B41E3]">
    
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-[#EEF2F6]">
        <div class="max-w-[1200px] mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-[#3B41E3] rounded-lg flex items-center justify-center font-bold text-sm text-white shadow-sm">
                        F
                    </div>
                    <span class="text-[20px] font-bold text-[#0F172A] tracking-tight">FinanceHub</span>
                </div>
                
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-all duration-200 text-[14px] shadow-sm hover:shadow">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-[#64748B] hover:text-[#0F172A] font-semibold transition-colors duration-200 text-[14px]">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-all duration-200 text-[14px] shadow-sm hover:shadow">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-40 pb-24 text-center px-6">
        <div class="max-w-[1000px] mx-auto">
            <h1 class="text-[48px] md:text-[64px] font-extrabold text-[#0F172A] leading-[1.1] mb-6 tracking-tight">
                Smart Accounting <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#3B41E3] to-[#8B5CF6]">Made Simple</span>
            </h1>
            <p class="text-[18px] md:text-[20px] text-[#64748B] mb-10 max-w-[600px] mx-auto leading-relaxed">
                Kelola keuangan bisnis Anda dengan sistem akuntansi modern yang powerful, mudah digunakan, dan terintegrasi penuh.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-all duration-200 text-[15px] shadow-lg shadow-[#3B41E3]/25 hover:shadow-xl hover:shadow-[#3B41E3]/20 hover:-translate-y-0.5">
                        Mulai Gratis
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-all duration-200 text-[15px] border border-[#EEF2F6] shadow-sm hover:-translate-y-0.5">
                        Login ke Akun
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-white border-y border-[#EEF2F6]">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-[32px] font-bold text-[#0F172A] mb-4 tracking-tight">Fitur Lengkap untuk Bisnis Anda</h2>
                <p class="text-[16px] text-[#64748B]">Semua yang Anda butuhkan untuk mengelola keuangan dalam satu tempat.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[24px] p-8 hover:shadow-lg hover:shadow-[#0F172A]/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#EFF6FF] rounded-xl flex items-center justify-center text-[24px] mb-6 border border-[#BFDBFE]">
                        📊
                    </div>
                    <h3 class="text-[18px] font-bold text-[#0F172A] mb-3">Laporan Keuangan</h3>
                    <p class="text-[14px] text-[#64748B] leading-relaxed">
                        Laba rugi, neraca, arus kas, dan perubahan ekuitas tersedia secara real-time dengan visualisasi yang jelas.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[24px] p-8 hover:shadow-lg hover:shadow-[#0F172A]/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#FEF2F2] rounded-xl flex items-center justify-center text-[24px] mb-6 border border-[#FECDD3]">
                        💰
                    </div>
                    <h3 class="text-[18px] font-bold text-[#0F172A] mb-3">Manajemen Transaksi</h3>
                    <p class="text-[14px] text-[#64748B] leading-relaxed">
                        Catat transaksi penjualan, pembelian, produksi, kas & bank dengan mudah dan otomatis terjurnal.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[24px] p-8 hover:shadow-lg hover:shadow-[#0F172A]/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#ECFDF5] rounded-xl flex items-center justify-center text-[24px] mb-6 border border-[#A7F3D0]">
                        📈
                    </div>
                    <h3 class="text-[18px] font-bold text-[#0F172A] mb-3">Dashboard Analytics</h3>
                    <p class="text-[14px] text-[#64748B] leading-relaxed">
                        Monitor performa bisnis dengan dashboard interaktif yang menampilkan KPI penting secara real-time.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[24px] p-8 hover:shadow-lg hover:shadow-[#0F172A]/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#FFFBEB] rounded-xl flex items-center justify-center text-[24px] mb-6 border border-[#FDE68A]">
                        🔄
                    </div>
                    <h3 class="text-[18px] font-bold text-[#0F172A] mb-3">Automation Rules</h3>
                    <p class="text-[14px] text-[#64748B] leading-relaxed">
                        Buat aturan otomatis untuk jurnal transaksi, hemat waktu dan kurangi kesalahan input manual.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[24px] p-8 hover:shadow-lg hover:shadow-[#0F172A]/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#F3E8FF] rounded-xl flex items-center justify-center text-[24px] mb-6 border border-[#E9D5FF]">
                        📄
                    </div>
                    <h3 class="text-[18px] font-bold text-[#0F172A] mb-3">Dokumen Digital</h3>
                    <p class="text-[14px] text-[#64748B] leading-relaxed">
                        Generate invoice, nota, kuitansi, dan receipt profesional dalam sekejap dengan template yang dapat disesuaikan.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[24px] p-8 hover:shadow-lg hover:shadow-[#0F172A]/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#F1F5F9] rounded-xl flex items-center justify-center text-[24px] mb-6 border border-[#E2E8F0]">
                        🔐
                    </div>
                    <h3 class="text-[18px] font-bold text-[#0F172A] mb-3">Aman & Terpercaya</h3>
                    <p class="text-[14px] text-[#64748B] leading-relaxed">
                        Data Anda tersimpan dengan enkripsi tingkat enterprise dan backup otomatis setiap hari.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] rounded-[32px] p-12 md:p-16 text-center text-white relative overflow-hidden shadow-2xl">
                <!-- Decorative background elements -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
                    <div class="absolute -top-[50%] -left-[10%] w-[70%] h-[150%] rounded-full bg-gradient-to-r from-[#3B41E3] to-transparent blur-3xl transform rotate-12"></div>
                    <div class="absolute -bottom-[50%] -right-[10%] w-[70%] h-[150%] rounded-full bg-gradient-to-l from-[#8B5CF6] to-transparent blur-3xl transform -rotate-12"></div>
                </div>

                <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
                    <div>
                        <h4 class="text-[40px] md:text-[56px] font-bold mb-2">500+</h4>
                        <p class="text-[14px] md:text-[16px] text-[#94A3B8] font-medium">Bisnis Terdaftar</p>
                    </div>
                    <div>
                        <h4 class="text-[40px] md:text-[56px] font-bold mb-2">50K+</h4>
                        <p class="text-[14px] md:text-[16px] text-[#94A3B8] font-medium">Transaksi/Bulan</p>
                    </div>
                    <div>
                        <h4 class="text-[40px] md:text-[56px] font-bold mb-2">99.9%</h4>
                        <p class="text-[14px] md:text-[16px] text-[#94A3B8] font-medium">Uptime</p>
                    </div>
                    <div>
                        <h4 class="text-[40px] md:text-[56px] font-bold mb-2">24/7</h4>
                        <p class="text-[14px] md:text-[16px] text-[#94A3B8] font-medium">Support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 border-t border-[#EEF2F6] bg-white">
        <div class="max-w-[1200px] mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-2 mb-4">
                <div class="w-6 h-6 bg-[#3B41E3] rounded-md flex items-center justify-center font-bold text-[10px] text-white">F</div>
                <span class="text-[16px] font-bold text-[#0F172A] tracking-tight">FinanceHub</span>
            </div>
            <p class="text-[13px] text-[#64748B] font-medium">&copy; {{ date('Y') }} Reygan Fadhilah. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
