@extends('layouts.app')

@section('content')
@php
    $isEdit = isset($user);
    $title = $isEdit ? 'Edit Data Anggota' : 'Tambah Anggota Baru';
    $buttonText = $isEdit ? 'Simpan Perubahan' : 'Tambah Anggota';
    $route = $isEdit ? route('admin.anggota.update', $user) : route('admin.anggota.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header dengan Breadcrumb - Dark Version -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    {{ $title }}
                </h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.anggota.index') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-white">Anggota</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-white">{{ $isEdit ? 'Edit' : 'Tambah Baru' }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Flash Messages - Dark Version -->
        @if($errors->any())
        <div class="bg-red-900 text-red-200 px-4 py-3 rounded-lg mb-6 flex items-center border border-red-700">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>Terjadi kesalahan. Silakan periksa form di bawah.</span>
        </div>
        @endif

        <!-- Form Container - Dark Version -->
        <div class="bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-700">
            <form method="POST" action="{{ $route }}" class="p-6">
                @csrf
                @method($method)

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom 1 -->
                    <div class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">
                                Nama Lengkap <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                                   class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="Masukkan nama lengkap anggota" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-300 mb-1">
                                NIP
                            </label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip', $user->nip ?? '') }}"
                                class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Nomor Induk Pegawai">
                            @error('nip')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NRP -->
                        <div>
                            <label for="nrp" class="block text-sm font-medium text-gray-300 mb-1">
                                NRP
                            </label>
                            <input type="text" id="nrp" name="nrp" value="{{ old('nrp', $user->nrp ?? '') }}"
                                class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Nomor Registrasi Pegawai">
                            @error('nrp')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                                   class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="contoh@email.com" required {{ $isEdit ? 'readonly' : '' }}>
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">
                                Nomor Telepon
                                <span class="text-gray-400 text-xs ml-2">(Opsional)</span>
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                   class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="081234567890">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                        <!-- Preview Foto Profil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Foto Profil</label>
                            <div class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg border border-gray-600">
                                @if($isEdit && $user->foto)
                                <div class="relative">
                                    <img src="{{ route('admin.anggota.foto', $user->foto) }}" 
                                         alt="Foto {{ $user->name }}" 
                                         class="h-12 w-12 rounded-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm text-gray-300">Foto profil saat ini</p>
                                    <p class="text-xs text-gray-400">Anggota dapat mengubah foto nanti</p>
                                </div>
                                @else
                                <div class="bg-blue-600 bg-opacity-20 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-300">{{ $isEdit ? 'Tidak ada foto' : 'Foto profil default' }}</p>
                                    <p class="text-xs text-gray-400">Anggota dapat mengupload foto nanti</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($isEdit)
                        <!-- Status Verifikasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Status Verifikasi</label>
                            <div class="p-3 bg-gray-700 rounded-lg border border-gray-600">
                                <div class="flex items-center">
                                    @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Terverifikasi
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Belum Verifikasi
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-2">
                                    @if($user->email_verified_at)
                                        Terverifikasi pada {{ $user->email_verified_at->format('d M Y H:i') }}
                                    @else
                                        Anggota belum memverifikasi email mereka
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if(!$isEdit)
                <!-- Informasi Verifikasi - Dark Version -->
                <div class="mt-6 p-4 bg-blue-900 bg-opacity-30 rounded-lg border border-blue-700">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-300">Informasi Verifikasi</h3>
                            <p class="text-sm text-blue-200 mt-1">
                                Anggota akan menerima email verifikasi dan harus memverifikasi email mereka sendiri saat pertama kali login.
                                Password sementara akan dikirimkan ke email mereka.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tombol Aksi - Dark Version -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.anggota.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ $buttonText }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-focus pada field nama
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('name').focus();
});

// Validasi real-time untuk email
document.getElementById('email').addEventListener('input', function(e) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailRegex.test(e.target.value)) {
        e.target.classList.add('border-green-500');
        e.target.classList.remove('border-red-500');
    } else {
        e.target.classList.add('border-red-500');
        e.target.classList.remove('border-green-500');
    }
});
</script>
@endsection