<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-800 px-4">

        <div class="w-full max-w-md bg-slate-800 shadow-2xl rounded-2xl p-8 border border-slate-700">

            <h2 class="text-2xl font-bold text-center text-white mb-2">
                Reset Password
            </h2>
            <p class="text-sm text-slate-400 text-center mb-6">
                Silakan masukkan password baru Anda
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-slate-300"/>
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full bg-slate-700 border border-slate-600 text-white rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-500/20"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-slate-300"/>
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full bg-slate-700 border border-slate-600 text-white rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-500/20"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-300"/>
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full bg-slate-700 border border-slate-600 text-white rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-500/20"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 transition duration-200 rounded-lg">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>

            </form>
        </div>

    </div>
</x-guest-layout>
