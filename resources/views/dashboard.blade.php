@extends('layouts.app')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header Dashboard -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Dashboard Perpustakaan</h1>
                <p class="text-gray-300 mt-1">Ringkasan aktivitas dan statistik perpustakaan</p>
            </div>
            <div class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium border border-gray-700">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>

        <!-- Statistics Cards - Made smaller like navigation cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
            <!-- Total Buku -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Total Buku</p>
                        <p class="text-xl font-bold text-white">{{ $totalBuku }}</p>
                        <p class="text-xs text-gray-400">{{ $bukuBaruBulanIni }} baru</p>
                    </div>
                </div>
            </div>

            <!-- Total User/Anggota -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-green-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Total Anggota</p>
                        <p class="text-xl font-bold text-white">{{ $totalUser }}</p>
                        <p class="text-xs text-gray-400">{{ $userBaruBulanIni }} baru</p>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Aktif -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-yellow-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Peminjaman Aktif</p>
                        <p class="text-xl font-bold text-white">{{ $peminjamanAktif }}</p>
                        <p class="text-xs text-gray-400">{{ $peminjamanHariIni }} hari ini</p>
                    </div>
                </div>
            </div>

            <!-- Denda Belum Lunas -->
            <div class="bg-gray-800 rounded-lg p-4 text-center border border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-red-600 rounded-full p-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-300">Denda Belum Lunas</p>
                        <p class="text-xl font-bold text-white">{{ $dendaBelumLunas }}</p>
                        <p class="text-xs text-gray-400">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Peminjaman Chart -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 lg:col-span-2">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                    <h2 class="text-lg font-semibold text-white">Statistik Peminjaman 30 Hari Terakhir</h2>
                    <select class="bg-gray-700 text-white border border-gray-600 rounded-lg px-3 py-2 text-sm">
                        <option value="30">30 Hari</option>
                        <option value="7">7 Hari</option>
                        <option value="365">1 Tahun</option>
                    </select>
                </div>
                <div class="h-64 sm:h-80">
                    <canvas id="peminjamanChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Aktivitas Terkini</h2>
                    <a href="{{ route('admin.aktivitas.index') }}" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-4 max-h-64 sm:max-h-80 overflow-y-auto pr-2">
                    @forelse($aktivitasTerkini as $aktivitas)
                    <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $aktivitas['bgColor'] }}">
                                    {!! $aktivitas['icon'] !!}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1 gap-1">
                                    <p class="text-sm font-medium text-white truncate">{{ $aktivitas['judul'] }}</p>
                                    <span class="text-xs text-gray-400">{{ $aktivitas['waktu'] }}</span>
                                </div>
                                <p class="text-sm text-gray-300">{{ $aktivitas['deskripsi'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada aktivitas terkini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Popular Books and Active Users -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Popular Books -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Buku Paling Populer</h2>
                    <a href="{{ route('admin.buku.populer') }}" class="text-gray-400 hover:text-white text-sm flex items-center">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($bukuPopuler as $buku)
                    <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ $loop->iteration }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $buku->judul }}</p>
                                <p class="text-xs text-gray-400">{{ $buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}</p>
                            </div>
                            <div class="text-xs px-2 py-1 rounded-full bg-blue-600 text-white">
                                {{ $buku->peminjamans_count }}x dipinjam
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada data peminjaman buku</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">Anggota Paling Aktif</h2>
                    <a href="{{ route('admin.anggota.aktif') }}" class="text-gray-400 hover:text-white text-sm flex items-center">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($userAktif as $user)
                    <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ $loop->iteration }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                            <div class="text-xs px-2 py-1 rounded-full bg-green-600 text-white">
                                {{ $user->peminjamans_count }}x meminjam
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada data peminjaman</p>
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
    // Grafik Peminjaman
    const ctx = document.getElementById('peminjamanChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
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
});
</script>
@endsection