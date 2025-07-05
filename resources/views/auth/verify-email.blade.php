{{-- resources/views/auth/verify-email.blade.php --}}
<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto mb-4 flex items-center justify-center">
            <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-20 w-20 object-contain">
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Verifikasi Email
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email dengan mengklik link yang baru saja kami kirimkan?') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-indigo-50 dark:bg-gray-700 border border-indigo-200 dark:border-gray-600 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-indigo-700 dark:text-indigo-300 font-medium">
                        {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="space-y-5">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 dark:bg-gray-700 rounded-full mb-4">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Periksa Email Anda</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Kami telah mengirimkan link verifikasi ke email Anda. Klik link tersebut untuk mengaktifkan akun.
                </p>
            </div>

            <div class="flex flex-col space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button class="w-full justify-center py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                    </x-primary-button>
                </form>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">atau</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 px-6 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-medium text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Keluar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Info -->
    <div class="mt-6 bg-indigo-50 dark:bg-gray-700 border border-indigo-200 dark:border-gray-600 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Tidak menerima email?</h4>
                <p class="text-sm text-indigo-700 dark:text-indigo-300 mt-1">
                    Periksa folder spam atau sampah. Jika masih tidak ada, klik tombol "Kirim Ulang" di atas.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>