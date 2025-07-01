@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Denda</h1>
            <p class="text-gray-600">Kelola denda perpustakaan dan laporan pembayaran</p>
        </div>
        <div class="flex space-x-3">
            <!-- Tombol Cek Denda Terlambat -->
            <button onclick="cekDendaTerlambat()" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Cek Terlambat
            </button>
            
            <!-- Tombol Laporan -->
            <a href="{{ route('admin.denda.laporan') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Buat Laporan
            </a>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600">
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Denda</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="alert-warning bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        {{ session('warning') }}
    </div>
    @endif

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Status Filter -->
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <a href="{{ route('admin.denda.index', ['status' => 'belum']) }}" 
                       class="px-4 py-2 text-sm font-medium {{ request('status') == 'belum' ? 'bg-red-100 text-red-800 border-red-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }} transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Belum Lunas
                    </a>
                    <a href="{{ route('admin.denda.index', ['status' => 'lunas']) }}" 
                       class="px-4 py-2 text-sm font-medium {{ request('status') == 'lunas' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }} transition-colors duration-200 border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lunas
                    </a>
                    <a href="{{ route('admin.denda.index', ['status' => 'pending']) }}" 
                       class="px-4 py-2 text-sm font-medium {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }} transition-colors duration-200 border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pending Verifikasi
                    </a>
                    <a href="{{ route('admin.denda.index') }}" 
                       class="px-4 py-2 text-sm font-medium {{ !request('status') ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }} transition-colors duration-200 border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Semua
                    </a>
                </div>
            </div>

            <!-- Search and Total Count -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <div class="flex-1 sm:min-w-80">
                    <label for="search" class="sr-only">Cari denda</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                               placeholder="Cari user, buku, atau jumlah denda..." onkeyup="searchTable()">
                    </div>
                </div>
                <div class="text-sm text-gray-500 whitespace-nowrap">
                    Total: <span class="font-semibold text-gray-700">{{ $dendas->total() }}</span> denda
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        @if($dendas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="dendaTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                No
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                User
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Buku
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                Jumlah
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Bayar
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dendas as $denda)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                            {{ $loop->iteration + ($dendas->currentPage() - 1) * $dendas->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $denda->peminjaman->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $denda->peminjaman->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 max-w-xs">{{ Str::limit($denda->peminjaman->buku->judul, 50) }}</div>
                            <div class="text-sm text-gray-500">{{ $denda->peminjaman->buku->isbn }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">
                                Rp {{ number_format($denda->jumlah, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 max-w-xs">
                                {{ $denda->keterangan }}
                            </div>
                            @if($denda->alasan_penolakan)
                            <div class="text-xs text-red-600 mt-1">
                                <strong>Ditolak:</strong> {{ $denda->alasan_penolakan }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($denda->bukti_pembayaran && !$denda->status_pembayaran && !$denda->is_verified)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending Verifikasi
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $denda->status_pembayaran ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            @endif
                            
                            @if($denda->metode_pembayaran)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ ucfirst($denda->metode_pembayaran) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $denda->tanggal_bayar ? $denda->tanggal_bayar->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-2 flex-wrap gap-1">
                                <!-- Detail Button -->
                                <a href="{{ route('admin.denda.show', $denda->id) }}" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                   title="Detail Denda">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                                
                                <!-- Jika ada bukti pembayaran dan belum diverifikasi -->
                                @if($denda->bukti_pembayaran && !$denda->status_pembayaran && !$denda->is_verified)
                                    <form action="{{ route('admin.denda.verifikasi', $denda->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="terima">
                                        <button type="submit" 
                                                class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                                title="Terima Pembayaran"
                                                onclick="return confirm('Terima pembayaran denda ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Terima
                                        </button>
                                    </form>
                                    
                                    <button onclick="tolakPembayaran({{ $denda->id }})" 
                                            class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                            title="Tolak Pembayaran">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>
                                @endif
                                
                                <!-- Jika belum dibayar dan tidak ada bukti pembayaran -->
                                @if(!$denda->status_pembayaran && !$denda->bukti_pembayaran)
                                <form action="{{ route('admin.denda.bayar', $denda->id) }}" method="POST" class="inline" onsubmit="return confirmPayment('{{ $denda->peminjaman->user->name }}', '{{ number_format($denda->jumlah, 0, ',', '.') }}')">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                            title="Bayar Denda">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Bayar
                                    </button>
                                </form>
                                @endif
                                <!-- Tombol Hapus (hanya untuk denda yang belum dibayar) -->
                                @if(!$denda->status_pembayaran)
                                <form action="{{ route('admin.denda.destroy', $denda->id) }}" method="POST" class="inline" onsubmit="return confirmDelete('{{ $denda->peminjaman->user->name }}', '{{ number_format($denda->jumlah, 0, ',', '.') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                            title="Hapus Denda">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data denda</h3>
            <p class="text-gray-500 mb-6">
                @if(request('status'))
                    Belum ada denda dengan status "{{ ucfirst(str_replace('_', ' ', request('status'))) }}"
                @else
                    Belum ada denda yang terdaftar dalam sistem
                @endif
            </p>
            <div class="flex justify-center space-x-3">
                <button onclick="cekDendaTerlambat()" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Cek Peminjaman Terlambat
                </button>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($dendas->hasPages()))
    <div class="mt-6 flex justify-center">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            {{ $dendas->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Modal untuk Menolak Pembayaran -->
<div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tolak Pembayaran</h3>
                <button onclick="closeTolakModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="tolakForm" method="POST">
                @csrf
                <input type="hidden" name="action" value="tolak">
                <div class="mb-4">
                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                              placeholder="Masukkan alasan penolakan pembayaran..." required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTolakModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                <span class="text-gray-700 font-medium">Memproses...</span>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk pencarian tabel
function searchTable() {
    const input = document.getElementById('search');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('dendaTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
}

// Fungsi untuk konfirmasi pembayaran
function confirmPayment(nama, jumlah) {
    return confirm(`Konfirmasi pembayaran denda untuk ${nama} sebesar Rp ${jumlah}?`);
}

// Fungsi untuk konfirmasi hapus
function confirmDelete(nama, jumlah) {
    return confirm(`Yakin ingin menghapus denda untuk ${nama} sebesar Rp ${jumlah}?\n\nTindakan ini tidak dapat dibatalkan!`);
}

// Fungsi untuk menolak pembayaran
function tolakPembayaran(dendaId) {
    document.getElementById('tolakForm').action = `/admin/denda/${dendaId}/verifikasi`;
    document.getElementById('tolakModal').classList.remove('hidden');
}

// Fungsi untuk menutup modal tolak
function closeTolakModal() {
    document.getElementById('tolakModal').classList.add('hidden');
    document.getElementById('alasan_penolakan').value = '';
}

// Fungsi untuk cek denda terlambat
function cekDendaTerlambat() {
    if (!confirm('Proses ini akan mengecek semua peminjaman yang terlambat dan membuat denda otomatis. Lanjutkan?')) {
        return;
    }

    // Tampilkan loading
    document.getElementById('loadingOverlay').classList.remove('hidden');

    fetch('/admin/denda/cek-terlambat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Sembunyikan loading
        document.getElementById('loadingOverlay').classList.add('hidden');
        
        if (data.success) {
            alert(data.message);
            // Reload halaman untuk menampilkan denda baru
            window.location.reload();
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        // Sembunyikan loading
        document.getElementById('loadingOverlay').classList.add('hidden');
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses permintaan');
    });
}

// Event listener untuk menutup modal ketika klik di luar
document.getElementById('tolakModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTolakModal();
    }
});

// Event listener untuk tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTolakModal();
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-success, .alert-error, .alert-warning');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        }, 5000);
    });
});
</script>

@endsection