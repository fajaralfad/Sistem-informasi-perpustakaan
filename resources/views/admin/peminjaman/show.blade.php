@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header with Breadcrumb - Dark Version -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Peminjaman</h1>
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
                                <span class="ml-1 text-sm font-medium text-white">Detail</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                @if($peminjaman->status === 'dipinjam')
                    <button onclick="openPerpanjangModal({{ $peminjaman->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Perpanjang
                    </button>
                @endif
            </div>
        </div>

        <!-- Peminjaman Detail Card - Dark Version -->
        <div class="bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-700">
            <!-- Header with ID and Status -->
            <div class="bg-gray-900 px-8 py-6 border-b border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Peminjaman #{{ $peminjaman->id }}</h1>
                        <p class="text-gray-400 mt-1">ID Transaksi: {{ $peminjaman->id }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-900 text-yellow-200' : 
                               ($peminjaman->status == 'dikembalikan' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200') }}">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-8">
                <!-- User and Book Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- User Information Card -->
                    <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                        <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi User
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nama</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Email</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Role</p>
                                <p class="mt-1 text-white font-medium">{{ ucfirst($peminjaman->user->role) }}</p>
                            </div>
                            @if($peminjaman->user->telepon)
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Telepon</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->user->telepon }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Book Information Card -->
                    <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                        <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Informasi Buku
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Judul</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->judul }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pengarang</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->pengarang->nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kategori</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->kategori->nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">ISBN</p>
                                <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->isbn }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Information -->
                <div class="bg-gray-700 p-6 rounded-xl border border-gray-600 mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Informasi Tanggal
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Pinjam</p>
                            <p class="mt-1 text-white font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $peminjaman->tanggal_pinjam->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Kembali</p>
                            <p class="mt-1 text-white font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $peminjaman->tanggal_kembali->format('d F Y') }}
                            </p>
                        </div>
                        @if($peminjaman->tanggal_pengembalian)
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Pengembalian</p>
                            <p class="mt-1 text-white font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $peminjaman->tanggal_pengembalian->format('d F Y') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Penalty Information -->
                @if($peminjaman->status === 'terlambat' && $peminjaman->denda)
                <div class="bg-red-900/30 border border-red-800 p-6 rounded-xl mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-red-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Informasi Denda
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-medium text-red-300 uppercase tracking-wider">Jumlah Denda</p>
                            <p class="mt-1 text-white font-medium">Rp {{ number_format($peminjaman->denda->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-red-300 uppercase tracking-wider">Keterangan</p>
                            <p class="mt-1 text-white font-medium">{{ $peminjaman->denda->keterangan }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-red-300 uppercase tracking-wider">Status Pembayaran</p>
                            <p class="mt-1 font-medium">
                                <span class="{{ $peminjaman->denda->status_pembayaran ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $peminjaman->denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-700">
                    @if($peminjaman->status === 'dipinjam')
                        <form action="{{ route('admin.peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Kembalikan Buku
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Perpanjang - Dark Version -->
<div id="perpanjangModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Perpanjang Peminjaman</h3>
            <button onclick="closePerpanjangModal()" class="text-gray-400 hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="perpanjangForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="hari" class="block text-sm font-medium text-gray-300 mb-2">Jumlah Hari</label>
                <input type="number" name="hari" id="hari" min="1" max="14" value="7" 
                       class="w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">Maksimal 14 hari</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePerpanjangModal()" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Perpanjang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPerpanjangModal(peminjamanId) {
        document.getElementById('perpanjangForm').action = `/peminjaman/${peminjamanId}/perpanjang`;
        const modal = document.getElementById('perpanjangModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closePerpanjangModal() {
        const modal = document.getElementById('perpanjangModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Close modal when clicking outside
    document.getElementById('perpanjangModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePerpanjangModal();
        }
    });
</script>
@endsection