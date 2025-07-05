{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div class="text-center mb-6"> <!-- Reduced mb-8 to mb-6 -->
        <div class="mx-auto mb-4 flex items-center justify-center"> <!-- Reduced mb-6 to mb-4 -->
            <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-20 w-20 object-contain"> <!-- Reduced size from h-24 w-24 -->
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2"> <!-- Reduced text-3xl to text-2xl -->
            Selamat Datang
        </h2>
        <p class="text-gray-600 dark:text-gray-400"> <!-- Removed text-lg -->
            Silakan masuk ke akun Anda
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5"> <!-- Reduced space-y-6 to space-y-5 -->
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
            <div class="relative group">
                <x-text-input id="email" 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 text-sm shadow-sm" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="contoh@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
            <div class="relative group">
                <x-text-input id="password" 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 text-sm shadow-sm"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="Masukkan password Anda" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1"> <!-- Reduced pt-2 to pt-1 -->
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" 
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded transition duration-200" 
                    name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ __('Ingat saya') }}</span> <!-- Reduced ml-3 to ml-2 -->
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold transition duration-200 hover:underline" 
                   href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="space-y-4"> <!-- Reduced space-y-5 to space-y-4 -->
            <x-primary-button class="w-full justify-center py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Masuk') }}
            </x-primary-button>

            <div class="text-center pt-1"> <!-- Reduced pt-2 to pt-1 -->
                <span class="text-sm text-gray-600 dark:text-gray-400">Belum punya akun? </span>
                <a href="{{ route('register') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-200 hover:underline">
                    Daftar sekarang
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>