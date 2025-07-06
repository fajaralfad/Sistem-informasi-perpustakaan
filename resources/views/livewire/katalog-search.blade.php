<div>
    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-8 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari buku</label>
                <div class="relative">
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg text-gray-900 dark:text-gray-100" 
                           placeholder="Cari judul buku, pengarang, ISBN...">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <select wire:model.live="kategori" class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                    @endforeach
                </select>
                @if($search || $kategori)
                <button wire:click="resetFilters" type="button"
                   class="bg-indigo-600 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-3 rounded-lg font-medium transition-colors duration-200">
                    Reset
                </button>
                @endif
            </div>
        </div>

        <!-- Results Counter -->
        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            @if($search || $kategori)
                <span class="font-medium">{{ $bukus->total() }}</span> buku ditemukan
                @if($search)
                    untuk "<span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $search }}</span>"
                @endif
                @if($kategori)
                    @php $selectedKategori = $kategoris->find($kategori) @endphp
                    @if($selectedKategori)
                        dalam kategori "<span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $selectedKategori->nama }}</span>"
                    @endif
                @endif
            @else
                Menampilkan <span class="font-medium">{{ $bukus->total() }}</span> buku tersedia
            @endif
        </div>
    </div>

    <!-- Book Grid -->
    <div wire:loading.remove>
        @if($bukus->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-5">
            @foreach($bukus as $buku)
            <div class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full">
                <!-- Book Cover -->
                <div class="relative w-full aspect-[2/3] bg-gray-50 dark:bg-gray-700 overflow-hidden">
                    @if($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" 
                             alt="Cover {{ $buku->judul }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900 dark:to-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-400 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-2 left-2 right-2 flex justify-between items-start">
                        <!-- Category Badge -->
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-blue-600 dark:text-blue-300 shadow-sm">
                            {{ $buku->kategori->nama ?? '-' }}
                        </span>
                        
                        <!-- Stock Status Badge -->
                        @if($buku->stok > 10)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-green-600 dark:text-green-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Tersedia
                            </span>
                        @elseif($buku->stok > 0)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-yellow-600 dark:text-yellow-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Terbatas
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-red-600 dark:text-red-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Book Info -->
                <div class="p-3 sm:p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1 line-clamp-2 text-sm sm:text-base">
                        {{ $buku->judul }}
                    </h3>
                    
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3 space-y-1">
                        <p class="line-clamp-1">
                            <span class="font-medium">By:</span> {{ $buku->pengarang->nama ?? 'Unknown' }}
                        </p>
                        <p class="line-clamp-1">
                            <span class="font-medium">ISBN:</span> {{ $buku->isbn }}
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-auto">
                        <a href="{{ route('member.buku.detail', $buku->id) }}" 
                           class="w-full inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-md text-xs sm:text-sm font-medium transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bukus->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-2xl font-medium text-gray-900 dark:text-gray-100 mb-2">Tidak Ada Buku Ditemukan</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                @if($search || $kategori)
                    Coba ubah kata kunci pencarian atau filter kategori Anda.
                @else
                    Maaf, saat ini tidak ada buku yang tersedia di perpustakaan.
                @endif
            </p>
            @if($search || $kategori)
            <button wire:click="resetFilters" type="button"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                Lihat Semua Buku
            </button>
            @endif
        </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Memuat data buku...</p>
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .aspect-\[2\/3\] {
            aspect-ratio: 2/3;
        }
    </style>
</div>