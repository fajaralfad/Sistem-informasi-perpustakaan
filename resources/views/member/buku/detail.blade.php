@extends('layouts.app')

@section('title', 'Detail Buku - ' . $buku->judul)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('member.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
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
                    <a href="{{ route('member.katalog') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Katalog Buku</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ Str::limit($buku->judul, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Cover Buku -->
            <div class="md:w-1/3 lg:w-1/4">
                <div class="p-6">
                    @if($buku->cover)
                        <img src="{{ Storage::url($buku->cover) }}" 
                             alt="Cover {{ $buku->judul }}" 
                             class="w-full max-w-sm mx-auto rounded-lg shadow-md">
                    @else
                        <div class="w-full max-w-sm mx-auto bg-gray-200 rounded-lg shadow-md flex items-center justify-center" style="aspect-ratio: 3/4;">
                            <div class="text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm">Cover tidak tersedia</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Buku -->
            <div class="md:w-2/3 lg:w-3/4 p-6">
                <!-- Judul dan Status -->
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $buku->judul }}</h1>
                    <div class="flex items-center space-x-4">
                        @if($buku->stok > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak Tersedia
                            </span>
                        @endif
                        <span class="text-sm text-gray-600">Stok: {{ $buku->stok }}</span>
                    </div>
                </div>

                <!-- Detail Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Buku</h3>
                        <dl class="space-y-3">
                            <div class="flex items-start">
                                <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">Pengarang:</dt>
                                <dd class="text-sm text-gray-900">{{ $buku->pengarang->nama }}</dd>
                            </div>
                            <div class="flex items-start">
                                <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">Kategori:</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $buku->kategori->nama }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex items-start">
                                <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">ISBN:</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $buku->isbn }}</dd>
                            </div>
                            <div class="flex items-start">
                                <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">Tahun:</dt>
                                <dd class="text-sm text-gray-900">{{ $buku->tahun_terbit }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Aksi -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Aksi</h3>
                        <div class="space-y-3">
                            @if($buku->stok > 0)
                                @if($canBook)
                                    <button onclick="openBookingModal()" 
                                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Booking Buku
                                    </button>
                                @else
                                    <button class="w-full bg-indigo-300 cursor-not-allowed text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center" title="{{ $bookingMessage }}">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Booking Buku
                                    </button>
                                @endif

                                <a href="{{ route('member.riwayat') }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Lihat Riwayat Peminjaman
                                </a>
                                <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-700">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Untuk meminjam buku, silakan datang langsung ke perpustakaan dengan membawa kartu anggota.
                                    </p>
                                </div>
                            @else
                                @if($canBook)
                                    <button onclick="openBookingModal()" 
                                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Booking Buku
                                    </button>
                                @else
                                    <button class="w-full bg-indigo-300 cursor-not-allowed text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center" title="{{ $bookingMessage }}">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Booking Buku
                                    </button>
                                @endif

                                <div class="p-3 bg-red-50 rounded-lg">
                                    <p class="text-sm text-red-700">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Buku sedang tidak tersedia.
                                    </p>
                                </div>
                            @endif

                            <button type="button" 
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center"
                                    onclick="tambahKeWishlist({{ $buku->id }})">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                Simpan ke Favorit
                            </button>
                            
                            <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                                <h4 class="text-sm font-medium text-yellow-800 mb-2">Informasi Peminjaman:</h4>
                                <ul class="text-xs text-yellow-700 space-y-1">
                                    <li>• Datang langsung ke perpustakaan</li>
                                    <li>• Bawa kartu anggota yang masih aktif</li>
                                    <li>• Maksimal peminjaman: 3 buku</li>
                                    <li>• Durasi peminjaman: 7 hari</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($buku->deskripsi)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                    <div class="text-gray-700 leading-relaxed">
                        {!! nl2br(e($buku->deskripsi)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="{{ route('member.katalog') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Katalog
        </a>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Booking Buku</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="bookingForm" method="POST" action="{{ route('member.peminjaman.booking') }}">
            @csrf
            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
            
            <div class="mb-4">
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Peminjaman</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                       class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                <p class="mt-1 text-sm text-gray-500">Pilih tanggal saat Anda ingin meminjam buku</p>
                <div id="tanggal_pinjam_error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div class="mb-4">
                <label for="durasi" class="block text-sm font-medium text-gray-700 mb-2">Durasi Peminjaman (hari)</label>
                <select id="durasi" name="durasi" class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="3">3 Hari</option>
                    <option value="7" selected>7 Hari</option>
                    <option value="14">14 Hari</option>
                </select>
                <div id="durasi_error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800 mb-2">Ketentuan Booking:</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Booking akan aktif setelah admin menyetujui</li>
                    <li>• Anda akan mendapat notifikasi via email</li>
                    <li>• Batas maksimal booking: 3 buku</li>
                    <li>• Harap datang ke perpustakaan sesuai tanggal peminjaman</li>
                </ul>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeBookingModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" id="submitBooking" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Booking Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-3" id="successTitle">Booking Berhasil!</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500" id="successMessage"></p>
            </div>
            <div class="mt-4">
                <button type="button" onclick="closeSuccessModal()" 
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-indigo-900 bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openBookingModal() {
    document.getElementById('bookingModal').classList.remove('hidden');
    document.getElementById('bookingModal').classList.add('flex');
    // Set default date to tomorrow
    document.getElementById('tanggal_pinjam').valueAsDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
    document.getElementById('bookingModal').classList.remove('flex');
    // Clear errors
    document.getElementById('tanggal_pinjam_error').classList.add('hidden');
    document.getElementById('durasi_error').classList.add('hidden');
    // Reset form
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
    // Also close booking modal if open
    closeBookingModal();
    // Refresh page to update button state
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Close modals when clicking outside
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

// Handle booking form submission
// Handle booking form submission
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = document.getElementById('submitBooking');
    const originalText = submitButton.textContent;
    
    // Disable button and show loading state
    submitButton.disabled = true;
    submitButton.textContent = 'Memproses...';

    // Clear previous errors
    document.getElementById('tanggal_pinjam_error').classList.add('hidden');
    document.getElementById('durasi_error').classList.add('hidden');

    // Get form data
    const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
    const durasi = document.getElementById('durasi').value;
    const bukuId = document.querySelector('input[name="buku_id"]').value;
    
    // Validate data before sending
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

    // Prepare form data dengan format yang benar
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('buku_id', bukuId);
    formData.append('tanggal_pinjam', tanggalPinjam); // Format: YYYY-MM-DD
    formData.append('durasi', parseInt(durasi).toString()); // Pastikan integer
    
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
        
        // Check if response is JSON
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
            // Handle validation errors
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
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
});

// Validate date input with better error handling
document.getElementById('tanggal_pinjam').addEventListener('change', function() {
    const selectedDate = new Date(this.value + 'T00:00:00'); // Explicit time to avoid timezone issues
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0); // Reset time for comparison
    
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 1);
    maxDate.setHours(23, 59, 59, 999); // End of day for comparison
    
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
    
    // Clear any previous error
    document.getElementById('tanggal_pinjam_error').classList.add('hidden');
});

// Validate duration input
document.getElementById('durasi').addEventListener('change', function() {
    const durasi = parseInt(this.value);
    if (isNaN(durasi) || durasi < 1 || durasi > 14) {
        alert('Durasi peminjaman harus antara 1-14 hari');
        this.value = '7'; // Reset to default
        return;
    }
    
    // Clear any previous error
    document.getElementById('durasi_error').classList.add('hidden');
});
// Validate date input
document.getElementById('tanggal_pinjam').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 1);
    
    if (selectedDate < tomorrow) {
        alert('Tanggal peminjaman harus minimal besok');
        this.value = '';
    } else if (selectedDate > maxDate) {
        alert('Tanggal peminjaman maksimal 1 bulan dari sekarang');
        this.value = '';
    }
});
</script>
@endsection