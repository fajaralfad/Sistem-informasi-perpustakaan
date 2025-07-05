{{-- resources/views/auth/forgot-password.blade.php --}}
<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto mb-4 flex items-center justify-center">
            <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-20 w-20 object-contain">
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Lupa Password?
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            {{ __('Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan link reset password.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                    placeholder="Masukkan email Anda" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Kirim Link Reset Password') }}
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

    <!-- Help Info -->
    <div class="mt-6 bg-indigo-50 dark:bg-gray-700 border border-indigo-200 dark:border-gray-600 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-indigo-700 dark:text-indigo-300">
                    Periksa folder spam jika Anda tidak menerima email dalam beberapa menit.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>