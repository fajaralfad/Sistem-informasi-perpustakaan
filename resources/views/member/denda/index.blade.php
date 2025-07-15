@extends('layouts.app')

@section('title', 'Denda Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section - Dark Version -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Denda Saya</h1>
            <p class="text-gray-300 mt-1">Kelola dan bayar denda perpustakaan Anda</p>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('member.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-blue-400">
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
                    <span class="ml-1 text-sm font-medium text-white md:ml-2">Riwayat Denda</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Summary Cards - Dark Version -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gray-800 border border-red-900 rounded-lg p-6 text-center shadow-md">
            <div class="text-3xl font-bold text-red-400 mb-2">
                Rp {{ number_format($totalDendaBelumLunas ?? 0, 0, ',', '.') }}
            </div>
            <div class="text-sm text-gray-400">Total Belum Lunas</div>
        </div>
        <div class="bg-gray-800 border border-green-900 rounded-lg p-6 text-center shadow-md">
            <div class="text-3xl font-bold text-green-400 mb-2">
                Rp {{ number_format($totalDendaLunas ?? 0, 0, ',', '.') }}
            </div>
            <div class="text-sm text-gray-400">Total Sudah Lunas</div>
        </div>
    </div>

    <!-- Flash Messages - Dark Version -->
    @if(session('success'))
    <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Info Panel - Dark Version -->
    <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm text-gray-300">
                    Total Denda: <span class="font-semibold text-white">{{ $dendas->total() ?? 0 }}</span>
                </span>
            </div>
            <div class="text-sm text-gray-400">
                Halaman {{ $dendas->currentPage() ?? 1 }} dari {{ $dendas->lastPage() ?? 1 }}
            </div>
        </div>
    </div>

    <!-- Table - Dark Version -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700">
        @if(isset($dendas) && $dendas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Informasi Buku
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Jumlah Denda
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($dendas as $denda)
                    <tr class="hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                            {{ $loop->iteration + (($dendas->currentPage() ?? 1) - 1) * ($dendas->perPage() ?? 15) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-purple-900 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">
                                        {{ Str::limit($denda->peminjaman->buku->judul ?? 'Buku Tidak Tersedia', 50) }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        ISBN: {{ $denda->peminjaman->buku->isbn ?? 'Tidak tersedia' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-red-400">
                                Rp {{ number_format($denda->jumlah ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-300 max-w-xs">
                                {{ $denda->keterangan ?? 'Tidak ada keterangan' }}
                            </div>
                            @if(isset($denda->alasan_penolakan) && $denda->alasan_penolakan)
                            <div class="text-xs text-red-400 mt-2 p-2 bg-red-900 rounded bg-opacity-20">
                                <strong>Alasan Penolakan:</strong> {{ $denda->alasan_penolakan }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($denda->status_pembayaran) && $denda->status_pembayaran)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-900 text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lunas
                                </span>
                            @elseif(isset($denda->bukti_pembayaran) && $denda->bukti_pembayaran && !($denda->is_verified ?? false))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu Verifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-900 text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            <div class="font-medium text-white">
                                {{ ($denda->created_at ?? now())->format('d M Y') }}
                            </div>
                            @if(isset($denda->tanggal_bayar) && $denda->tanggal_bayar)
                            <div class="text-xs text-green-400 mt-1">
                                Dibayar: {{ $denda->tanggal_bayar->format('d M Y') }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('member.denda.show', $denda->id) }}" 
                                   class="bg-blue-900 hover:bg-blue-800 text-blue-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                   title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                                
                                @if(!($denda->status_pembayaran ?? false))
                                <button onclick="showPaymentInfo()" 
                                        class="bg-orange-900 hover:bg-orange-800 text-orange-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                        title="Bayar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Bayar
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination - Dark Version -->
        @if(isset($dendas) && $dendas->hasPages())
        <div class="bg-gray-800 px-4 py-3 border-t border-gray-700 sm:px-6">
            {{ $dendas->links() }}
        </div>
        @endif
        
        @else
        <!-- Empty State - Dark Version -->
        <div class="text-center py-16">
            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-white mb-2">Tidak ada denda</h3>
            <p class="text-gray-400">Selamat! Anda tidak memiliki denda perpustakaan.</p>
        </div>
        @endif
    </div>

    <!-- Payment Info Modal - Dark Version -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full border border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-orange-900 rounded-full mb-4">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-medium text-white text-center mb-4">
                        Cara Pembayaran Denda
                    </h3>
                    
                    <div class="space-y-3 text-sm text-gray-300 mb-6">
                        <p class="font-medium text-white">Silakan datang ke perpustakaan dengan membawa:</p>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Kartu identitas (NIP/NRP)
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Kartu anggota perpustakaan
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Uang tunai sesuai jumlah denda
                            </li>
                        </ul>
                        
                        <div class="mt-4 p-3 bg-blue-900 rounded-lg bg-opacity-30">
                            <p class="text-xs text-blue-300">
                                <strong>Jam Operasional:</strong><br>
                                Senin - Jumat: 08:00 - 16:00<br>
                            </p>
                        </div>
                    </div>
                    
                    <button onclick="closePaymentInfo()" 
                            class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple modal functions
function showPaymentInfo() {
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentInfo() {
    document.getElementById('paymentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentInfo();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePaymentInfo();
    }
});
</script>
@endsection