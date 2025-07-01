@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="container mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="relative inline-block">
                <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    {{ __('Profile') }}
                </h1>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
            </div>
            <p class="text-gray-600 mt-4 text-lg">Kelola informasi akun dan preferensi Anda</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">

            {{-- Update Profile Information --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
                <div class="relative bg-white backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-white/20 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Informasi Profil</h2>
                            <p class="text-gray-500 text-sm">Perbarui informasi akun dan alamat email Anda</p>
                        </div>
                    </div>
                    <div class="border-l-4 border-blue-500 pl-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
                <div class="relative bg-white backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-white/20 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Keamanan Password</h2>
                            <p class="text-gray-500 text-sm">Pastikan akun Anda menggunakan password yang kuat</p>
                        </div>
                    </div>
                    <div class="border-l-4 border-green-500 pl-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Delete User --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
                <div class="relative bg-gradient-to-br from-red-50 to-pink-50 backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-red-200/50 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-red-700">Zona Berbahaya</h2>
                            <p class="text-red-600 text-sm">Tindakan permanen yang tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    
                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 border border-red-200">
                        <div class="flex items-start space-x-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-800 mb-1">Hapus Akun Permanen</h3>
                                <p class="text-red-600 text-sm mb-4">
                                    Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. 
                                    Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.
                                </p>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-red-400 pl-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Section -->
        <div class="text-center mt-16">
            <div class="inline-flex items-center space-x-2 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="text-sm">Data Anda aman dan terenkripsi</span>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.group:hover .animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #3b82f6, #8b5cf6);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #2563eb, #7c3aed);
}
</style>
@endsection