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
                            <a href="{{ route('member.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white">
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
                                <a href="{{ route('member.riwayat') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-white">Riwayat Peminjaman</a>
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
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('member.riwayat') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Peminjaman Detail Card - Dark Version -->
        <div class="bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-700">
            <!-- Header with ID and Status -->
            <div class="bg-gray-900 px-8 py-6 border-b border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Peminjaman #{{ $peminjaman->kode_peminjaman }}</h1>
                        <p class="text-gray-400 mt-1">ID Transaksi: {{ $peminjaman->id }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-900 text-yellow-200' : 
                               ($peminjaman->status == 'dikembalikan' ? 'bg-green-900 text-green-200' : 
                               ($peminjaman->status == 'terlambat' ? 'bg-red-900 text-red-200' : 'bg-gray-600 text-gray-200')) }}">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-8">
                <!-- Book Information -->
                <div class="bg-gray-700 p-6 rounded-xl border border-gray-600 mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Informasi Buku
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Judul</p>
                            <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->judul }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pengarang</p>
                            <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->pengarang->nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">ISBN</p>
                            <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->isbn }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tahun Terbit</p>
                            <p class="mt-1 text-white font-medium">{{ $peminjaman->buku->tahun_terbit }}</p>
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
                                {{ $peminjaman->tanggal_pinjam->format('d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Kembali</p>
                            <p class="mt-1 text-white font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $peminjaman->tanggal_kembali->format('d F Y H:i') }}
                            </p>
                        </div>
                        @if($peminjaman->tanggal_pengembalian)
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Pengembalian</p>
                            <p class="mt-1 text-white font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $peminjaman->tanggal_pengembalian->format('d F Y H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Overdue Information -->
                @if($isOverdue)
                <div class="bg-red-900/30 border border-red-800 p-6 rounded-xl mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-red-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Peringatan Keterlambatan
                    </h2>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mr-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-white font-medium">Peminjaman ini terlambat {{ $overdueInfo }}</p>
                            <p class="text-red-300 text-sm mt-1">Segera kembalikan buku untuk menghindari denda</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Penalty Information -->
                @if($peminjaman->denda)
                <div class="bg-yellow-900/30 border border-yellow-800 p-6 rounded-xl mb-8">
                    <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-yellow-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Denda
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-medium text-yellow-300 uppercase tracking-wider">Jumlah Denda</p>
                            <p class="mt-1 text-white font-medium">Rp {{ number_format($peminjaman->denda->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-yellow-300 uppercase tracking-wider">Status Pembayaran</p>
                            <p class="mt-1 font-medium">
                                <span class="{{ $peminjaman->denda->status_pembayaran ? 'text-green-400' : 'text-yellow-400' }}">
                                    {{ $peminjaman->denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </p>
                        </div>
                        @if($peminjaman->denda->tanggal_bayar)
                        <div>
                            <p class="text-xs font-medium text-yellow-300 uppercase tracking-wider">Tanggal Bayar</p>
                            <p class="mt-1 text-white font-medium">{{ $peminjaman->denda->tanggal_bayar->format('d F Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-700">
                    @if($peminjaman->status === 'dipinjam')
                        <button onclick="openPerpanjangModal()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Perpanjang Peminjaman
                        </button>
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
        <form id="perpanjangForm" method="POST" action="{{ route('member.peminjaman.perpanjang', $peminjaman->id) }}">
            @csrf
            <div class="mb-4">
                <p class="text-sm text-gray-300 mb-4">Anda dapat memperpanjang peminjaman ini maksimal 7 hari dari tanggal kembali saat ini.</p>
                <p class="text-sm font-medium text-white mb-2">Tanggal Kembali Baru:</p>
                <p class="text-lg font-bold text-purple-400">
                    {{ Carbon\Carbon::parse($peminjaman->tanggal_kembali)->addDays(7)->format('d F Y H:i') }}
                </p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePerpanjangModal()" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                    Konfirmasi Perpanjangan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPerpanjangModal() {
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