<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex items-center justify-center py-6 px-4 font-sans">
        <div data-animate-stagger class="apple-glass-card max-w-md w-full space-y-8 p-8 sm:p-10 rounded-[24px] border border-slate-200/80 shadow-[0_20px_50px_rgba(15,23,42,0.06)]">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo-hexagon.png') }}" alt="PT Hexagon Karyatama Indonesia" class="h-12 max-w-[280px] w-auto object-contain">
                </div>
                <h2 class="apple-display-title text-2xl sm:text-[28px] font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="mt-2 text-xs sm:text-[14px] text-slate-500 font-medium">Masuk ke portal keuangan internal perusahaan</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <x-input-label for="email" :value="__('Email Pegawai')" class="text-xs sm:text-[13px] font-semibold text-slate-700" />
                    <x-text-input
                        id="email"
                        class="apple-input block w-full text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 text-xs sm:text-sm"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@hexagon.co.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs sm:text-[13px] font-semibold text-slate-700" />
                    <x-text-input
                        id="password"
                        class="apple-input block w-full text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 text-xs sm:text-sm"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 transition duration-150" name="remember">
                        <span class="ms-2 text-xs sm:text-[13px] text-slate-600 font-medium select-none">{{ __('Ingat saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs sm:text-[13px] text-indigo-600 hover:text-indigo-700 font-semibold transition duration-150" href="{{ route('password.request') }}">
                            {{ __('Lupa sandi?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div class="pt-2">
                    <button type="submit" class="apple-button apple-button-primary w-full py-3 sm:py-3.5 rounded-xl text-xs sm:text-sm font-semibold shadow-xs">
                        {{ __('Masuk ke Portal') }}
                    </button>
                </div>

                <!-- Register Link -->
                @if (Route::has('register'))
                <div class="text-center pt-2">
                    <p class="text-xs sm:text-[13px] text-slate-500 font-medium">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition duration-150 ml-1">
                            Daftar di sini
                        </a>
                    </p>
                </div>
                @endif
            </form>
        </div>
    </div>
</x-guest-layout>
