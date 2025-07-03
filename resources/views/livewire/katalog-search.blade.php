<div>
    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari buku</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg" 
                           placeholder="Cari judul buku, pengarang, ISBN...">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <select wire:model.live="kategori" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                    @endforeach
                </select>
                @if($search || $kategori)
                <button wire:click="resetFilters" type="button"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg font-medium transition-colors duration-200">
                    Reset
                </button>
                @endif
            </div>
        </div>

        <!-- Results Counter -->
        <div class="mt-4 text-sm text-gray-600">
            @if($search || $kategori)
                <span class="font-medium">{{ $bukus->total() }}</span> buku ditemukan
                @if($search)
                    untuk "<span class="font-semibold text-indigo-600">{{ $search }}</span>"
                @endif
                @if($kategori)
                    @php $selectedKategori = $kategoris->find($kategori) @endphp
                    @if($selectedKategori)
                        dalam kategori "<span class="font-semibold text-indigo-600">{{ $selectedKategori->nama }}</span>"
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($bukus as $buku)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden">
                <!-- Book Cover -->
                <div class="relative aspect-[3/4] bg-gradient-to-br from-gray-50 to-gray-100">
                    @if($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" 
                             alt="Cover {{ $buku->judul }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <p class="text-xs text-indigo-600 font-medium">No Cover</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Stock Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($buku->stok > 10)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                                Tersedia
                            </span>
                        @elseif($buku->stok > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></div>
                                Terbatas
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <div class="w-2 h-2 bg-red-400 rounded-full mr-1"></div>
                                Habis
                            </span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $buku->kategori->nama ?? 'Uncategorized' }}
                        </span>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="p-4">
                    <div class="mb-2">
                        <h3 class="font-bold text-lg text-gray-900 line-clamp-2 mb-1">
                            @if($search)
                                {!! str_ireplace($search, '<mark class="bg-yellow-200">' . $search . '</mark>', e($buku->judul)) !!}
                            @else
                                {{ $buku->judul }}
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-medium">Pengarang:</span> 
                            @if($search && $buku->pengarang)
                                {!! str_ireplace($search, '<mark class="bg-yellow-200">' . $search . '</mark>', e($buku->pengarang->nama)) !!}
                            @else
                                {{ $buku->pengarang->nama ?? 'Unknown' }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">
                            ISBN: 
                            @if($search)
                                {!! str_ireplace($search, '<mark class="bg-yellow-200">' . $search . '</mark>', e($buku->isbn)) !!}
                            @else
                                {{ $buku->isbn }}
                            @endif
                            • {{ $buku->tahun_terbit }}
                        </p>
                    </div>

                    <!-- Stock Info -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 01-1-1V5a1 1 0 011-1h4zM9 3v1h6V3H9z" />
                            </svg>
                            <span class="text-gray-600">Stok: <span class="font-semibold">{{ $buku->stok }}</span></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex gap-2">
                        <a href="{{ route('member.buku.detail', $buku->id) }}" 
                           class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Detail
                        </a>
                    </div>

                    <!-- Borrowing Note -->
                    <div class="mt-3 text-center">
                        <p class="text-xs text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pinjam di perpustakaan
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $bukus->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-2xl font-medium text-gray-900 mb-2">Tidak Ada Buku Ditemukan</h3>
            <p class="text-gray-500 mb-6">
                @if($search || $kategori)
                    Coba ubah kata kunci pencarian atau filter kategori Anda.
                @else
                    Maaf, saat ini tidak ada buku yang tersedia di perpustakaan.
                @endif
            </p>
            @if($search || $kategori)
            <button wire:click="resetFilters" type="button"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Lihat Semua Buku
            </button>
            @endif
        </div>
        @endif
    </div>

    <!-- Internal Styles -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .aspect-\[3\/4\] {
            aspect-ratio: 3/4;
        }

        mark {
            background-color: #fef08a;
            padding: 0 2px;
            border-radius: 2px;
        }
    </style>
</div>