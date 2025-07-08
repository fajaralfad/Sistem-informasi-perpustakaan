<div>
    <!-- Search and Filter Section - Dark Version (Improved) -->
<div class="bg-gray-800 rounded-lg shadow-md p-4 sm:p-6 mb-6 border border-gray-700">
    <div class="flex flex-col gap-4">
        <!-- Search Input -->
        <div class="w-full">
            <label for="search" class="sr-only">Cari buku</label>
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-lg bg-gray-700 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Cari judul buku..."
                >
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <!-- Status Filter -->
            <div class="flex-1 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <select 
                    wire:model.live="status"
                    class="w-full sm:w-auto px-3 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="selesai">Selesai</option>
                    <option value="terlambat">Terlambat</option>
                </select>

                <!-- Reset Button -->
                <button 
                    wire:click="resetFilters"
                    class="w-full sm:w-auto px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </button>
            </div>

            <!-- Results Count -->
            @if($search || $status)
                <div class="text-sm text-gray-400 flex items-center">
                    <span class="hidden sm:inline mr-2">Filter aktif:</span>
                    <button 
                        wire:click="resetFilters" 
                        class="text-blue-400 hover:text-blue-300 underline flex items-center"
                    >
                        Reset semua
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Memuat data riwayat peminjaman...</p>
    </div>

    <!-- Borrowing History Table - Dark Version -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700" wire:loading.remove>
        @if($peminjamans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Buku
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Tanggal Pinjam
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Tanggal Kembali
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Denda
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($peminjamans as $index => $peminjaman)
                        <tr class="hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $peminjamans->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($peminjaman->buku->cover)
                                            <img class="h-10 w-10 rounded-lg object-cover" src="{{ asset('storage/' . $peminjaman->buku->cover) }}" alt="{{ $peminjaman->buku->judul }}">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-700 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $peminjaman->buku->judul }}</div>
                                        <div class="text-sm text-gray-400">{{ $peminjaman->buku->pengarang->nama ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                                <div class="text-sm text-gray-400">{{ $peminjaman->tanggal_pinjam->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</div>
                                <div class="text-sm text-gray-400">{{ $peminjaman->tanggal_kembali->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($peminjaman->status)
                                    @case('pending')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200">
                                            Menunggu Konfirmasi
                                        </span>
                                        @break
                                    @case('booking')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900 text-blue-200">
                                            Booking Dikonfirmasi
                                        </span>
                                        @break
                                    @case('dipinjam')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200">
                                            Sedang Dipinjam
                                        </span>
                                        @break
                                    @case('dikembalikan')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-200">
                                            Dikembalikan
                                        </span>
                                        @break
                                    @case('ditolak')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900 text-red-200">
                                            Ditolak
                                        </span>
                                        @break
                                    @case('dibatalkan')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-700 text-gray-300">
                                            Dibatalkan
                                        </span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-700 text-gray-300">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($peminjaman->denda)
                                    <span class="text-red-400 font-medium">
                                        Rp {{ number_format($peminjaman->denda->jumlah, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('member.riwayat.detail', $peminjaman->id) }}" 
                                       class="bg-blue-900 hover:bg-blue-800 text-blue-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                       title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination - Dark Version -->
            <div class="bg-gray-800 px-4 py-3 border-t border-gray-700 sm:px-6">
                {{ $peminjamans->links() }}
            </div>
        @else
            <!-- Empty State - Dark Version -->
            <div class="text-center py-12">
                @if($search || $status)
                    <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-white">Tidak ada hasil ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-400">Tidak ada riwayat peminjaman yang sesuai dengan kriteria pencarian Anda.</p>
                    <div class="mt-6">
                        <button wire:click="resetFilters" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset Pencarian
                        </button>
                    </div>
                @else
                    <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-white">Belum ada riwayat peminjaman</h3>
                    <p class="mt-1 text-sm text-gray-400">Anda belum memiliki riwayat peminjaman buku.</p>
                    <div class="mt-6">
                        <a href="{{ route('member.dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kembali ke Dashboard
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>