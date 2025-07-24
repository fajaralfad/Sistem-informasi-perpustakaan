<div>
    <!-- Export Buttons -->
    <div class="flex justify-end gap-3 mb-4">
        <a href="{{ route('admin.buku.export.excel') }}?search={{ $search }}&kategori={{ $kategori }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </a>
        
        <a href="{{ route('admin.buku.export.pdf') }}?search={{ $search }}&kategori={{ $kategori }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export PDF
        </a>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-700">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <label for="search" class="sr-only">Cari buku</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-lg leading-5 bg-gray-700 text-gray-700 placeholder-gray-400 focus:outline-none focus:placeholder-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Cari buku berdasarkan judul, ISBN, atau pengarang...">
                </div>
            </div>
            
            <!-- Category Filter -->
            <div class="flex items-center gap-3">
                <select wire:model.live="kategori" class="px-4 py-2 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategoriItem)
                        <option value="{{ $kategoriItem->id }}">{{ $kategoriItem->nama }}</option>
                    @endforeach
                </select>
                
                @if($search || $kategori)
                    <button wire:click="resetFilters" 
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Reset
                    </button>
                @endif
            </div>
            
            <!-- Total Count -->
            <div class="text-sm text-gray-400">
                Total: <span class="font-semibold text-white">{{ $bukus->total() }}</span> buku
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Memuat data buku...</p>
    </div>

    <!-- Book Table -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700" wire:loading.remove>
        @if($bukus->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cover</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    Judul Buku
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pengarang</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stok</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($bukus as $buku)
                        <tr class="hover:bg-gray-700 transition-colors duration-150">
                            <!-- Cover Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($buku->cover)
                                        <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" class="h-12 w-12 object-cover rounded">
                                    @else
                                        <div class="h-12 w-12 bg-gray-700 rounded flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Title Column -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-white">{{ $buku->judul }}</div>
                                <div class="text-sm text-gray-400">
                                    Total Copy: {{ $buku->total_copy }} buku<br>
                                    ISBN: {{ Str::limit($buku->isbn_list, 20) }}
                                    @if(strlen($buku->isbn_list) > 20)
                                        <button wire:click="showAllIsbn('{{ $buku->judul }}', '{{ $buku->isbn_list }}')" 
                                                class="text-blue-400 hover:text-blue-300 text-xs ml-1">
                                            Lihat Semua
                                        </button>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Category Column -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200">
                                    {{ $buku->kategori->nama ?? '-' }}
                                </span>
                            </td>
                            
                            <!-- Author Column -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">{{ $buku->pengarang->nama ?? '-' }}</div>
                            </td>
                            
                            <!-- Stock Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($buku->total_stok > 10)
                                        <div class="flex-shrink-0 w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        <span class="text-sm text-green-400 font-medium">{{ $buku->total_stok }}</span>
                                    @elseif($buku->total_stok > 0)
                                        <div class="flex-shrink-0 w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                        <span class="text-sm text-yellow-400 font-medium">{{ $buku->total_stok }}</span>
                                    @else
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                        <span class="text-sm text-red-400 font-medium">{{ $buku->total_stok }}</span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Date Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $buku->created_at?->format('d M Y') ?? '-' }}
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.buku.show', $buku->id) }}" 
                                       class="bg-blue-900 hover:bg-blue-800 text-blue-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                       title="Lihat Detail Buku">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat
                                    </a>
                                    <a href="{{ route('admin.buku.edit', $buku->id) }}" 
                                       class="bg-yellow-900 hover:bg-yellow-800 text-yellow-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                       title="Edit Buku">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <button wire:click="confirmDelete({{ $buku->id }}, '{{ $buku->judul }}')" 
                                            class="bg-red-900 hover:bg-red-800 text-red-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                            title="Hapus Buku">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-800 px-4 py-3 border-t border-gray-700 sm:px-6">
                {{ $bukus->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">
                    @if($search || $kategori)
                        Tidak ada buku yang ditemukan
                    @else
                        Belum ada buku
                    @endif
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    @if($search || $kategori)
                        Coba ubah kata kunci pencarian atau filter kategori Anda.
                    @else
                        Mulai dengan menambahkan buku pertama ke perpustakaan.
                    @endif
                </p>
                <div class="mt-6">
                    @if($search || $kategori)
                        <button wire:click="resetFilters" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset Filter
                        </button>
                    @else
                        <a href="{{ route('admin.buku.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Buku
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal (Simplified - Will use Toast) -->
    @if($bukuIdToDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" x-data="{ open: true }" x-show="open" @keydown.escape.window="open = false">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-900 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-white text-center mb-4">Konfirmasi Hapus</h2>
            <p class="text-gray-300 text-center mb-6">
                Yakin ingin menghapus buku <strong class="text-white">{{ $bukuJudulToDelete }}</strong> dan semua copy-nya? 
                <br><span class="text-sm text-red-400">Tindakan ini tidak dapat dibatalkan.</span>
            </p>
            <div class="flex justify-center gap-3">
                <button wire:click="$set('bukuIdToDelete', null)" 
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button wire:click="delete" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Ya, Hapus Semua
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ISBN Modal (Simplified - Will use Toast) -->
    @if($showIsbnModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" x-data="{ open: true }" x-show="open" @keydown.escape.window="open = false">
        <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">
                Daftar ISBN - {{ $selectedBookTitle }}
            </h3>
            <div class="max-h-60 overflow-y-auto">
                <ul class="space-y-2">
                    @foreach($selectedBookIsbns as $isbn)
                    <li class="text-gray-300 p-2 bg-gray-700 rounded flex justify-between items-center">
                        <span>{{ trim($isbn) }}</span>
                        <button wire:click="copyToClipboard('{{ trim($isbn) }}')" 
                                class="text-blue-400 hover:text-blue-300 ml-2"
                                title="Salin ISBN">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6 flex justify-end">
                <button wire:click="closeIsbnModal" 
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Handle ISBN copy with Toast
            Livewire.on('copied-isbn', (event) => {
                const isbn = event.isbn;
                
                navigator.clipboard.writeText(isbn).then(() => {
                    // Use the existing toast handler from app layout
                    Livewire.dispatch('show-toast', {
                        type: 'success',
                        message: 'ISBN berhasil disalin: ' + isbn
                    });
                }).catch(err => {
                    console.error('Failed to copy ISBN:', err);
                    Livewire.dispatch('show-toast', {
                        type: 'error',
                        message: 'Gagal menyalin ISBN: ' + err.message
                    });
                });
            });

            // Handle delete confirmation with Toast
            Livewire.on('delete-confirmed', (event) => {
                Livewire.dispatch('show-toast', {
                    type: 'success',
                    message: 'Buku berhasil dihapus: ' + event.title
                });
            });

            // Handle errors with Toast
            Livewire.on('error', (event) => {
                Livewire.dispatch('show-toast', {
                    type: 'error',
                    message: event.message
                });
            });
        });
    </script>
    @endpush
</div>