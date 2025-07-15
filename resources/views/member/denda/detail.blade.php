@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header with Breadcrumb - Dark Version -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Denda</h1>
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
                                <a href="{{ route('member.denda.index') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-white">Denda Saya</a>
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
                <a href="{{ route('member.denda.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                
                <!-- Action Buttons for Member -->
                @if(!$denda->status_pembayaran)
                    @if(!$denda->bukti_pembayaran)
                        <a href="{{ route('member.denda.bayar', $denda->id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Bayar Denda
                        </a>
                    @elseif(!$denda->is_verified)
                        <form action="{{ route('member.denda.batalkan-pembayaran', $denda->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran?')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batalkan Pembayaran
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Fine Detail Card - Dark Version -->
        <div class="bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-700">
            <!-- Fine Status Header -->
            <div class="bg-gray-900 px-8 py-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-full {{ $denda->status_pembayaran ? 'bg-green-900' : 'bg-red-900' }}">
                            @if($denda->status_pembayaran)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                Denda #{{ $denda->id }}
                            </h1>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $denda->status_pembayaran ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                    {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                                @if($denda->bukti_pembayaran && !$denda->is_verified)
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-900 text-yellow-200">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($denda->bukti_pembayaran && $denda->is_verified)
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-900 text-blue-200">
                                        Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Jumlah Denda</p>
                        <p class="text-3xl font-bold text-red-400">
                            Rp {{ number_format($denda->jumlah, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Fine Details Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Fine Information -->
                    <div class="space-y-6">
                        <!-- Fine Details Card -->
                        <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                            <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                Informasi Denda
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">ID Denda</p>
                                    <p class="mt-1 text-white font-medium">#{{ $denda->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Jumlah Denda</p>
                                    <p class="mt-1 text-white font-medium text-lg">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Alasan Denda</p>
                                    <p class="mt-1 text-white font-medium">{{ $denda->keterangan }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Denda</p>
                                    <p class="mt-1 text-white font-medium">{{ $denda->created_at->format('d M Y H:i') }}</p>
                                </div>
                                @if($denda->tanggal_bayar)
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Lunas</p>
                                        <p class="mt-1 text-white font-medium">{{ $denda->tanggal_bayar->format('d M Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Status Card -->
                        <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                            <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Status Pembayaran
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status Pembayaran</p>
                                    <p class="mt-1 font-medium {{ $denda->status_pembayaran ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                                    </p>
                                </div>
                                
                                @if($denda->bukti_pembayaran)
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status Verifikasi</p>
                                        <p class="mt-1 font-medium {{ $denda->is_verified ? 'text-green-400' : 'text-yellow-400' }}">
                                            {{ $denda->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi Admin' }}
                                        </p>
                                    </div>
                                    
                                    @if($denda->metode_pembayaran)
                                        <div>
                                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Metode Pembayaran</p>
                                            <p class="mt-1 text-white font-medium">{{ ucfirst($denda->metode_pembayaran) }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($denda->tanggal_upload_bukti)
                                        <div>
                                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Upload Bukti</p>
                                            <p class="mt-1 text-white font-medium">{{ $denda->tanggal_upload_bukti->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($denda->keterangan_pembayaran)
                                        <div>
                                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Keterangan Pembayaran</p>
                                            <p class="mt-1 text-white font-medium">{{ $denda->keterangan_pembayaran }}</p>
                                        </div>
                                    @endif
                                @endif
                                
                                @if($denda->alasan_penolakan)
                                    <div class="p-4 bg-red-900 rounded-lg border border-red-700">
                                        <p class="text-xs font-medium text-red-200 uppercase tracking-wider">Alasan Penolakan</p>
                                        <p class="mt-1 text-red-100 font-medium">{{ $denda->alasan_penolakan }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Related Information -->
                    <div class="space-y-6">
                        <!-- Book Information Card -->
                        <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                            <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Buku yang Dipinjam
                            </h2>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($denda->peminjaman->buku->cover)
                                        <img src="{{ asset('storage/' . $denda->peminjaman->buku->cover) }}" 
                                             alt="Cover {{ $denda->peminjaman->buku->judul }}" 
                                             class="h-24 w-20 object-cover rounded border border-gray-600">
                                    @else
                                        <div class="h-24 w-20 bg-gray-600 rounded flex items-center justify-center text-gray-300 border border-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-white mb-1">{{ $denda->peminjaman->buku->judul }}</h3>
                                    <p class="text-sm text-gray-400">{{ $denda->peminjaman->buku->pengarang->nama }}</p>
                                    <p class="text-sm text-gray-400">{{ $denda->peminjaman->buku->penerbit }}</p>
                                    <p class="text-xs text-gray-500 mt-1">ISBN: {{ $denda->peminjaman->buku->isbn }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Details Card -->
                        <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                            <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Detail Peminjaman
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">ID Peminjaman</p>
                                    <p class="mt-1 text-white font-medium">#{{ $denda->peminjaman->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Pinjam</p>
                                    <p class="mt-1 text-white font-medium">{{ $denda->peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Batas Kembali</p>
                                    <p class="mt-1 text-white font-medium">{{ $denda->peminjaman->tanggal_kembali->format('d M Y') }}</p>
                                </div>
                                @if($denda->peminjaman->tanggal_dikembalikan)
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Dikembalikan</p>
                                        <p class="mt-1 text-white font-medium">{{ $denda->peminjaman->tanggal_dikembalikan->format('d M Y') }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status Peminjaman</p>
                                    <span class="mt-1 inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $denda->peminjaman->status == 'dipinjam' ? 'bg-yellow-900 text-yellow-200' : 
                                           ($denda->peminjaman->status == 'dikembalikan' ? 'bg-green-900 text-green-200' : 
                                           ($denda->peminjaman->status == 'terlambat' ? 'bg-red-900 text-red-200' : 'bg-gray-600 text-gray-200')) }}">
                                        {{ ucfirst($denda->peminjaman->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Proof Card -->
                        @if($denda->bukti_pembayaran)
                            <div class="bg-gray-700 p-6 rounded-xl border border-gray-600">
                                <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Bukti Pembayaran
                                </h2>
                                <div class="space-y-4">
                                    <div>
                                        <img src="{{ asset('storage/' . $denda->bukti_pembayaran) }}" 
                                             alt="Bukti Pembayaran" 
                                             class="w-full h-48 object-cover rounded-lg border border-gray-600 cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="openImageModal(this.src)">
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ asset('storage/' . $denda->bukti_pembayaran) }}" 
                                           target="_blank"
                                           class="text-blue-400 hover:text-blue-300 text-sm transition duration-150">
                                            Lihat Gambar Penuh →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 items-center justify-center z-50 hidden">
    <div class="max-w-4xl max-h-full p-4">
        <div class="relative">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full rounded-lg">
        </div>
    </div>
</div>

<script>
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    document.getElementById('modalImage').src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex'); // Add flex when showing
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex'); // Remove flex when hiding
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection