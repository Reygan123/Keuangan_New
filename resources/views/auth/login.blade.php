<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-[#F5F7FA] py-12 px-4 sm:px-6 lg:px-8 font-sans selection:bg-[#3B41E3]/20 selection:text-[#3B41E3]">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-[24px] shadow-xl shadow-[#0F172A]/5 border border-[#EEF2F6]">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-14 w-14 bg-[#EFF6FF] border border-[#BFDBFE] rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-[#3B41E3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h2 class="text-[28px] font-bold text-[#0F172A] tracking-tight">Welcome Back</h2>
                <p class="mt-2 text-[15px] text-[#64748B]">Sign in to your finance account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <x-input-label for="email" :value="__('Email')" class="text-[14px] font-semibold text-[#0F172A]" />
                    <x-text-input
                        id="email"
                        class="block w-full bg-[#F8FAFC] border-[#EEF2F6] text-[#0F172A] placeholder-[#94A3B8] focus:border-[#3B41E3] focus:ring-[#3B41E3] transition duration-200 rounded-xl px-4 py-3"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-input-label for="password" :value="__('Password')" class="text-[14px] font-semibold text-[#0F172A]" />
                    <x-text-input
                        id="password"
                        class="block w-full bg-[#F8FAFC] border-[#EEF2F6] text-[#0F172A] placeholder-[#94A3B8] focus:border-[#3B41E3] focus:ring-[#3B41E3] transition duration-200 rounded-xl px-4 py-3"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-[#EEF2F6] text-[#3B41E3] focus:ring-[#3B41E3] focus:ring-2 transition duration-200 bg-[#F8FAFC]" name="remember">
                        <span class="ms-2 text-[14px] text-[#64748B] font-medium">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-[14px] text-[#3B41E3] hover:text-[#343AC7] transition duration-200 font-semibold" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#3B41E3]/25 text-[15px] font-semibold text-white bg-[#3B41E3] hover:bg-[#343AC7] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3B41E3] transition-all duration-200 transform hover:-translate-y-0.5">
                        {{ __('Log in') }}
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-[14px] text-[#64748B] font-medium">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-[#3B41E3] hover:text-[#343AC7] transition duration-200 ml-1">
                            Sign up
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
