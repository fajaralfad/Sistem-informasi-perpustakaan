{{-- resources/views/admin/aktivitas/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-20"></div>
                <div class="relative bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Semua Aktivitas
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base">Riwayat lengkap aktivitas perpustakaan</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-600 hover:to-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300 flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm text-red-300">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 shadow-2xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4 border-b border-gray-600">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                    </svg>
                    Filter Aktivitas
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filter Waktu -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Waktu</label>
                        <select id="filterTime" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>

                    <!-- Filter Tipe -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Tipe</label>
                        <select id="filterType" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Aktivitas</option>
                            <option value="peminjaman" {{ $type == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                            <option value="pengembalian" {{ $type == 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                            <option value="user" {{ $type == 'user' ? 'selected' : '' }}>User Baru</option>
                            <option value="denda" {{ $type == 'denda' ? 'selected' : '' }}>Denda</option>
                        </select>
                    </div>

                    <!-- Items per page -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Tampilkan</label>
                        <select id="perPage" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4 border-b border-gray-600">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Aktivitas Terkini
                        @if($pagination['total'] > 0)
                            <span class="text-sm font-normal text-gray-300 ml-2">
                                ({{ $pagination['from'] }}-{{ $pagination['to'] }} dari {{ $pagination['total'] }})
                            </span>
                        @endif
                    </h2>
                    <!-- Refresh Button -->
                    <button id="refreshBtn" class="text-white hover:text-blue-300 transition-colors duration-200 p-2 rounded-lg hover:bg-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="hidden">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-3 text-white">Memuat aktivitas...</span>
                </div>
            </div>

            <!-- Activities List -->
            <div id="activitiesList" class="divide-y divide-gray-700">
                @if($aktivitasPaginated->count() > 0)
                    @foreach($aktivitasPaginated as $aktivitas)
                    <div class="px-6 py-4 hover:bg-gray-700 transition duration-300 group">
                        <div class="flex items-center gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full {{ $aktivitas['bgColor'] }} flex items-center justify-center {{ $aktivitas['textColor'] }}">
                                    {!! $aktivitas['icon'] !!}
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-white group-hover:text-blue-300 transition duration-200">
                                            {{ $aktivitas['judul'] }}
                                        </h3>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $aktivitas['deskripsi'] }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <!-- Type Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $aktivitas['bgColor'] }} {{ $aktivitas['textColor'] }}">
                                            {{ ucfirst($aktivitas['type']) }}
                                        </span>
                                        <!-- Time -->
                                        <span class="text-xs text-gray-400 flex-shrink-0">
                                            {{ $aktivitas['waktu'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a2 2 0 00-1.9 1.4L16 16l-1.6 1.6a2 2 0 01-1.9 1.4H9.5a2 2 0 01-1.9-1.4L6 16l-1.6-1.6A2 2 0 012.5 13H0" />
                            </svg>
                            <div class="text-center">
                                <p class="text-gray-400 text-sm font-medium">Belum ada aktivitas</p>
                                <p class="text-gray-500 text-xs mt-1">Aktivitas akan muncul setelah ada kegiatan di perpustakaan</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($pagination['total'] > 0 && $pagination['last_page'] > 1)
            <div class="bg-gray-750 px-6 py-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($pagination['current_page'] > 1)
                        <button id="prevPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600">
                            Sebelumnya
                        </button>
                        @endif
                        
                        @if($pagination['has_more_pages'])
                        <button id="nextPageMobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600">
                            Selanjutnya
                        </button>
                        @endif
                    </div>
                    
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-400">
                                Menampilkan <span class="font-medium text-white">{{ $pagination['from'] }}</span> - <span class="font-medium text-white">{{ $pagination['to'] }}</span> dari <span class="font-medium text-white">{{ $pagination['total'] }}</span> aktivitas
                            </p>
                        </div>
                        <div class="pagination-wrapper">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($pagination['current_page'] > 1)
                                <button id="prevPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-600 bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600">
                                    <span class="sr-only">Sebelumnya</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @endif
                                
                                @php
                                    $start = max(1, $pagination['current_page'] - 2);
                                    $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                <button data-page="1" class="page-btn relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600">1</button>
                                @if($start > 2)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-700 text-sm font-medium text-gray-500">...</span>
                                @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                <button data-page="{{ $i }}" class="page-btn relative inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium {{ $i == $pagination['current_page'] ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                                    {{ $i }}
                                </button>
                                @endfor
                                
                                @if($end < $pagination['last_page'])
                                @if($end < $pagination['last_page'] - 1)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-700 text-sm font-medium text-gray-500">...</span>
                                @endif
                                <button data-page="{{ $pagination['last_page'] }}" class="page-btn relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600">{{ $pagination['last_page'] }}</button>
                                @endif
                                
                                @if($pagination['has_more_pages'])
                                <button id="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-600 bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600">
                                    <span class="sr-only">Selanjutnya</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.25rem;
    }
    
    .pagination-wrapper .pagination a,
    .pagination-wrapper .pagination span {
        padding: 0.5rem 0.75rem;
        text-decoration: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .pagination-wrapper .pagination a {
        background-color: #374151;
        color: #d1d5db;
        border: 1px solid #4b5563;
    }
    
    .pagination-wrapper .pagination a:hover {
        background-color: #4b5563;
        color: #ffffff;
    }
    
    .pagination-wrapper .pagination .active span {
        background-color: #3b82f6;
        color: #ffffff;
        border: 1px solid #3b82f6;
    }
    
    .pagination-wrapper .pagination .disabled span {
        background-color: #1f2937;
        color: #6b7280;
        border: 1px solid #374151;
    }
    
    .bg-gray-750 {
        background-color: #334155;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTime = document.getElementById('filterTime');
    const filterType = document.getElementById('filterType');
    const perPage = document.getElementById('perPage');
    const refreshBtn = document.getElementById('refreshBtn');
    const loadingState = document.getElementById('loadingState');
    const activitiesList = document.getElementById('activitiesList');
    
    let currentPage = {{ $pagination['current_page'] }};
    
    // Function to load activities
    function loadActivities(page = 1, showLoading = true) {
        if (showLoading) {
            loadingState.classList.remove('hidden');
            activitiesList.style.opacity = '0.5';
        }
        
        const params = new URLSearchParams({
            filter: filterTime.value,
            type: filterType.value,
            per_page: perPage.value,
            page: page
        });
        
        fetch(`{{ route('admin.aktivitas.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the page content
                window.location.search = params.toString();
            } else {
                console.error('Error loading activities:', data.message);
                showError('Gagal memuat aktivitas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat aktivitas');
        })
        .finally(() => {
            if (showLoading) {
                loadingState.classList.add('hidden');
                activitiesList.style.opacity = '1';
            }
        });
    }
    
    // Event listeners
    filterTime.addEventListener('change', () => loadActivities(1));
    filterType.addEventListener('change', () => loadActivities(1));
    perPage.addEventListener('change', () => loadActivities(1));
    
    // Refresh button
    refreshBtn.addEventListener('click', function() {
        this.classList.add('animate-spin');
        loadActivities(currentPage, false);
        setTimeout(() => {
            this.classList.remove('animate-spin');
        }, 1000);
    });
    
    // Pagination buttons
    const prevPage = document.getElementById('prevPage');
    const nextPage = document.getElementById('nextPage');
    const prevPageMobile = document.getElementById('prevPageMobile');
    const nextPageMobile = document.getElementById('nextPageMobile');
    const pageButtons = document.querySelectorAll('.page-btn');
    
    if (prevPage) {
        prevPage.addEventListener('click', () => loadActivities(currentPage - 1));
    }
    
    if (nextPage) {
        nextPage.addEventListener('click', () => loadActivities(currentPage + 1));
    }
    
    if (prevPageMobile) {
        prevPageMobile.addEventListener('click', () => loadActivities(currentPage - 1));
    }
    
    if (nextPageMobile) {
        nextPageMobile.addEventListener('click', () => loadActivities(currentPage + 1));
    }
    
    pageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const page = parseInt(this.dataset.page);
            loadActivities(page);
        });
    });
    
    // Error handling
    function showError(message) {
        // You can implement a toast notification here
        console.error(message);
    }
    
    // Auto refresh every 30 seconds
    setInterval(() => {
        loadActivities(currentPage, false);
    }, 30000);
});
</script>
@endpush
@endsection