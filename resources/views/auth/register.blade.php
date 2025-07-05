{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto mb-4 flex items-center justify-center">
            <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-20 w-20 object-contain">
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Buat Akun Baru
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            Bergabunglah dengan kami hari ini
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
            <div class="relative group">
                <x-text-input id="name" 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 text-sm shadow-sm"
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap Anda" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
            <div class="relative group">
                <x-text-input id="password_confirmation" 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 text-sm shadow-sm"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ulangi password Anda" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>

            <div class="text-center pt-1">
                <span class="text-sm text-gray-600 dark:text-gray-400">Sudah punya akun? </span>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-200 hover:underline">
                    Masuk di sini
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>