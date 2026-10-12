<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="apple-headline font-bold text-xl sm:text-2xl text-slate-900 tracking-tight leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs ring-1 ring-emerald-500/10">
                Sesi Aktif
            </span>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-animate-stagger class="apple-glass-card rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-[0_1px_3px_rgba(15,23,42,0.02)] space-y-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm shadow-2xs">
                        ✓
                    </div>
                    <div>
                        <h3 class="apple-headline font-bold text-base sm:text-lg text-slate-900">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="text-xs sm:text-sm text-slate-500">Anda telah berhasil masuk ke sistem internal PT Hexagon Karyatama Indonesia.</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="apple-button apple-button-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-xs">
                        <span>Buka Dashboard Keuangan Admin</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
