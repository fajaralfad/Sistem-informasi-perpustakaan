<nav x-data="{ open: false }" class="bg-gray-800 dark:bg-gray-900 border-b border-gray-700 dark:border-gray-600 relative z-30">
    <!-- Background with glassmorphism effect -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-90 dark:bg-gray-900 dark:bg-opacity-90 backdrop-blur-md"></div>
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo and Text -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                        <div class="flex flex-col">
                            <span class="text-white font-bold text-lg leading-tight">Sistem Informasi</span>
                            <span class="text-white font-bold text-lg leading-tight">Perpustakaan</span>
                            <span class="text-gray-300 text-sm">Kejari Bandar Lampung</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <!-- Admin Navigation Links -->
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.buku.index')" :active="request()->routeIs('admin.buku.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Buku') }}
                            </x-nav-link>
                             <x-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Kategori') }}
                            </x-nav-link>
                             <x-nav-link :href="route('admin.pengarang.index')" :active="request()->routeIs('admin.pengarang.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Pengarang') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.anggota.index')" :active="request()->routeIs('admin.anggota.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Anggota') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.peminjaman.index')" :active="request()->routeIs('admin.peminjaman.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Peminjaman') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.denda.index')" :active="request()->routeIs('admin.denda.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Denda') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.laporan.buku')" :active="request()->routeIs('admin.laporan.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Laporan') }}
                            </x-nav-link>
                        @elseif(auth()->user()->role === 'anggota')
                            <!-- Member Navigation Links -->
                            <x-nav-link :href="route('member.dashboard')" :active="request()->routeIs('member.dashboard')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('member.katalog')" :active="request()->routeIs('member.katalog')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Katalog Buku') }}
                            </x-nav-link>
                            <x-nav-link :href="route('member.riwayat')" :active="request()->routeIs('member.riwayat')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Riwayat Peminjaman') }}
                            </x-nav-link>
                            <x-nav-link :href="route('member.denda.index')" :active="request()->routeIs('member.denda.*')" 
                                        class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white border-b-2 border-transparent hover:border-blue-400 dark:hover:border-blue-300 transition-all duration-300">
                                {{ __('Denda') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-600 dark:border-gray-500 text-sm leading-4 font-medium rounded-md text-gray-200 dark:text-gray-100 bg-gray-700 dark:bg-gray-800 hover:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300 backdrop-blur-sm">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-gray-800 dark:bg-gray-900 backdrop-blur-md border border-gray-600 dark:border-gray-500 rounded-md shadow-lg">
                            <x-dropdown-link :href="route('profile.edit')" 
                                           class="text-gray-200 dark:text-gray-100 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="text-gray-200 dark:text-gray-100 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-700 dark:focus:bg-gray-800 focus:text-white dark:focus:text-white transition-all duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Background for mobile menu -->
        <div class="absolute inset-0 bg-gray-800 dark:bg-gray-900 bg-opacity-95 dark:bg-opacity-95 backdrop-blur-md"></div>
        
        <div class="relative">
            <!-- Mobile Logo Section -->
            <div class="px-4 py-3 border-b border-gray-700 dark:border-gray-600">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-base leading-tight">Sistem Informasi Perpustakaan</span>
                        <span class="text-gray-300 text-sm">Kejari Bandar Lampung</span>
                    </div>
                </div>
            </div>
            
            <div class="pt-2 pb-3 space-y-1">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Mobile Links -->
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.buku.index')" :active="request()->routeIs('admin.buku.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Manajemen Buku') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Kategori') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.pengarang.index')" :active="request()->routeIs('admin.pengarang.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Pengarang') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.anggota.index')" :active="request()->routeIs('admin.anggota.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Manajemen Anggota') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.peminjaman.index')" :active="request()->routeIs('admin.peminjaman.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Peminjaman') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.denda.index')" :active="request()->routeIs('admin.denda.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Denda') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.laporan.buku')" :active="request()->routeIs('admin.laporan.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Laporan') }}
                        </x-responsive-nav-link>
                    @elseif(auth()->user()->role === 'anggota')
                        <!-- Member Mobile Links -->
                        <x-responsive-nav-link :href="route('member.dashboard')" :active="request()->routeIs('member.dashboard')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('member.katalog')" :active="request()->routeIs('member.katalog')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Katalog Buku') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('member.riwayat')" :active="request()->routeIs('member.riwayat')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Riwayat Peminjaman') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('member.denda.index')" :active="request()->routeIs('member.denda.*')" 
                                             class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Denda') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-700 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-200 dark:text-gray-100">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400 dark:text-gray-300">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" 
                                         class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-gray-300 dark:text-gray-200 hover:text-white dark:hover:text-white hover:bg-gray-700 dark:hover:bg-gray-800 transition-all duration-200">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>