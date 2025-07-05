{{-- resources/views/auth/reset-password.blade.php --}}
<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto mb-4 flex items-center justify-center">
            <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-20 w-20 object-contain">
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Reset Password
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            Buat password baru untuk akun Anda
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
            <div class="relative group">
                <x-text-input id="email" 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 text-sm shadow-sm" 
                    type="email" 
                    name="email" 
                    :value="old('email', $request->email)" 
                    required 
                    autofocus 
                    autocomplete="username"
                    readonly />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password Baru')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
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
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-gray-700 dark:text-gray-300 font-semibold text-sm" />
            <div class="relative group">
                <x-text-input id="password_confirmation" 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 text-sm shadow-sm"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ulangi password baru Anda" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Reset Password') }}
            </x-primary-button>

            <div class="text-center pt-1">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-200 hover:underline inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke halaman login
                </a>
            </div>
        </div>
    </form>

    <!-- Security Info -->
    <div class="mt-6 bg-indigo-50 dark:bg-gray-700 border border-indigo-200 dark:border-gray-600 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-indigo-700 dark:text-indigo-300">
                    Pastikan password baru Anda kuat dan mudah diingat. Kombinasikan huruf, angka, dan simbol untuk keamanan maksimal.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>