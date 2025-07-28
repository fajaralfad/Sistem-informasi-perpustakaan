@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-blue-400">
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
                    <a href="{{ route('admin.denda.index') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-blue-400 md:ml-2">Denda</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-white md:ml-2">Detail Denda</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Detail Denda</h1>
            <p class="text-gray-300 mt-1">Informasi lengkap tentang denda</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert-success bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Main Card -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700">
        <div class="px-6 py-4 border-b border-gray-700 bg-gray-700">
            <h3 class="text-lg font-medium text-white">
                Informasi Denda
                <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $denda->status_pembayaran ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                    {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                </span>
            </h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Denda Information -->
                <div>
                    <h4 class="text-md font-semibold text-white mb-3 border-b border-gray-700 pb-2">Detail Denda</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">ID Denda:</span>
                            <span class="text-white font-medium">{{ $denda->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jumlah Denda:</span>
                            <span class="text-white font-medium">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Dibuat:</span>
                            <span class="text-white font-medium">{{ $denda->created_at ? $denda->created_at->format('d M Y, H:i') : 'Tidak tersedia' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status Pembayaran:</span>
                            <span class="font-medium {{ $denda->status_pembayaran ? 'text-green-400' : 'text-red-400' }}">
                                {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </div>
                        @if($denda->status_pembayaran && $denda->tanggal_bayar)
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Pembayaran:</span>
                            <span class="text-white font-medium">{{ $denda->tanggal_bayar->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Metode Pembayaran:</span>
                            <span class="text-white font-medium">{{ $denda->metode_pembayaran ?? 'Cash' }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-400">Keterangan:</span>
                            <span class="text-white font-medium text-right">{{ $denda->keterangan }}</span>
                        </div>
                    </div>
                </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Peminjaman Information -->
                <div>
                    <h4 class="text-md font-semibold text-white mb-3 border-b border-gray-700 pb-2">Informasi Peminjaman</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">ID Peminjaman:</span>
                            <span class="text-white font-medium">{{ $denda->peminjaman->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status Peminjaman:</span>
                            <span class="font-medium {{ $denda->peminjaman->status === 'dikembalikan' ? 'text-green-400' : ($denda->peminjaman->status === 'terlambat' ? 'text-red-400' : 'text-yellow-400') }}">
                                {{ ucfirst($denda->peminjaman->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Pinjam:</span>
                            <span class="text-white font-medium">{{ $denda->peminjaman->tanggal_pinjam ? $denda->peminjaman->tanggal_pinjam->format('d M Y') : 'Tidak tersedia' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Kembali:</span>
                            <span class="text-white font-medium">{{ $denda->peminjaman->tanggal_kembali ? $denda->peminjaman->tanggal_kembali->format('d M Y') : 'Tidak tersedia' }}</span>
                        </div>
                        @if($denda->peminjaman->tanggal_pengembalian)
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Pengembalian:</span>
                            <span class="text-white font-medium">{{ $denda->peminjaman->tanggal_pengembalian->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div>
                    <h4 class="text-md font-semibold text-white mb-3 border-b border-gray-700 pb-2">Informasi Anggota</h4>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-blue-900 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-white truncate">{{ $denda->peminjaman->user->name }}</div>
                            <div class="text-sm text-gray-400">{{ $denda->peminjaman->user->email }}</div>
                            <div class="text-sm text-gray-400 mt-1">
                                {{ $denda->peminjaman->user->phone ?? 'No. HP tidak tersedia' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book Information -->
                <div>
                    <h4 class="text-md font-semibold text-white mb-3 border-b border-gray-700 pb-2">Informasi Buku</h4>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if($denda->peminjaman->buku->cover)
                            <img class="h-16 w-12 object-cover rounded" src="{{ asset('storage/' . $denda->peminjaman->buku->cover) }}" alt="{{ $denda->peminjaman->buku->judul }}">
                            @else
                            <div class="h-16 w-12 bg-gray-700 rounded flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-white">{{ $denda->peminjaman->buku->judul }}</div>
                            <div class="text-sm text-gray-400">ISBN: {{ $denda->peminjaman->buku->isbn }}</div>
                            <div class="text-sm text-gray-400">Penerbit: {{ $denda->peminjaman->buku->penerbit }}</div>
                            <div class="text-sm text-gray-400">Tahun: {{ $denda->peminjaman->buku->tahun_terbit }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Actions -->
                @if(!$denda->status_pembayaran)
                <div class="pt-4 border-t border-gray-700">
                    <h4 class="text-md font-semibold text-white mb-3">Aksi Pembayaran</h4>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form action="{{ route('admin.denda.bayar', $denda->id) }}" method="POST" class="w-full" onsubmit="return confirmPayment('{{ $denda->peminjaman->user->name ?? 'Tidak Diketahui' }}', '{{ number_format($denda->jumlah, 0, ',', '.') }}')">
                            @csrf
                            <button type="submit" class="w-full bg-green-900 hover:bg-green-800 text-green-200 px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center justify-center transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Konfirmasi Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

        <!-- Bukti Pembayaran Section -->
        @if($denda->bukti_pembayaran)
        <div class="px-6 py-4 border-t border-gray-700 bg-gray-700">
            <h3 class="text-lg font-medium text-white">Bukti Pembayaran</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col items-center">
                <img src="{{ asset('storage/' . $denda->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg border border-gray-600 max-h-96">
                <div class="mt-4 text-sm text-gray-400">
                    Uploaded on: {{ $denda->updated_at ? $denda->updated_at->format('d M Y, H:i') : 'Tidak tersedia' }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
// Confirm payment function
function confirmPayment(namaAnggota, jumlahDenda) {
    return confirm(`Konfirmasi pembayaran denda untuk "${namaAnggota}" sebesar Rp ${jumlahDenda}?\n\nTindakan ini akan mengubah status denda menjadi lunas.`);
}

// Auto hide flash messages
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-error, .alert-success');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endsection