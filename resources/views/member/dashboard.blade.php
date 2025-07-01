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
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Buku Dipinjam -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['sedang_dipinjam'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Peminjaman -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_peminjaman'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Denda Aktif -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Denda Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['denda_aktif'] }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Buku Terlambat -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['terlambat'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Utama -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
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
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Peminjaman Aktif -->
            @if($peminjaman_aktif && $peminjaman_aktif->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Peminjaman Aktif</h3>
                <div class="space-y-3">
                    @foreach($peminjaman_aktif->take(3) as $peminjaman)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 text-sm">{{ $peminjaman->buku->judul }}</h4>
                            <p class="text-xs text-gray-600">
                                Jatuh tempo: {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                            </p>
                            @if(\Carbon\Carbon::parse($peminjaman->tanggal_kembali)->lt(now()))
                                <span class="inline-block px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full mt-1">
                                    Terlambat
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($peminjaman_aktif->count() > 3)
                <div class="mt-4">
                    <a href="{{ route('member.riwayat') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat semua →
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Notifikasi Denda -->
            @if($denda_aktif && $denda_aktif->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Denda Aktif</h3>
                <div class="space-y-2">
                    @foreach($denda_aktif->take(2) as $denda)
                    <div class="p-3 bg-red-50 rounded-lg">
                        <p class="font-medium text-red-800 text-sm">{{ $denda->peminjaman->buku->judul }}</p>
                        <p class="text-red-600 text-sm">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('member.denda.index') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Kelola Denda →
                    </a>
                </div>
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
            <h3 class="text-xl font-bold text-gray-800 mb-4">📚 Rekomendasi Buku</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($buku_rekomendasi->take(6) as $buku)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h4 class="font-semibold text-gray-800 text-sm mb-2">{{ $buku->judul }}</h4>
                    <p class="text-xs text-gray-600 mb-2">{{ $buku->pengarang->nama ?? 'N/A' }}</p>
                    <p class="text-xs text-blue-600">{{ $buku->peminjamans_count }} kali dipinjam</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto refresh setiap 5 menit untuk update status
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            // Refresh hanya statistik quick tanpa reload seluruh halaman
            fetch('{{ route("member.dashboard.quick-stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update statistik jika ada perubahan
                    // Implementasikan update DOM jika diperlukan
                })
                .catch(error => console.log('Error updating stats:', error));
        }
    }, 300000); // 5 menit
</script>
@endpush
@endsection