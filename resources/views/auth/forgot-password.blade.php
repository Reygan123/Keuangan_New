<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-900 px-4">
        <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl shadow-lg p-6">

            <h2 class="text-xl font-bold text-slate-100 mb-4 text-center">
                Reset Password
            </h2>

            <div class="mb-4 text-sm text-slate-300 leading-relaxed">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <div class="mb-4 text-green-400 text-sm">
                <x-auth-session-status :status="session('status')" />
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" 
                        :value="__('Email')" 
                        class="text-slate-300" />

                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full bg-slate-700 border-slate-600 text-slate-100 placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                    />

                    <x-input-error 
                        :messages="$errors->get('email')" 
                        class="mt-2 text-red-400" />
                </div>

                <div class="flex items-center justify-end pt-2">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>