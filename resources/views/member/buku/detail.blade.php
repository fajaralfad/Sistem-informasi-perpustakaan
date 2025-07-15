@extends('layouts.app')

@section('title', 'Detail Buku - ' . $buku->judul)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('member.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('member.katalog') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 md:ml-2">Katalog Buku</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium dark:text-white md:ml-2">{{ Str::limit($buku->judul, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="md:flex">
            <!-- Cover Buku -->
            <div class="md:w-1/3 lg:w-1/4 p-6">
                <div class="relative w-full aspect-[2/3] bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md">
                    @if($buku->cover)
                        <img src="{{ Storage::url($buku->cover) }}" 
                             alt="Cover {{ $buku->judul }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-400 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                    
                    <<!-- Status Badge -->
                    <div class="absolute top-2 right-2">
                        @php
                            // Check if book has active bookings or borrowings
                            $hasActiveStatus = $buku->peminjamans()
                                ->whereIn('status', ['booking', 'dipinjam', 'pending'])
                                ->exists();
                        @endphp

                        @if($buku->stok > 0 && !$hasActiveStatus)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak Tersedia
                            </span>
                        @endif
                    </div>
                    
                    <!-- Category Badge -->
                    <div class="absolute top-2 left-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $buku->kategori->nama ?? 'Uncategorized' }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium">Stok:</span> {{ $buku->stok }} buku
                </div>
            </div>

            <!-- Informasi Buku -->
            <div class="md:w-2/3 lg:w-3/4 p-6">
                <!-- Judul dan Info -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-3">{{ $buku->judul }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $buku->pengarang->nama ?? 'Unknown' }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $buku->tahun_terbit }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ISBN: {{ $buku->isbn }}
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($buku->deskripsi)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Deskripsi Buku</h3>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($buku->deskripsi)) !!}
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($buku->stok > 0)
                        @if($canBook)
                            <button onclick="openBookingModal()" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-medium transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Booking Buku
                            </button>
                        @else
                            <button class="w-full flex items-center justify-center px-4 py-3 bg-indigo-300 cursor-not-allowed text-white rounded-lg font-medium" title="{{ $bookingMessage }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Booking Buku
                            </button>
                        @endif
                    @else
                        @if($canBook)
                            <button onclick="openBookingModal()" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-medium transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Booking Buku
                            </button>
                        @else
                            <button class="w-full flex items-center justify-center px-4 py-3 bg-indigo-300 cursor-not-allowed text-white rounded-lg font-medium" title="{{ $bookingMessage }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Booking Buku
                            </button>
                        @endif
                    @endif

                    <button type="button" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg font-medium transition-colors duration-200"
                            onclick="tambahKeWishlist({{ $buku->id }})">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Tambah ke Favorit
                    </button>
                </div>

                <!-- Informasi Peminjaman -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Informasi Peminjaman:</h4>
                    <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                        <li class="flex items-start">
                            <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Datang langsung ke perpustakaan untuk peminjaman</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Bawa kartu anggota yang masih aktif</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Maksimal peminjaman aktif: <strong>5 buku</strong></span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Durasi peminjaman: <strong>1-30 hari</strong></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="{{ route('member.katalog') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Katalog
        </a>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Booking Buku</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="bookingForm" method="POST" action="{{ route('member.peminjaman.booking') }}">
            @csrf
            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
            
            <div class="mb-4">
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Peminjaman</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                       class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       required>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih tanggal saat Anda ingin meminjam buku</p>
                <div id="tanggal_pinjam_error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div class="mb-4">
                <label for="durasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durasi Peminjaman (hari)</label>
                <select id="durasi" name="durasi" class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                    @for ($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }} Hari</option>
                    @endfor
                </select>
                <div id="durasi_error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Ketentuan Booking:</h4>
                <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                    <li class="flex items-start">
                        <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Booking akan aktif setelah admin menyetujui</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Anda akan mendapat notifikasi via email</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Batas maksimal booking: 3 buku</span>
                    </li>
                </ul>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeBookingModal()" 
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" id="submitBooking" 
                        class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Booking Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-3" id="successTitle">Booking Berhasil!</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400" id="successMessage"></p>
            </div>
            <div class="mt-4">
                <button type="button" onclick="closeSuccessModal()" 
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-indigo-900 bg-indigo-100 dark:bg-indigo-700 dark:text-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// All existing JavaScript logic remains unchanged
function openBookingModal() {
    document.getElementById('bookingModal').classList.remove('hidden');
    document.getElementById('bookingModal').classList.add('flex');
    document.getElementById('tanggal_pinjam').valueAsDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
    document.getElementById('bookingModal').classList.remove('flex');
    document.getElementById('tanggal_pinjam_error').classList.add('hidden');
    document.getElementById('durasi_error').classList.add('hidden');
    document.getElementById('bookingForm').reset();
}

