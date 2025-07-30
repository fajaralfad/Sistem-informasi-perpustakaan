@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ __('Profile') }}</h1>
                <p class="text-gray-300 mt-1">Kelola informasi akun dan preferensi Anda</p>
            </div>
            <div class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium border border-gray-700">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Update Profile Information --}}
            @if(auth()->user()->isAdmin())
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Informasi Profil</h2>
                        <p class="text-gray-300 text-sm">Perbarui informasi akun dan alamat email Anda</p>
                    </div>
                </div>
                <div class="border-l-4 border-blue-500 pl-6">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">Nama</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 text-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 text-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition-colors">
                                Simpan Perubahan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-green-500">
                                    {{ __('Profile updated successfully.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Update Password --}}
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Keamanan Password</h2>
                        <p class="text-gray-300 text-sm">Pastikan akun Anda menggunakan password yang kuat</p>
                    </div>
                </div>
                <div class="border-l-4 border-green-500 pl-6">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        @if(auth()->user()->role !== 'admin')
                        <div class="bg-blue-900/10 border-l-4 border-blue-500 p-4 mb-4">
                            <p class="text-sm text-blue-300">Anda hanya dapat mengubah password Anda.</p>
                        </div>
                        @endif

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-300">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" 
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 text-black rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300">Password Baru</label>
                            <input type="password" id="password" name="password" 
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 text-black rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('password')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 text-black rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium transition-colors">
                                Update Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-green-500">
                                    {{ __('Password updated successfully.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Delete User --}}
            @if(auth()->user()->isAdmin())
            <div class="bg-gray-800 rounded-lg p-6 border border-red-500">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-red-400">Zona Berbahaya</h2>
                        <p class="text-red-300 text-sm">Tindakan permanen yang tidak dapat dibatalkan</p>
                    </div>
                </div>
                
                <div class="bg-gray-700 rounded-lg p-4 border border-red-600">
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-red-400 mb-1">Hapus Akun Permanen</h3>
                            <p class="text-gray-300 text-sm mb-4">
                                Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. 
                                Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-l-4 border-red-500 pl-6">
                        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                            @csrf
                            @method('delete')

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                                <input type="password" id="password" name="password" 
                                       class="mt-1 block w-full bg-gray-700 border-gray-600 text-black rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                       placeholder="Masukkan password untuk konfirmasi">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="button" 
                                        onclick="confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.') && this.closest('form').submit()"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium transition-colors">
                                    Hapus Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<style>
/* Custom scrollbar dark theme */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #6B7280;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #9CA3AF;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Hover effects */
.bg-gray-800:hover {
    background-color: #1F2937;
}
</style>
@endsection