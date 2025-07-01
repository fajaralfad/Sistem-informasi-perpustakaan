<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Perpustakaan Kejaksaan Bandar Lampung</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen gradient-bg bg-pattern bg-pattern-blur pattern-default pattern-normal animate-pattern-float overflow-x-hidden">

    <!-- Navigation -->
    <nav class="relative z-10 w-full px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3 animate-fade-in">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center glass-effect">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="text-white">
                    <h1 class="font-bold text-lg">PERPUSTAKAAN</h1>
                    <p class="text-xs opacity-90">Kejaksaan Bandar Lampung</p>
                </div>
            </div>

            <!-- Auth Links -->
            @if (Route::has('login'))
                <nav class="flex items-center space-x-3 animate-fade-in">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="btn-primary flex items-center">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-6 py-2.5 text-white/90 hover:text-white transition-all duration-300 font-medium hover:bg-white/10 rounded-xl">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-6 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl font-medium transition-all duration-300 glass-effect hover:scale-105 transform">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-6 py-12">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Text -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full text-white/90 text-sm font-medium mb-6 glass-effect animate-fade-in">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Sistem Manajemen Perpustakaan Digital
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 leading-tight animate-slide-in-left">
                    Perpustakaan
                    <span class="block text-gradient">
                        Kejaksaan
                    </span>
                    <span class="block text-3xl lg:text-4xl">Bandar Lampung</span>
                </h1>
                
                <p class="text-xl text-white/80 mb-8 max-w-2xl animate-slide-in-left">
                    Portal digital untuk mengelola koleksi buku, peminjaman, dan layanan perpustakaan dengan sistem yang modern dan terintegrasi.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-slide-in-left">
                    <a href="#" class="btn-primary flex items-center">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Masuk ke Dashboard
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white/10 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 glass-effect">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            <!-- Hero Illustration -->
            <div class="relative animate-slide-in-right">
                <div class="relative z-10 animate-floating">
                    <div class="card-glass p-8 shadow-2xl">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <!-- Feature Cards -->
                            <div class="card-glass p-4">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <h3 class="text-white font-semibold text-sm mb-1">Kelola Buku</h3>
                                <p class="text-white/70 text-xs">Manajemen koleksi digital</p>
                            </div>
                            
                            <div class="card-glass p-4">
                                <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-white font-semibold text-sm mb-1">Anggota</h3>
                                <p class="text-white/70 text-xs">Registrasi & profil</p>
                            </div>
                            
                            <div class="card-glass p-4">
                                <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-white font-semibold text-sm mb-1">Peminjaman</h3>
                                <p class="text-white/70 text-xs">Tracking otomatis</p>
                            </div>
                            
                            <div class="card-glass p-4">
                                <div class="w-10 h-10 bg-red-500/20 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-white font-semibold text-sm mb-1">Laporan</h3>
                                <p class="text-white/70 text-xs">Analytics lengkap</p>
                            </div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="card-glass p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white/70 text-sm">Statistik Hari Ini</span>
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                    <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-white font-bold text-lg">1,245</div>
                                    <div class="text-white/60 text-xs">Total Buku</div>
                                </div>
                                <div>
                                    <div class="text-white font-bold text-lg">156</div>
                                    <div class="text-white/60 text-xs">Anggota</div>
                                </div>
                                <div>
                                    <div class="text-white font-bold text-lg">23</div>
                                    <div class="text-white/60 text-xs">Dipinjam</div>
                                </div>
                            </div>
                        </div>
                    </div
                                        </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-300/20 rounded-full blur-xl"></div>
                <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-pink-300/20 rounded-full blur-xl"></div>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="relative z-10 py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                    Fitur Unggulan Sistem
                </h2>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    Solusi lengkap untuk mengelola perpustakaan modern dengan teknologi terdepan
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Cards -->
                @php
                    $features = [
                        [
                            'icon' => 'book',
                            'color' => 'blue',
                            'title' => 'Manajemen Koleksi',
                            'desc' => 'Kelola inventaris buku secara digital dengan sistem kategorisasi yang lengkap dan pencarian yang cepat.'
                        ],
                        [
                            'icon' => 'clock',
                            'color' => 'green',
                            'title' => 'Peminjaman Real-time',
                            'desc' => 'Sistem peminjaman otomatis dengan tracking waktu pengembalian dan notifikasi reminder.'
                        ],
                        [
                            'icon' => 'chart-bar',
                            'color' => 'purple',
                            'title' => 'Dashboard Analytics',
                            'desc' => 'Laporan dan statistik lengkap untuk memantau aktivitas perpustakaan secara komprehensif.'
                        ],
                        [
                            'icon' => 'users',
                            'color' => 'yellow',
                            'title' => 'Manajemen Anggota',
                            'desc' => 'Registrasi dan pengelolaan profil anggota dengan riwayat aktivitas yang lengkap.'
                        ],
                        [
                            'icon' => 'currency-dollar',
                            'color' => 'red',
                            'title' => 'Sistem Denda',
                            'desc' => 'Pengelolaan denda otomatis untuk keterlambatan pengembalian dengan kalkulasi yang akurat.'
                        ],
                        [
                            'icon' => 'shield-check',
                            'color' => 'indigo',
                            'title' => 'Keamanan Data',
                            'desc' => 'Sistem keamanan berlapis dengan backup otomatis dan kontrol akses yang ketat.'
                        ]
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="card-glass hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-14 h-14 bg-{{ $feature['color'] }}-500/20 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($feature['icon'] == 'book')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                @elseif($feature['icon'] == 'clock')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @elseif($feature['icon'] == 'chart-bar')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                @elseif($feature['icon'] == 'users')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                @elseif($feature['icon'] == 'currency-dollar')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @elseif($feature['icon'] == 'shield-check')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                @endif
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-white/70">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Mobile Menu (Hidden by default) -->
    <div x-data="{ mobileMenuOpen: false }" 
         x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed inset-0 z-50 bg-gray-900/90 backdrop-blur-sm p-6"
         @click.away="mobileMenuOpen = false">
        <div class="bg-white rounded-xl p-6 max-w-sm mx-auto mt-16">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold">Menu</h3>
                <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="space-y-4">
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-gray-100">Beranda</a>
                <a href="#features" class="block px-4 py-2 rounded-lg hover:bg-gray-100">Fitur</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100">Masuk</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100">Daftar</a>
                    @endif
                @endauth
            </nav>
        </div>
    </div>

    <!-- Footer -->
    <footer class="relative z-10 py-8 px-6 border-t border-white/10">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center glass-effect">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-white font-semibold">Perpustakaan Kejaksaan Bandar Lampung</span>
            </div>
            <p class="text-white/60 text-sm">
                © {{ date('Y') }} Kejaksaan Bandar Lampung. Semua hak dilindungi undang-undang.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    
    <!-- Mobile Menu Toggle Button (Hidden on desktop) -->
    <button x-data @click="mobileMenuOpen = true" class="md:hidden fixed bottom-6 right-6 z-40 w-14 h-14 bg-purple-600 rounded-full shadow-lg flex items-center justify-center text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
</body>
</html>