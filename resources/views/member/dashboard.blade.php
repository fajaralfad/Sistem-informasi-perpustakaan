@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100 mt-2">Nikmati layanan perpustakaan digital kami</p>
                <div class="mt-3 flex space-x-4 text-sm">
                    <span>Bulan ini: {{ $bulan_ini }} peminjaman</span>
                    <span>•</span>
                    <span>Tahun ini: {{ $tahun_ini }} peminjaman</span>
                </div>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Stats - Lengkap sesuai controller -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Booking Pending -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Booking Pending</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="booking_pending">{{ $stats['booking_pending'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Menunggu konfirmasi</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Booking Disetujui -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Booking Disetujui</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="booking_disetujui">{{ $stats['booking_disetujui'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Siap diambil</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="sedang_dipinjam">{{ $stats['sedang_dipinjam'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Di tangan Anda</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Buku Terlambat -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="terlambat">{{ $stats['terlambat'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Segera kembalikan</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Tambahan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Selesai -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Selesai</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="total_selesai">{{ $stats['total_selesai'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sudah dikembalikan</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Peminjaman -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="total_peminjaman">{{ $stats['total_peminjaman'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sepanjang waktu</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Denda Aktif -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Denda Aktif</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="denda_aktif">{{ $stats['denda_aktif'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu dibayar</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Nominal Denda -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Denda</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="total_denda_nominal">Rp {{ number_format($stats['total_denda_nominal'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Belum dibayar</p>
                </div>
                <div class="p-3 bg-pink-100 rounded-full">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Utama -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Menu Utama</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Katalog Buku -->
                    <a href="{{ route('member.katalog') }}" class="block p-4 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-800">Katalog Buku</h3>
                                <p class="text-sm text-gray-600">Jelajahi koleksi buku</p>
                            </div>
                        </div>
                    </a>

                    <!-- Riwayat Peminjaman -->
                    <a href="{{ route('member.riwayat') }}" class="block p-4 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-800">Riwayat Peminjaman</h3>
                                <p class="text-sm text-gray-600">Lihat riwayat peminjaman</p>
                            </div>
                        </div>
                    </a>

                    <!-- Denda -->
                    <a href="{{ route('member.denda.index') }}" class="block p-4 bg-red-50 rounded-lg border border-red-200 hover:bg-red-100 transition-colors">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-800">Denda</h3>
                                <p class="text-sm text-gray-600">Kelola denda aktif</p>
                            </div>
                        </div>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('member.profile') }}" class="block p-4 bg-purple-50 rounded-lg border border-purple-200 hover:bg-purple-100 transition-colors">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-800">Profile</h3>
                                <p class="text-sm text-gray-600">Kelola profile Anda</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Grafik Aktivitas Peminjaman -->
            @if(isset($chart_data) && count($chart_data) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Aktivitas Peminjaman (6 Bulan Terakhir)</h3>
                <div class="h-64">
                    <canvas id="activityChart" width="400" height="200"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Peminjaman Aktif -->
            @if($peminjaman_aktif && $peminjaman_aktif->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📚 Peminjaman Aktif</h3>
                <div class="space-y-3">
                    @foreach($peminjaman_aktif->take(5) as $peminjaman)
                    @php
                        $statusConfig = [
                            'pending' => ['border' => 'border-orange-400', 'bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Pending'],
                            'booking' => ['border' => 'border-purple-400', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Ready'],
                            'dipinjam' => ['border' => 'border-blue-400', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Dipinjam'],
                            'terlambat' => ['border' => 'border-red-400', 'bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Terlambat'],
                            'default' => ['border' => 'border-gray-400', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($peminjaman->status)]
                        ];
                        
                        $isOverdue = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->lt(now()) && $peminjaman->status == 'dipinjam';
                        $currentStatus = $isOverdue ? 'terlambat' : $peminjaman->status;
                        $config = $statusConfig[$currentStatus] ?? $statusConfig['default'];
                    @endphp
                    
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border-l-4 {{ $config['border'] }}">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 text-sm">{{ $peminjaman->buku->judul }}</h4>
                            <p class="text-xs text-gray-600 mb-1">
                                {{ $peminjaman->buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-600">
                                    @if($peminjaman->status == 'pending')
                                        Menunggu konfirmasi
                                    @elseif($peminjaman->status == 'booking')
                                        Siap diambil: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}
                                    @else
                                        Jatuh tempo: {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                                    @endif
                                </p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($peminjaman_aktif->count() > 5)
                <div class="mt-4">
                    <a href="{{ route('member.riwayat') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat semua ({{ $peminjaman_aktif->count() }} items) →
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Notifikasi Denda -->
            @if($denda_aktif && $denda_aktif->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Denda Aktif</h3>
                <div class="space-y-2 mb-4">
                    @foreach($denda_aktif->take(3) as $denda)
                    <div class="p-3 bg-red-50 rounded-lg">
                        <p class="font-medium text-red-800 text-sm">{{ $denda->peminjaman->buku->judul }}</p>
                        <p class="text-red-600 text-sm">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($denda->created_at)->format('d/m/Y') }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="bg-red-100 p-3 rounded-lg mb-4">
                    <p class="text-sm font-semibold text-red-800">
                        Total: Rp {{ number_format($stats['total_denda_nominal'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('member.denda.index') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Kelola Denda →
                    </a>
                </div>
            </div>
            @endif

            <!-- Riwayat Peminjaman Terakhir -->
            @if($riwayat_terakhir && $riwayat_terakhir->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🕒 Riwayat Terakhir</h3>
                <div class="space-y-3">
                    @foreach($riwayat_terakhir->take(3) as $riwayat)
                    @php
                        $riwayatStatusConfig = [
                            'dikembalikan' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                            'terlambat' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                            'dipinjam' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                            'booking' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                            'pending' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                            'default' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800']
                        ];
                        $riwayatConfig = $riwayatStatusConfig[$riwayat->status] ?? $riwayatStatusConfig['default'];
                    @endphp
                    
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 text-sm">{{ $riwayat->buku->judul }}</h4>
                            <p class="text-xs text-gray-600">{{ $riwayat->buku->pengarang->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $riwayatConfig['bg'] }} {{ $riwayatConfig['text'] }}">
                            {{ ucfirst($riwayat->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @if($riwayat_terakhir->count() > 3)
                <div class="mt-4">
                    <a href="{{ route('member.riwayat') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat semua riwayat →
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Tips -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-md p-6 border border-yellow-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">💡 Tips</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="text-yellow-500 mr-2">•</span>
                        Kembalikan buku tepat waktu untuk menghindari denda
                    </li>
                    <li class="flex items-start">
                        <span class="text-yellow-500 mr-2">•</span>
                        Booking disetujui harus diambil dalam 3 hari
                    </li>
                    <li class="flex items-start">
                        <span class="text-yellow-500 mr-2">•</span>
                        Gunakan fitur pencarian untuk menemukan buku favorit
                    </li>
                    <li class="flex items-start">
                        <span class="text-yellow-500 mr-2">•</span>
                        Perbarui profile Anda secara berkala
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Buku -->
    @if(isset($buku_rekomendasi) && $buku_rekomendasi->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📚 Rekomendasi Buku Untukmu</h3>
            <p class="text-gray-600 text-sm mb-4">Buku populer yang belum pernah Anda pinjam</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($buku_rekomendasi->take(6) as $buku)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow hover:border-blue-300">
                    <h4 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2">{{ $buku->judul }}</h4>
                    <p class="text-xs text-gray-600 mb-2">{{ $buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}</p>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-blue-600">{{ $buku->peminjamans_count }} kali dipinjam</p>
                        <p class="text-xs text-green-600">Stok: {{ $buku->stok }}</p>
                    </div>
                    @if($buku->kategori)
                    <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full mt-2">
                        {{ $buku->kategori->nama }}
                    </span>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('member.katalog') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajahi Semua Buku
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart untuk aktivitas peminjaman
    @if(isset($chart_data) && count($chart_data) > 0)
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chart_labels),
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: @json($chart_data),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif

    // Auto refresh setiap 5 menit untuk update status
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            // Refresh hanya statistik quick tanpa reload seluruh halaman
            fetch('{{ route("member.dashboard.quick-stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update statistik booking pending
                    document.querySelector('[data-stat="booking_pending"]').textContent = data.booking_pending;
                    // Update statistik booking disetujui
                    document.querySelector('[data-stat="booking_disetujui"]').textContent = data.booking_disetujui;
                    // Update statistik sedang dipinjam
                    document.querySelector('[data-stat="sedang_dipinjam"]').textContent = data.sedang_dipinjam;
                    // Update statistik terlambat
                    document.querySelector('[data-stat="terlambat"]').textContent = data.terlambat;
                    // Update statistik total selesai
                    document.querySelector('[data-stat="total_selesai"]').textContent = data.total_selesai;
                    // Update statistik total peminjaman
                    document.querySelector('[data-stat="total_peminjaman"]').textContent = data.total_peminjaman;
                    // Update statistik denda aktif
                    document.querySelector('[data-stat="denda_aktif"]').textContent = data.denda_aktif;
                    // Update total nominal denda
                    document.querySelector('[data-stat="total_denda_nominal"]').textContent = 
                        'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_denda_nominal);
                })
                .catch(error => console.log('Error updating stats:', error));
        }
    }, 300000); // 5 menit

    // Add data attributes untuk auto refresh
    document.addEventListener('DOMContentLoaded', function() {
        // Add data attributes untuk stats yang akan di-refresh
        const stats = document.querySelectorAll('.text-2xl.font-bold.text-gray-900');
        const statKeys = ['booking_pending', 'booking_disetujui', 'sedang_dipinjam', 'terlambat', 
                         'total_selesai', 'total_peminjaman', 'denda_aktif', 'total_denda_nominal'];
        
        stats.forEach((stat, index) => {
            if (statKeys[index]) {
                stat.setAttribute('data-stat', statKeys[index]);
            }
        });
    });
</script>
@endpush
@endsection