{{-- resources/views/admin/aktivitas/index.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- Main Container with Gradient Background and Pattern -->
<div class="dashboard-container">
    <div class="container mx-auto px-4 py-8 relative z-10">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div class="animate-fade-in">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Semua Aktivitas</h1>
                <p class="text-white text-opacity-90 text-lg">Riwayat lengkap aktivitas perpustakaan</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="card-glass text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Error Message -->
        @if(session('error'))
        <div class="content-card-glass mb-6 border-l-4 border-red-500">
            <div class="p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filter Bar -->
        <div class="content-card-glass mb-6">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-4">
                    <!-- Filter Waktu -->
                    <div class="flex items-center space-x-2">
                        <label class="text-white text-sm font-medium">Waktu:</label>
                        <select id="filterTime" class="dashboard-select text-sm">
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>

                    <!-- Filter Tipe -->
                    <div class="flex items-center space-x-2">
                        <label class="text-white text-sm font-medium">Tipe:</label>
                        <select id="filterType" class="dashboard-select text-sm">
                            <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Aktivitas</option>
                            <option value="peminjaman" {{ $type == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                            <option value="pengembalian" {{ $type == 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                            <option value="user" {{ $type == 'user' ? 'selected' : '' }}>User Baru</option>
                            <option value="denda" {{ $type == 'denda' ? 'selected' : '' }}>Denda</option>
                        </select>
                    </div>
                </div>

                <!-- Items per page -->
                <div class="flex items-center space-x-2">
                    <label class="text-white text-sm font-medium">Tampilkan:</label>
                    <select id="perPage" class="dashboard-select text-sm">
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Aktivitas List -->
        <div class="content-card-glass">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-white">
                    Aktivitas Terkini
                    @if($pagination['total'] > 0)
                        <span class="text-sm font-normal text-gray-300 ml-2">
                            ({{ $pagination['from'] }}-{{ $pagination['to'] }} dari {{ $pagination['total'] }})
                        </span>
                    @endif
                </h2>
                
                <!-- Refresh Button -->
                <button id="refreshBtn" class="text-white hover:text-blue-300 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="hidden">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                    <span class="ml-3 text-white">Memuat aktivitas...</span>
                </div>
            </div>

            <!-- Activities List -->
            <div id="activitiesList">
                @if($aktivitasPaginated->count() > 0)
                    <div class="space-y-3">
                        @foreach($aktivitasPaginated as $aktivitas)
                        <div class="activity-item {{ $aktivitas['type'] }}-activity">
                            <div class="flex items-start space-x-4 p-4 rounded-lg border border-white border-opacity-10 hover:border-opacity-20 transition-all duration-300 hover:bg-white hover:bg-opacity-5">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full {{ $aktivitas['bgColor'] }} flex items-center justify-center {{ $aktivitas['textColor'] }}">
                                        {!! $aktivitas['icon'] !!}
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-white">
                                            {{ $aktivitas['judul'] }}
                                        </p>
                                        <span class="text-xs text-gray-400 flex-shrink-0 ml-4">
                                            {{ $aktivitas['waktu'] }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-300">
                                        {{ $aktivitas['deskripsi'] }}
                                    </p>
                                    
                                    <!-- Type Badge -->
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $aktivitas['bgColor'] }} {{ $aktivitas['textColor'] }}">
                                            {{ ucfirst($aktivitas['type']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a2 2 0 00-1.9 1.4L16 16l-1.6 1.6a2 2 0 01-1.9 1.4H9.5a2 2 0 01-1.9-1.4L6 16l-1.6-1.6A2 2 0 012.5 13H0" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-300">Tidak ada aktivitas</h3>
                        <p class="mt-1 text-sm text-gray-400">Belum ada aktivitas untuk filter yang dipilih.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($pagination['total'] > 0 && $pagination['last_page'] > 1)
            <div class="mt-6 border-t border-white border-opacity-10 pt-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($pagination['current_page'] > 1)
                        <button id="prevPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Sebelumnya
                        </button>
                        @endif
                        
                        @if($pagination['has_more_pages'])
                        <button id="nextPageMobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Selanjutnya
                        </button>
                        @endif
                    </div>
                    
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-300">
                                Menampilkan <span class="font-medium">{{ $pagination['from'] }}</span> sampai <span class="font-medium">{{ $pagination['to'] }}</span> dari <span class="font-medium">{{ $pagination['total'] }}</span> hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($pagination['current_page'] > 1)
                                <button id="prevPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-600 bg-gray-800 text-sm font-medium text-gray-300 hover:bg-gray-700">
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
                                <button data-page="1" class="page-btn relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-800 text-sm font-medium text-gray-300 hover:bg-gray-700">1</button>
                                @if($start > 2)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-800 text-sm font-medium text-gray-500">...</span>
                                @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                <button data-page="{{ $i }}" class="page-btn relative inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium {{ $i == $pagination['current_page'] ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                                    {{ $i }}
                                </button>
                                @endfor
                                
                                @if($end < $pagination['last_page'])
                                @if($end < $pagination['last_page'] - 1)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-800 text-sm font-medium text-gray-500">...</span>
                                @endif
                                <button data-page="{{ $pagination['last_page'] }}" class="page-btn relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-800 text-sm font-medium text-gray-300 hover:bg-gray-700">{{ $pagination['last_page'] }}</button>
                                @endif
                                
                                @if($pagination['has_more_pages'])
                                <button id="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-600 bg-gray-800 text-sm font-medium text-gray-300 hover:bg-gray-700">
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

@push('styles')
<style>
.dashboard-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.dashboard-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
    pointer-events: none;
}

.content-card-glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.card-glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
}

.dashboard-select {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    padding: 0.5rem 1rem;
    outline: none;
    transition: all 0.3s ease;
}

.dashboard-select:focus {
    border-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.dashboard-select option {
    background: #374151;
    color: white;
}

.activity-item {
    transition: all 0.3s ease;
}

.activity-item:hover {
    transform: translateY(-2px);
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.peminjaman-activity {
    border-left: 3px solid #3b82f6;
}

.pengembalian-activity {
    border-left: 3px solid #10b981;
}

.user-activity {
    border-left: 3px solid #8b5cf6;
}

.denda-activity {
    border-left: 3px solid #ef4444;
}

.system-activity {
    border-left: 3px solid #6b7280;
}

.error-activity {
    border-left: 3px solid #ef4444;
}
</style>
@endpush

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