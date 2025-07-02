@extends('layouts.app')

@section('content')
<!-- Main Container with Gradient Background and Pattern -->
<div class="dashboard-container min-h-screen">
    <div class="container mx-auto px-4 py-8 pb-16 relative z-10">
        <!-- Header Dashboard -->
        <div class="flex justify-between items-center mb-8">
            <div class="animate-fade-in">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Dashboard Perpustakaan</h1>
                <p class="text-white text-opacity-90 text-lg">Ringkasan aktivitas dan statistik perpustakaan</p>
            </div>
            <div class="card-glass text-white text-sm font-medium px-4 py-2 rounded-lg animate-slide-in-right">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>

        <!-- Menu Navigasi Cepat -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8 animate-slide-in-left">
            <!-- Buku -->
            <a href="{{ route('admin.buku.index') }}" class="nav-card-glass group">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="nav-icon-wrapper mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Kelola Buku</span>
                </div>
            </a>

            <!-- Peminjaman -->
            <a href="{{ route('admin.peminjaman.index') }}" class="nav-card-glass group">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="nav-icon-wrapper mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Peminjaman</span>
                </div>
            </a>

            <!-- User/Anggota -->
            <a href="{{ route('admin.anggota.index') }}" class="nav-card-glass group">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="nav-icon-wrapper mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">User</span>
                </div>
            </a>

            <!-- Denda -->
            <a href="{{ route('admin.denda.index') }}" class="nav-card-glass group">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="nav-icon-wrapper mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Denda</span>
                </div>
            </a>

            <!-- Konfirmasi Peminjaman -->
            <a href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}" class="nav-card-glass group">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="nav-icon-wrapper mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Konfirmasi ({{ $peminjamanPending }})</span>
                </div>
            </a>

            <!-- Aktivitas -->
            <a href="{{ route('admin.aktivitas.index') }}" class="nav-card-glass group">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="nav-icon-wrapper mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Aktivitas</span>
                </div>
            </a>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-in-right">
            <!-- Total Buku -->
            <div class="stat-card-glass p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="stat-icon-wrapper mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-white text-opacity-90">Total Buku</h3>
                                <p class="text-3xl font-bold text-white">{{ $totalBuku }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-white text-opacity-70">{{ $bukuBaruBulanIni }} baru bulan ini</p>
                    </div>
                </div>
            </div>

            <!-- Total User/Anggota -->
            <div class="stat-card-glass p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="stat-icon-wrapper mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-white text-opacity-90">Total Anggota</h3>
                                <p class="text-3xl font-bold text-white">{{ $totalUser }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-white text-opacity-70">{{ $userBaruBulanIni }} baru bulan ini</p>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Aktif -->
            <div class="stat-card-glass p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="stat-icon-wrapper mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-white text-opacity-90">Peminjaman Aktif</h3>
                                <p class="text-3xl font-bold text-white">{{ $peminjamanAktif }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-white text-opacity-70">{{ $peminjamanHariIni }} hari ini</p>
                    </div>
                </div>
            </div>

            <!-- Denda Belum Lunas -->
            <div class="stat-card-glass p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="stat-icon-wrapper mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-white text-opacity-90">Denda Belum Lunas</h3>
                                <p class="text-3xl font-bold text-white">{{ $dendaBelumLunas }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-white text-opacity-70">Total Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik dan Aktivitas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate-fade-in">
            <!-- Grafik Peminjaman -->
            <div class="content-card-glass p-6 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Statistik Peminjaman 30 Hari Terakhir</h2>
                    <select class="dashboard-select bg-white bg-opacity-10 text-white border border-white border-opacity-20 rounded-lg px-3 py-2 text-sm">
                        <option value="30" class="text-gray-900">30 Hari</option>
                        <option value="7" class="text-gray-900">7 Hari</option>
                        <option value="365" class="text-gray-900">1 Tahun</option>
                    </select>
                </div>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="peminjamanChart"></canvas>
                </div>
            </div>

            <!-- Aktivitas Terkini -->
            <div class="content-card-glass p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Aktivitas Terkini</h2>
                    <a href="{{ route('admin.aktivitas.index') }}" class="text-white text-opacity-70 hover:text-opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="activity-list space-y-4 max-h-80 overflow-y-auto pr-2">
                    @forelse($aktivitasTerkini as $aktivitas)
                    <div class="activity-item flex items-start space-x-3 p-3 rounded-lg {{ $aktivitas['bgColor'] }} hover:bg-opacity-30 transition-all duration-300">
                        <div class="activity-icon flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $aktivitas['bgColor'] }}">
                                {!! $aktivitas['icon'] !!}
                            </div>
                        </div>
                        <div class="activity-content flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-medium text-white">{{ $aktivitas['judul'] }}</p>
                                <span class="text-xs text-white text-opacity-70">{{ $aktivitas['waktu'] }}</span>
                            </div>
                            <p class="text-sm text-white text-opacity-80">{{ $aktivitas['deskripsi'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white text-opacity-60 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-white text-opacity-70 text-sm">Belum ada aktivitas terkini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Buku Populer dan Peminjam Aktif -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-slide-in-left">
            <!-- Buku Populer -->
            <div class="content-card-glass p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Buku Paling Populer</h2>
                    <a href="{{ route('admin.buku.index') }}" class="text-white text-opacity-70 hover:text-opacity-100 text-sm flex items-center">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($bukuPopuler as $buku)
                    <div class="ranking-item flex items-center p-3 rounded-lg bg-white bg-opacity-5 hover:bg-opacity-10 transition-all duration-300">
                        <div class="ranking-number w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold mr-4">
                            {{ $loop->iteration }}
                        </div>
                        <div class="ranking-content flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $buku->judul }}</p>
                            <p class="text-xs text-white text-opacity-80">{{ $buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}</p>
                        </div>
                        <div class="ml-4">
                            <span class="ranking-badge text-xs px-2 py-1 rounded-full bg-blue-500 bg-opacity-20 text-blue-200">
                                {{ $buku->peminjamans_count }}x dipinjam
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white text-opacity-60 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-white text-opacity-70 text-sm">Belum ada data peminjaman buku</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- User/Peminjam Aktif -->
            <div class="content-card-glass p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Anggota Paling Aktif</h2>
                    <a href="{{ route('admin.anggota.index') }}" class="text-white text-opacity-70 hover:text-opacity-100 text-sm flex items-center">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($userAktif as $user)
                    <div class="ranking-item flex items-center p-3 rounded-lg bg-white bg-opacity-5 hover:bg-opacity-10 transition-all duration-300">
                        <div class="ranking-number w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold mr-4">
                            {{ $loop->iteration }}
                        </div>
                        <div class="ranking-content flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                            <p class="text-xs text-white text-opacity-80">{{ $user->email }}</p>
                        </div>
                        <div class="ml-4">
                            <span class="ranking-badge text-xs px-2 py-1 rounded-full bg-green-500 bg-opacity-20 text-green-200">
                                {{ $user->peminjamans_count }}x meminjam
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white text-opacity-60 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-white text-opacity-70 text-sm">Belum ada data peminjaman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Peminjaman dengan tema glassmorphism
    const ctx = document.getElementById('peminjamanChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                pointBorderColor: 'rgba(99, 102, 241, 1)',
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                pointHoverBorderColor: 'rgba(99, 102, 241, 1)',
                pointHoverBorderWidth: 2
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
});
</script>
@endsection