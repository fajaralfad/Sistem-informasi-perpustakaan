<div>
    <div class="flex justify-end gap-3 mb-4">
    <a href="{{ route('admin.peminjaman.export.excel') }}?search={{ $search }}&status={{ $status }}&tanggal_dari={{ $tanggal_dari }}&tanggal_sampai={{ $tanggal_sampai }}" 
    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export Excel
    </a>
    
    <a href="{{ route('admin.peminjaman.export.pdf') }}?search={{ $search }}&status={{ $status }}&tanggal_dari={{ $tanggal_dari }}&tanggal_sampai={{ $tanggal_sampai }}" 
    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export PDF
    </a>
    </div>

    <!-- Search and Filter - Clean Modified Version -->
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 mb-6 border border-gray-700">
        <div class="space-y-4">
            <!-- Search Bar -->
            <div class="w-full">
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" 
                        type="text" 
                        id="search" 
                        name="search"
                        class="block w-full pl-10 pr-4 py-3 border border-gray-600 rounded-lg bg-gray-700 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                        placeholder="Cari nama user, email, judul buku, atau ISBN...">
                </div>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select wire:model.live="status" 
                            class="w-full px-4 py-2.5 border border-gray-600 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="aktif">Dipinjam</option>
                        <option value="booking">Booking</option>
                        <option value="dikembalikan">Dikembalikan</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Dari Tanggal</label>
                    <input wire:model.live="tanggal_dari" 
                        type="date" 
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-lg bg-gray-700 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sampai Tanggal</label>
                    <input wire:model.live="tanggal_sampai" 
                        type="date" 
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-lg bg-gray-700 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <button wire:click="resetFilters" 
                            class="w-full bg-gray-600 hover:bg-gray-500 text-white px-4 py-2.5 rounded-lg font-medium inline-flex items-center justify-center transition-all duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>

            <!-- Total Counter -->
            <div class="pt-4 border-t border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Total: <span class="font-semibold text-white">{{ $peminjamans->total() }}</span> peminjaman
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-400">Filter aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Memuat data peminjaman...</p>
    </div>

    <!-- Table - Dark Version -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700">
        @if($peminjamans->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700" id="peminjamanTable">
                <thead class="bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                User
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                                </svg>
                                Buku
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                ISBN
                            </div>
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
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($peminjamans as $peminjaman)
                    <tr class="hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-900 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $peminjaman->user->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $peminjaman->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-green-900 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $peminjaman->buku->judul }}</div>
                                    <div class="text-sm text-gray-400">{{ $peminjaman->buku->pengarang->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $peminjaman->buku->isbn }}</div>
                            <div class="text-sm text-gray-400">Tahun: {{ $peminjaman->buku->tahun_terbit }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                            <div class="text-sm text-gray-400">{{ $peminjaman->tanggal_pinjam->format('H:i') }}</div>
                            @if($peminjaman->status === 'booking')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-900 text-blue-200 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Akan dipinjam
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</div>
                            <div class="text-sm text-gray-400">{{ $peminjaman->tanggal_kembali->format('H:i') }}</div>
                            @if($peminjaman->diperpanjang ?? false)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-900 text-purple-200 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 9.293 10.793a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Diperpanjang
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-900 text-yellow-200' : 
                                   ($peminjaman->status == 'booking' ? 'bg-blue-900 text-blue-200' :
                                   ($peminjaman->status == 'dikembalikan' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200')) }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" 
                                   class="bg-blue-900 hover:bg-blue-800 text-blue-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                   title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                                
                                @if($peminjaman->status === 'dipinjam')
                                    <button onclick="openReturnModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->user->name }}')" 
                                            class="bg-green-900 hover:bg-green-800 text-green-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                            title="Kembalikan Buku">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Kembalikan
                                    </button>
                                    
                                    <button onclick="openExtendModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->user->name }}', '{{ $peminjaman->tanggal_kembali->format('d M Y') }}')" 
                                            class="bg-purple-900 hover:bg-purple-800 text-purple-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                            title="Perpanjang Peminjaman">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Perpanjang
                                    </button>

                                @elseif($peminjaman->status === 'pending')
                                    <button onclick="openConfirmModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->user->name }}')" 
                                            class="bg-green-900 hover:bg-green-800 text-green-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                            title="Konfirmasi Peminjaman">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Konfirmasi
                                    </button>
                                    
                                    <button onclick="openRejectModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->user->name }}')" 
                                            class="bg-red-900 hover:bg-red-800 text-red-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                            title="Tolak Peminjaman">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>

                               @elseif($peminjaman->status === 'booking' && $peminjaman->tanggal_pinjam <= now())
                                <button onclick="openConfirmTakenModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->buku->pengarang->nama ?? '-' }}', '{{ $peminjaman->user->name }}', '{{ $peminjaman->user->email }}', '{{ $peminjaman->tanggal_pinjam->format('d M Y H:i') }}')" 
                                        class="bg-green-900 hover:bg-green-800 text-green-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                        title="Konfirmasi Pengambilan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Konfirmasi Ambil
                                </button>

                                @elseif($peminjaman->status === 'booking')
                                    <button onclick="openCancelBookingModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->user->name }}')" 
                                            class="bg-red-900 hover:bg-red-800 text-red-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                            title="Batalkan Booking">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Batalkan
                                    </button>
                                @endif

                                <button onclick="openDeleteModal({{ $peminjaman->id }}, '{{ $peminjaman->buku->judul }}', '{{ $peminjaman->user->name }}')" 
                                        class="bg-red-900 hover:bg-red-800 text-red-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                        title="Hapus Peminjaman">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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

        <!-- Pagination - Dark Version -->
        <div class="bg-gray-800 px-6 py-4 border-t border-gray-700">
            {{ $peminjamans->links() }}
        </div>
        @else
        <!-- Empty State - Dark Version -->
        <div class="text-center py-12">
            @if($status == 'booking')
                <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">Belum ada pemesanan buku</h3>
                <p class="mt-1 text-sm text-gray-400">Tidak ada booking buku yang ditemukan.</p>
            @else
                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">Belum ada peminjaman</h3>
                <p class="mt-1 text-sm text-gray-400">
                    @if(!empty($search))
                        Tidak ada peminjaman yang ditemukan untuk pencarian "{{ $search }}".
                    @else
                        Mulai dengan menambahkan peminjaman buku pertama.
                    @endif
                </p>
            @endif
            <div class="mt-6">
                @if(!empty($search) || !empty($status) || !empty($tanggal_dari) || !empty($tanggal_sampai))
                    <button wire:click="resetFilters" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                @else
                    <a href="{{ route('admin.peminjaman.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Peminjaman
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Delete Confirmation -->
<div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Konfirmasi Hapus</h2>
        <p class="text-gray-300 text-center mb-6">
            Yakin ingin menghapus peminjaman buku <strong id="deleteBookTitle" class="text-white"></strong> oleh <strong id="deleteUserName" class="text-white"></strong>? 
            <br><span class="text-sm text-red-400">Tindakan ini tidak dapat dibatalkan.</span>
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Return Confirmation -->
<div id="returnModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Konfirmasi Pengembalian</h2>
        <p class="text-gray-300 text-center mb-6">
            Konfirmasi pengembalian buku <strong id="returnBookTitle" class="text-white"></strong> oleh <strong id="returnUserName" class="text-white"></strong>?
            <br><span class="text-sm text-green-400">Buku akan dikembalikan ke stok perpustakaan.</span>
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeReturnModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                Batal
            </button>
            <form id="returnForm" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Ya, Kembalikan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Extend Confirmation -->
<div id="extendModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-purple-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Perpanjang Peminjaman</h2>
        <div class="text-center mb-4">
            <p class="text-gray-300 mb-2">
                Buku: <strong id="extendBookTitle" class="text-white"></strong>
            </p>
            <p class="text-gray-300 mb-2">
                Peminjam: <strong id="extendUserName" class="text-white"></strong>
            </p>
            <p class="text-gray-400 text-sm">
                Tanggal kembali saat ini: <span id="extendCurrentDate" class="text-white"></span>
            </p>
        </div>
        <form id="extendForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="extendDays" class="block text-sm font-medium text-gray-300 mb-2">Jumlah Hari Perpanjangan</label>
                <input type="number" name="hari" id="extendDays" min="1" max="14" value="7" 
                    class="block w-full border border-gray-600 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-gray-700 text-black">
                <p class="mt-1 text-sm text-gray-400">Maksimal 30 hari perpanjangan</p>
            </div>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeExtendModal()" 
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Perpanjang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirm Pending -->
<div id="confirmModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Konfirmasi Peminjaman</h2>
        <p class="text-gray-300 text-center mb-6">
            Konfirmasi peminjaman buku <strong id="confirmBookTitle" class="text-white"></strong> oleh <strong id="confirmUserName" class="text-white"></strong>?
            <br><span class="text-sm text-green-400">Buku akan dipinjam kepada user ini.</span>
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                Batal
            </button>
            <form id="confirmForm" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Ya, Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject Pending -->
<div id="rejectModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Tolak Peminjaman</h2>
        <p class="text-gray-300 text-center mb-6">
            Tolak peminjaman buku <strong id="rejectBookTitle" class="text-white"></strong> oleh <strong id="rejectUserName" class="text-white"></strong>?
            <br><span class="text-sm text-red-400">Peminjaman akan dibatalkan.</span>
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeRejectModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                Batal
            </button>
            <form id="rejectForm" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Ya, Tolak
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cancel Booking -->
<div id="cancelBookingModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-orange-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Batalkan Booking</h2>
        <p class="text-gray-300 text-center mb-6">
            Batalkan booking buku <strong id="cancelBookTitle" class="text-white"></strong> oleh <strong id="cancelUserName" class="text-white"></strong>?
            <br><span class="text-sm text-orange-400">Booking akan dibatalkan dan buku kembali tersedia.</span>
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeCancelBookingModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                Batal
            </button>
            <form id="cancelBookingForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Ya, Batalkan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pengambilan Buku -->
<div id="confirmTakenModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-blue-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Konfirmasi Pengambilan Buku</h2>
        
        <div class="mb-4">
            <div class="flex items-center gap-4 mb-3 p-3 bg-gray-700 rounded-lg">
                <div class="flex-shrink-0 h-10 w-10 bg-green-900 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h4 id="takenBookTitle" class="font-medium text-white"></h4>
                    <p id="takenBookAuthor" class="text-sm text-gray-400"></p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 p-3 bg-gray-700 rounded-lg">
                <div class="flex-shrink-0 h-10 w-10 bg-purple-900 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h4 id="takenUserName" class="font-medium text-white"></h4>
                    <p id="takenUserEmail" class="text-sm text-gray-400"></p>
                </div>
            </div>
        </div>
        
        <form id="confirmTakenForm" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Booking</label>
                    <p id="takenBookingDate" class="text-sm text-white bg-gray-700 p-2 rounded"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Pengambilan</label>
                    <input type="datetime-local" id="takenPickupDate" name="pickup_date"
                        class="block w-full border border-gray-600 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-700 text-white"
                        value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>
            
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeConfirmTakenModal()" 
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Konfirmasi Pengambilan
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Scripts -->
    <script>
    // Global modal functions with proper display handling
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteModal(id, bookTitle, userName) {
            document.getElementById('deleteBookTitle').textContent = bookTitle;
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = `${window.location.pathname}/${id}`;
            showModal('deleteModal');
        }

        function closeDeleteModal() {
            hideModal('deleteModal');
        }

        function openReturnModal(id, bookTitle, userName) {
            document.getElementById('returnBookTitle').textContent = bookTitle;
            document.getElementById('returnUserName').textContent = userName;
            document.getElementById('returnForm').action = `${window.location.pathname}/${id}/kembalikan`;
            showModal('returnModal');
        }

        function closeReturnModal() {
            hideModal('returnModal');
        }

        function openExtendModal(id, bookTitle, userName, currentDate) {
            document.getElementById('extendBookTitle').textContent = bookTitle;
            document.getElementById('extendUserName').textContent = userName;
            document.getElementById('extendCurrentDate').textContent = currentDate;
            document.getElementById('extendForm').action = `${window.location.pathname}/${id}/perpanjang`;
            showModal('extendModal');
        }

        function closeExtendModal() {
            hideModal('extendModal');
        }

        function openConfirmModal(id, bookTitle, userName) {
            document.getElementById('confirmBookTitle').textContent = bookTitle;
            document.getElementById('confirmUserName').textContent = userName;
            document.getElementById('confirmForm').action = `${window.location.pathname}/${id}/confirm`;
            showModal('confirmModal');
        }

        function closeConfirmModal() {
            hideModal('confirmModal');
        }

        function openRejectModal(id, bookTitle, userName) {
            document.getElementById('rejectBookTitle').textContent = bookTitle;
            document.getElementById('rejectUserName').textContent = userName;
            document.getElementById('rejectForm').action = `${window.location.pathname}/${id}/reject`;
            showModal('rejectModal');
        }

        function closeRejectModal() {
            hideModal('rejectModal');
        }

        function openCancelBookingModal(id, bookTitle, userName) {
            document.getElementById('cancelBookTitle').textContent = bookTitle;
            document.getElementById('cancelUserName').textContent = userName;
            document.getElementById('cancelBookingForm').action = `${window.location.pathname}/${id}`;
            showModal('cancelBookingModal');
        }

        function closeCancelBookingModal() {
            hideModal('cancelBookingModal');
        }

        function openConfirmTakenModal(id, bookTitle, bookAuthor, userName, userEmail, bookingDate) {
            document.getElementById('takenBookTitle').textContent = bookTitle;
            document.getElementById('takenBookAuthor').textContent = bookAuthor;
            document.getElementById('takenUserName').textContent = userName;
            document.getElementById('takenUserEmail').textContent = userEmail;
            document.getElementById('takenBookingDate').textContent = bookingDate;
            document.getElementById('confirmTakenForm').action = `/admin/peminjaman/${id}/confirm-taken`;
            showModal('confirmTakenModal');
        }

        function closeConfirmTakenModal() {
            hideModal('confirmTakenModal');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            const modals = ['deleteModal', 'returnModal', 'extendModal', 'confirmModal', 'rejectModal', 'cancelBookingModal', 'confirmTakenModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (e.target === modal) {
                    hideModal(modalId);
                }
            });
        });

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = ['deleteModal', 'returnModal', 'extendModal', 'confirmModal', 'rejectModal', 'cancelBookingModal', 'confirmTakenModal'];
                modals.forEach(modalId => {
                    hideModal(modalId);
                });
            }
        });

        // Enhanced table interactions
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }

            const table = document.getElementById('peminjamanTable');
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    row.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.01)';
                        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                        this.style.transition = 'all 0.2s ease';
                    });
                    
                    row.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1)';
                        this.style.boxShadow = 'none';
                    });
                });
            }
        });

        // Auto hide flash messages
        const alerts = document.querySelectorAll('.alert-error, .alert-success');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    </script>
</div>