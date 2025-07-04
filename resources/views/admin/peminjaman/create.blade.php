@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header dengan Breadcrumb - Dark Version -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    Tambah Peminjaman Baru
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
                                <a href="{{ route('admin.peminjaman.index') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-white">Peminjaman</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-300">Tambah</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Flash Messages - Dark Version -->
        @if (session('success'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 4000)" 
                class="mb-6 bg-green-900 text-green-200 px-4 py-3 rounded-lg flex items-center border border-green-700 transition-all duration-300 ease-in-out"
            >
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 6000)" 
                class="mb-6 bg-red-900 text-red-200 px-4 py-3 rounded-lg flex items-center border border-red-700 transition-all duration-300 ease-in-out"
            >
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Container - Dark Version -->
        <div class="bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-700">
            <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom 1 -->
                    <div class="space-y-4">
                        <!-- Anggota -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-300 mb-1">Anggota <span class="text-red-400">*</span></label>
                            <select name="user_id" id="user_id" required
                                    class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                <option value="">Pilih Anggota</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buku -->
                        <div>
                            <label for="buku_id" class="block text-sm font-medium text-gray-300 mb-1">Buku <span class="text-red-400">*</span></label>
                            <select name="buku_id" id="buku_id" required
                                    class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                <option value="">Pilih Buku</option>
                                @foreach($bukus as $buku)
                                    <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                        {{ $buku->judul }} (Stok: {{ $buku->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('buku_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu Peminjaman -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">Waktu Peminjaman</label>
                            
                            <div class="space-y-3">
                                <div>
                                    <label for="tanggal_pinjam" class="block text-xs font-medium text-gray-400 mb-1">Tanggal Pinjam <span class="text-red-400">*</span></label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required
                                           value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}"
                                           class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    @error('tanggal_pinjam')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="jam_pinjam" class="block text-xs font-medium text-gray-400 mb-1">Jam Pinjam</label>
                                    <input type="time" name="jam_pinjam" id="jam_pinjam"
                                           value="{{ old('jam_pinjam', now()->format('H:i')) }}"
                                           class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <p class="mt-1 text-xs text-gray-400">Kosongkan untuk menggunakan waktu sekarang</p>
                                    @error('jam_pinjam')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="space-y-4">
                        <!-- Waktu Pengembalian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">Waktu Pengembalian</label>
                            
                            <div class="space-y-3">
                                <div>
                                    <label for="tanggal_kembali" class="block text-xs font-medium text-gray-400 mb-1">Tanggal Kembali <span class="text-red-400">*</span></label>
                                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" required
                                           value="{{ old('tanggal_kembali', now()->addDays(7)->format('Y-m-d')) }}"
                                           class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    @error('tanggal_kembali')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="jam_kembali" class="block text-xs font-medium text-gray-400 mb-1">Jam Kembali</label>
                                    <input type="time" name="jam_kembali" id="jam_kembali"
                                           value="{{ old('jam_kembali', now()->format('H:i')) }}"
                                           class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <p class="mt-1 text-xs text-gray-400">Kosongkan untuk menggunakan jam yang sama dengan jam pinjam</p>
                                    @error('jam_kembali')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="bg-blue-900 border border-blue-700 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-200">Informasi Peminjaman</h3>
                                    <div class="mt-2 text-sm text-blue-300">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Maksimal peminjaman aktif: 5 buku per anggota</li>
                                            <li>Durasi peminjaman default: 7 hari</li>
                                            <li>Jika jam tidak diisi, akan menggunakan waktu saat ini</li>
                                            <li>Denda keterlambatan: Rp 5.000 per hari</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi - Dark Version -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.peminjaman.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jamPinjam = document.getElementById('jam_pinjam');
    const jamKembali = document.getElementById('jam_kembali');
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali');

    // Auto-update jam kembali ketika jam pinjam berubah
    jamPinjam.addEventListener('change', function() {
        if (jamKembali.value === '' || jamKembali.value === jamPinjam.defaultValue) {
            jamKembali.value = this.value;
        }
    });

    // Auto-update tanggal kembali ketika tanggal pinjam berubah (tambah 7 hari)
    tanggalPinjam.addEventListener('change', function() {
        const pinjamDate = new Date(this.value);
        const kembaliDate = new Date(pinjamDate);
        kembaliDate.setDate(pinjamDate.getDate() + 7);
        
        const year = kembaliDate.getFullYear();
        const month = String(kembaliDate.getMonth() + 1).padStart(2, '0');
        const day = String(kembaliDate.getDate()).padStart(2, '0');
        
        tanggalKembali.value = `${year}-${month}-${day}`;
    });

    // Validasi waktu kembali tidak boleh lebih awal dari waktu pinjam
    function validateDateTime() {
        const pinjamDateTime = new Date(tanggalPinjam.value + 'T' + jamPinjam.value);
        const kembaliDateTime = new Date(tanggalKembali.value + 'T' + jamKembali.value);
        
        if (kembaliDateTime <= pinjamDateTime) {
            alert('Waktu pengembalian harus setelah waktu peminjaman!');
            return false;
        }
        return true;
    }

    // Validasi saat form disubmit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateDateTime()) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

@section('scripts')
{{-- Tambahkan Alpine.js jika belum ada --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection