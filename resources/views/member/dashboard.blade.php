@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header Dashboard -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Dashboard Anggota</h1>
                <p class="text-gray-300 mt-1">Selamat datang kembali, {{ auth()->user()->name }}</p>
            </div>
            <div class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium border border-gray-700">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>

        <!-- Tips Peminjaman -->
        <div class="bg-indigo-600 rounded-lg p-6 mb-8 border border-indigo-500">
            <div class="flex items-start gap-3">
                <div class="bg-indigo-500 rounded-full p-2 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-white mb-3">Tips Peminjaman Buku</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-white">
                        <div class="flex items-start">
                            <span class="text-indigo-200 mr-2 mt-1">•</span>
                            <span><strong>Maksimal 5 peminjaman aktif</strong> dan <strong>3 booking aktif</strong> per anggota</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-indigo-200 mr-2 mt-1">•</span>
                            <span>Booking harus diambil sesuai jadwal yang ditentukan</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-indigo-200 mr-2 mt-1">•</span>
                            <span>Kembalikan buku tepat waktu untuk menghindari denda <strong>Rp 5.000/hari</strong></span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-indigo-200 mr-2 mt-1">•</span>
                            <span>Lunasi denda terlebih dahulu sebelum melakukan peminjaman baru</span>
                        </div>
                    </div>
                </div>
            </div>
</div>


        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
            <!-- Booking Pending -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-orange-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Booking Pending</p>
                        <p class="text-xl font-bold text-white" data-stat="booking_pending">{{ $stats['booking_pending'] }}</p>
                        <p class="text-xs text-gray-400">Menunggu konfirmasi</p>
                    </div>
                </div>
            </div>

            <!-- Booking Disetujui -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-purple-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Booking Disetujui</p>
                        <p class="text-xl font-bold text-white" data-stat="booking_disetujui">{{ $stats['booking_disetujui'] }}</p>
                        <p class="text-xs text-gray-400">Siap diambil</p>
                    </div>
                </div>
            </div>

            <!-- Sedang Dipinjam -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Sedang Dipinjam</p>
                        <p class="text-xl font-bold text-white" data-stat="sedang_dipinjam">{{ $stats['sedang_dipinjam'] }}</p>
                        <p class="text-xs text-gray-400">Di tangan Anda</p>
                    </div>
                </div>
            </div>

            <!-- Buku Terlambat -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-red-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Terlambat</p>
                        <p class="text-xl font-bold text-white" data-stat="terlambat">{{ $stats['terlambat'] }}</p>
                        <p class="text-xs text-gray-400">Segera kembalikan</p>
                    </div>
                </div>
            </div>

            <!-- Total Selesai -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-green-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Total Selesai</p>
                        <p class="text-xl font-bold text-white" data-stat="total_selesai">{{ $stats['total_selesai'] }}</p>
                        <p class="text-xs text-gray-400">Sudah dikembalikan</p>
                    </div>
                </div>
            </div>

            <!-- Total Peminjaman -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-indigo-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Total Aktivitas</p>
                        <p class="text-xl font-bold text-white" data-stat="total_peminjaman">{{ $stats['total_peminjaman'] }}</p>
                        <p class="text-xs text-gray-400">Sepanjang waktu</p>
                    </div>
                </div>
            </div>

            <!-- Denda Aktif -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-yellow-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Denda Aktif</p>
                        <p class="text-xl font-bold text-white" data-stat="denda_aktif">{{ $stats['denda_aktif'] }}</p>
                        <p class="text-xs text-gray-400">Perlu dibayar</p>
                    </div>
                </div>
            </div>

            <!-- Total Nominal Denda -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-pink-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Total Denda</p>
                        <p class="text-xl font-bold text-white" data-stat="total_denda_nominal">Rp {{ number_format($stats['total_denda_nominal'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">Belum dibayar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Grafik Aktivitas Peminjaman -->
                @if(isset($chart_data) && count($chart_data) > 0)
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Aktivitas Peminjaman (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="activityChart" width="400" height="200"></canvas>
                    </div>
                </div>
                @endif

                <!-- Rekomendasi Buku -->
                @if(isset($buku_rekomendasi) && $buku_rekomendasi->count() > 0)
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Rekomendasi Buku Untukmu</h3>
                    <p class="text-gray-400 text-sm mb-4">Buku populer yang belum pernah Anda pinjam</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($buku_rekomendasi->take(6) as $buku)
                        <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 transition-colors border border-gray-600">
                            <h4 class="font-semibold text-white text-sm mb-2 line-clamp-2">{{ $buku->judul }}</h4>
                            <p class="text-xs text-gray-400 mb-2">{{ $buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-blue-400">{{ $buku->peminjamans_count }} kali dipinjam</p>
                                <p class="text-xs text-green-400">Stok: {{ $buku->stok }}</p>
                            </div>
                            @if($buku->kategori)
                            <span class="inline-block px-2 py-1 text-xs bg-gray-600 text-gray-200 rounded-full mt-2">
                                {{ $buku->kategori->nama }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Peminjaman Aktif -->
                @if($peminjaman_aktif && $peminjaman_aktif->count() > 0)
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-white">Peminjaman Aktif</h3>
                        <a href="{{ route('member.riwayat') }}" class="text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($peminjaman_aktif->take(5) as $peminjaman)
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-orange-600', 'text' => 'text-white', 'label' => 'Pending'],
                                'booking' => ['bg' => 'bg-purple-600', 'text' => 'text-white', 'label' => 'Ready'],
                                'dipinjam' => ['bg' => 'bg-blue-600', 'text' => 'text-white', 'label' => 'Dipinjam'],
                                'terlambat' => ['bg' => 'bg-red-600', 'text' => 'text-white', 'label' => 'Terlambat'],
                                'default' => ['bg' => 'bg-gray-600', 'text' => 'text-white', 'label' => ucfirst($peminjaman->status)]
                            ];
                            
                            $isOverdue = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->lt(now()) && $peminjaman->status == 'dipinjam';
                            $currentStatus = $isOverdue ? 'terlambat' : $peminjaman->status;
                            $config = $statusConfig[$currentStatus] ?? $statusConfig['default'];
                        @endphp
                        
                        <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-white text-sm">{{ $peminjaman->buku->judul }}</h4>
                                    <p class="text-xs text-gray-400 mb-1">
                                        {{ $peminjaman->buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        @if($peminjaman->status == 'pending')
                                            Menunggu konfirmasi
                                        @elseif($peminjaman->status == 'booking')
                                            Siap diambil: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}
                                        @else
                                            Jatuh tempo: {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-block px-2 py-1 text-xs rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($peminjaman_aktif->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('member.riwayat') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                            Lihat semua ({{ $peminjaman_aktif->count() }} items) →
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Notifikasi Denda -->
                @if($denda_aktif && $denda_aktif->count() > 0)
                <div class="bg-gray-800 rounded-lg p-6 border border-red-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-white">Denda Aktif</h3>
                        <a href="{{ route('member.denda.index') }}" class="text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="space-y-2 mb-4">
                        @foreach($denda_aktif->take(3) as $denda)
                        <div class="bg-gray-700 rounded-lg p-3">
                            <p class="font-medium text-white text-sm">{{ $denda->peminjaman->buku->judul }}</p>
                            <p class="text-red-400 text-sm">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($denda->created_at)->format('d/m/Y') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-red-900/30 p-3 rounded-lg mb-4">
                        <p class="text-sm font-semibold text-white">
                            Total: Rp {{ number_format($stats['total_denda_nominal'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('member.denda.index') }}" class="text-red-400 hover:text-red-300 text-sm font-medium">
                            Kelola Denda →
                        </a>
                    </div>
                </div>
                @endif

                <!-- Riwayat Peminjaman Terakhir -->
                @if($riwayat_terakhir && $riwayat_terakhir->count() > 0)
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-white">Riwayat Terakhir</h3>
                        <a href="{{ route('member.riwayat') }}" class="text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($riwayat_terakhir->take(3) as $riwayat)
                        @php
                            $riwayatStatusConfig = [
                                'dikembalikan' => ['bg' => 'bg-green-600', 'text' => 'text-white'],
                                'terlambat' => ['bg' => 'bg-red-600', 'text' => 'text-white'],
                                'dipinjam' => ['bg' => 'bg-blue-600', 'text' => 'text-white'],
                                'booking' => ['bg' => 'bg-purple-600', 'text' => 'text-white'],
                                'pending' => ['bg' => 'bg-orange-600', 'text' => 'text-white'],
                                'default' => ['bg' => 'bg-gray-600', 'text' => 'text-white']
                            ];
                            $riwayatConfig = $riwayatStatusConfig[$riwayat->status] ?? $riwayatStatusConfig['default'];
                        @endphp
                        
                        <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-white text-sm">{{ $riwayat->buku->judul }}</h4>
                                    <p class="text-xs text-gray-400">{{ $riwayat->buku->pengarang->nama ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $riwayatConfig['bg'] }} {{ $riwayatConfig['text'] }}">
                                    {{ ucfirst($riwayat->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($riwayat_terakhir->count() > 3)
                    <div class="mt-4 text-center">
                        <a href="{{ route('member.riwayat') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                            Lihat semua riwayat →
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                tension: 0.4,
                pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                pointBorderColor: 'rgba(59, 130, 246, 1)',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' peminjaman';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        stepSize: 1,
                        color: 'rgba(255, 255, 255, 0.8)',
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
    @endif

    // Auto refresh setiap 5 menit untuk update status
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            fetch('{{ route("member.dashboard.quick-stats") }}')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('[data-stat="booking_pending"]').textContent = data.booking_pending;
                    document.querySelector('[data-stat="booking_ditolak"]').textContent = data.booking_ditolak;
                    document.querySelector('[data-stat="booking_disetujui"]').textContent = data.booking_disetujui;
                    document.querySelector('[data-stat="sedang_dipinjam"]').textContent = data.sedang_dipinjam;
                    document.querySelector('[data-stat="terlambat"]').textContent = data.terlambat;
                    document.querySelector('[data-stat="total_selesai"]').textContent = data.total_selesai;
                    document.querySelector('[data-stat="total_peminjaman"]').textContent = data.total_peminjaman;
                    document.querySelector('[data-stat="denda_aktif"]').textContent = data.denda_aktif;
                    document.querySelector('[data-stat="total_denda_nominal"]').textContent = 
                        'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_denda_nominal);
                })
                .catch(error => console.log('Error updating stats:', error));
        }
    }, 300000); // 5 menit
});
</script>
@endpush
@endsection