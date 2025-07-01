{{-- resources/views/auth/verify-email.blade.php --}}
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-100 via-white to-cyan-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Email</h2>
                <p class="text-gray-600 text-center max-w-sm mx-auto">
                    {{ __('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email dengan mengklik link yang baru saja kami kirimkan?') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">
                                {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
                <div class="space-y-6">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Periksa Email Anda</h3>
                        <p class="text-sm text-gray-600 mb-6">
                            Kami telah mengirimkan link verifikasi ke email Anda. Klik link tersebut untuk mengaktifkan akun.
                        </p>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <x-primary-button class="w-full justify-center py-3 px-4 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                                {{ __('Kirim Ulang Email Verifikasi') }}
                            </x-primary-button>
                        </form>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">atau</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                {{ __('Keluar') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Info -->
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-teal-800">Tidak menerima email?</h4>
                        <p class="text-sm text-teal-700 mt-1">
                            Periksa folder spam atau sampah. Jika masih tidak ada, klik tombol "Kirim Ulang" di atas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>