function openSuccessModal(title, message) {
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').classList.remove('hidden');
    document.getElementById('successModal').classList.add('flex');
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    document.getElementById('successModal').classList.remove('flex');
    closeBookingModal();
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});

document.getElementById('successModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuccessModal();
    }
});

function tambahKeWishlist(bukuId) {
    fetch(`/member/wishlist/store`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            buku_id: bukuId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Buku berhasil disimpan ke favorit!');
        } else {
            alert('Gagal menyimpan ke favorit: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan ke favorit');
    });
}

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = document.getElementById('submitBooking');
    const originalText = submitButton.textContent;
    
    submitButton.disabled = true;
    submitButton.textContent = 'Memproses...';

    document.getElementById('tanggal_pinjam_error').classList.add('hidden');
    document.getElementById('durasi_error').classList.add('hidden');

    const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
    const durasi = document.getElementById('durasi').value;
    const bukuId = document.querySelector('input[name="buku_id"]').value;
    
    if (!tanggalPinjam) {
        alert('Tanggal peminjaman harus diisi');
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        return;
    }
    
    if (!durasi || isNaN(parseInt(durasi))) {
        alert('Durasi peminjaman harus berupa angka');
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('buku_id', bukuId);
    formData.append('tanggal_pinjam', tanggalPinjam);
    formData.append('durasi', parseInt(durasi).toString());
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Expected JSON but got:', text.substring(0, 500));
                throw new Error('Server returned non-JSON response. Check server logs.');
            });
        }
        
        return response.json().then(data => {
            data._status = response.status;
            return data;
        });
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            closeBookingModal();
            openSuccessModal(
                'Booking Berhasil!', 
                data.message || 'Booking berhasil dikirim dan menunggu konfirmasi admin.'
            );
        } else {
            if (data.errors) {
                if (data.errors.tanggal_pinjam) {
                    const errorElement = document.getElementById('tanggal_pinjam_error');
                    errorElement.textContent = Array.isArray(data.errors.tanggal_pinjam) 
                        ? data.errors.tanggal_pinjam[0] 
                        : data.errors.tanggal_pinjam;
                    errorElement.classList.remove('hidden');
                }
                if (data.errors.durasi) {
                    const errorElement = document.getElementById('durasi_error');
                    errorElement.textContent = Array.isArray(data.errors.durasi) 
                        ? data.errors.durasi[0] 
                        : data.errors.durasi;
                    errorElement.classList.remove('hidden');
                }
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan saat memproses booking'));
            }
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Terjadi kesalahan: ' + error.message + '\nSilakan periksa koneksi internet dan coba lagi.');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
});

document.getElementById('tanggal_pinjam').addEventListener('change', function() {
    const selectedDate = new Date(this.value + 'T00:00:00');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0);
    
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 1);
    maxDate.setHours(23, 59, 59, 999);
    
    if (selectedDate < tomorrow) {
        alert('Tanggal peminjaman harus minimal besok');
        this.value = '';
        return;
    }
    
    if (selectedDate > maxDate) {
        alert('Tanggal peminjaman maksimal 1 bulan dari sekarang');
        this.value = '';
        return;
    }
    
    document.getElementById('tanggal_pinjam_error').classList.add('hidden');
});

document.getElementById('durasi').addEventListener('change', function() {
    const durasi = parseInt(this.value);
    if (isNaN(durasi) || durasi < 1 || durasi > 30) {
        alert('Durasi peminjaman harus antara 1-30 hari');
        this.value = '1';
        return;
    }
    
    document.getElementById('durasi_error').classList.add('hidden');
});
</script>
@endsection