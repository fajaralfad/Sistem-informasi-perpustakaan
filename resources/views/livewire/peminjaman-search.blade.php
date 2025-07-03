<div>
    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex-1 flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari peminjaman</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               id="search" 
                               name="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               placeholder="Cari nama user, email, judul buku, atau ISBN...">
                    </div>
                </div>
                
                <!-- Filter Status -->
                <div class="flex items-center gap-2">
                    <select wire:model.live="status" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="aktif">Dipinjam</option>
                        <option value="booking">Booking</option>
                        <option value="dikembalikan">Dikembalikan</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div class="flex items-center gap-2">
                    <input wire:model.live="tanggal_dari" 
                           type="date" 
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Dari tanggal">
                    <span class="text-gray-500">s/d</span>
                    <input wire:model.live="tanggal_sampai" 
                           type="date" 
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Sampai tanggal">
                </div>

                <!-- Reset Filter Button -->
                <button wire:click="resetFilters" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </button>
            </div>
            
            <div class="text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $peminjamans->total() }}</span> peminjaman
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed top-4 right-4 z-50">
        <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat...
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        @if($peminjamans->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="peminjamanTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                User
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                                </svg>
                                Buku
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Pinjam
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Kembali
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($peminjamans as $peminjaman)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $peminjaman->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $peminjaman->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $peminjaman->buku->judul }}</div>
                                    <div class="text-sm text-gray-500">{{ $peminjaman->buku->pengarang->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $peminjaman->tanggal_pinjam->format('H:i') }}</div>
                            @if($peminjaman->status === 'booking')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Akan dipinjam
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $peminjaman->tanggal_kembali->format('H:i') }}</div>
                            @if($peminjaman->diperpanjang ?? false)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 9.293 10.793a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Diperpanjang
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($peminjaman->status == 'booking' ? 'bg-blue-100 text-blue-800' :
                                   ($peminjaman->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                   title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                                
                                @if($peminjaman->status === 'dipinjam')
                                    <form action="{{ route('admin.peminjaman.kembalikan', $peminjaman->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                                title="Kembalikan Buku">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Kembalikan
                                        </button>
                                    </form>
                                    
                                    <button onclick="openPerpanjangModal({{ $peminjaman->id }})" 
                                            class="bg-purple-100 hover:bg-purple-200 text-purple-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                            title="Perpanjang Peminjaman">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Perpanjang
                                    </button>

                                <!-- Jika status pending, tampilkan tombol konfirmasi/tolak -->
                                @elseif($peminjaman->status === 'pending')
                                    <!-- Tombol Konfirmasi Pending -->
                                    <form action="{{ route('admin.peminjaman.confirm', $peminjaman->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                                title="Konfirmasi Peminjaman"
                                                onclick="return confirm('Konfirmasi peminjaman buku ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Konfirmasi
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Tolak Pending -->
                                    <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                                title="Tolak Peminjaman"
                                                onclick="return confirm('Tolak peminjaman buku ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Tolak
                                        </button>
                                    </form>

                                @elseif($peminjaman->status === 'booking' && $peminjaman->tanggal_pinjam <= now())
                                    <button onclick="openConfirmTakenModal(
                                        '{{ $peminjaman->id }}',
                                        '{{ $peminjaman->buku->judul }}',
                                        '{{ $peminjaman->buku->pengarang->nama ?? '-' }}',
                                        '{{ $peminjaman->user->name }}',
                                        '{{ $peminjaman->user->email }}',
                                        '{{ $peminjaman->tanggal_pinjam->format('d M Y H:i') }}'
                                    )" 
                                    class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                    title="Konfirmasi Pengambilan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Konfirmasi Ambil
                                    </button>

                                @elseif($peminjaman->status === 'booking')
                                    <!-- Tombol Batalkan Booking -->
                                    <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200" 
                                                title="Batalkan Booking"
                                                onclick="return confirm('Batalkan booking buku ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Batalkan
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline" onsubmit="return confirmDelete('{{ $peminjaman->user->name }}', '{{ $peminjaman->buku->judul }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                            title="Hapus Peminjaman">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $peminjamans->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            @if($status == 'booking')
                <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pemesanan buku</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada booking buku yang ditemukan.</p>
            @else
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
                <p class="mt-1 text-sm text-gray-500">
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
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                @else
                    <a href="{{ route('admin.peminjaman.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
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

<!-- Modal Perpanjang -->
<div id="perpanjangModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Perpanjang Peminjaman</h3>
            <button onclick="closePerpanjangModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="perpanjangForm" method="POST">
            @csrf
            <div class="mb-6">
                <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Hari Perpanjangan</label>
                <input type="number" name="hari" id="hari" min="1" max="14" value="7" 
                       class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-sm text-gray-500">Maksimal 14 hari perpanjangan</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePerpanjangModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Perpanjang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Pengambilan Buku -->
<div id="confirmTakenModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pengambilan Buku</h3>
            <button onclick="closeConfirmTakenModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-4">
            <div class="flex items-center gap-4 mb-3">
                <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h4 id="bookTitle" class="font-medium text-gray-900"></h4>
                    <p id="bookAuthor" class="text-sm text-gray-500"></p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h4 id="userName" class="font-medium text-gray-900"></h4>
                    <p id="userEmail" class="text-sm text-gray-500"></p>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
                    <p id="bookingDate" class="text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengambilan</label>
                    <input type="datetime-local" id="pickupDate" 
                           class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                           value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>
        </div>
        
        <form id="confirmTakenForm" method="POST">
            @csrf
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeConfirmTakenModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    Konfirmasi Pengambilan
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Scripts -->
    <script>
        function openPerpanjangModal(peminjamanId) {
            document.getElementById('perpanjangForm').action = `{{ route('admin.peminjaman.index') }}/${peminjamanId}/perpanjang`;
            document.getElementById('perpanjangModal').classList.remove('hidden');
            document.getElementById('perpanjangModal').classList.add('flex');
        }

        function closePerpanjangModal() {
            document.getElementById('perpanjangModal').classList.add('hidden');
            document.getElementById('perpanjangModal').classList.remove('flex');
        }

        function openConfirmTakenModal(peminjamanId, bookTitle, bookAuthor, userName, userEmail, bookingDate) {
        // Set data peminjaman ke modal
        document.getElementById('bookTitle').textContent = bookTitle;
        document.getElementById('bookAuthor').textContent = bookAuthor;
        document.getElementById('userName').textContent = userName;
        document.getElementById('userEmail').textContent = userEmail;
        document.getElementById('bookingDate').textContent = bookingDate;
        
        // Set form action
        document.getElementById('confirmTakenForm').action = `/admin/peminjaman/${peminjamanId}/confirm-taken`;
        
        // Tampilkan modal
        document.getElementById('confirmTakenModal').classList.remove('hidden');
        document.getElementById('confirmTakenModal').classList.add('flex');
         }

        // Fungsi untuk menutup modal konfirmasi pengambilan
        function closeConfirmTakenModal() {
            document.getElementById('confirmTakenModal').classList.add('hidden');
            document.getElementById('confirmTakenModal').classList.remove('flex');
        }

        // Close modal ketika click outside
        document.getElementById('confirmTakenModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmTakenModal();
            }
        });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmTakenModal();
        }
    });

        // Function untuk konfirmasi hapus
        function confirmDelete(userName, bookTitle) {
            return confirm(`Apakah Anda yakin ingin menghapus data peminjaman buku "${bookTitle}" oleh ${userName}?\n\nTindakan ini tidak dapat dibatalkan.`);
        }

        // Close modal ketika click outside
        document.getElementById('perpanjangModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePerpanjangModal();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePerpanjangModal();
            }
        });

        // Auto-focus search input on page load
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
        });

        // Enhanced table functionality
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('peminjamanTable');
            if (table) {
                // Add hover effect enhancement
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

        // Konfirmasi untuk aksi-aksi penting
        document.addEventListener('DOMContentLoaded', function() {
            // Konfirmasi kembalikan buku
            const kembalikanButtons = document.querySelectorAll('form[action*="kembalikan"] button[type="submit"]');
            kembalikanButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin menandai buku ini sebagai dikembalikan?')) {
                        e.preventDefault();
                    }
                });
            });

            // Konfirmasi konfirmasi booking
            const confirmButtons = document.querySelectorAll('form[action*="confirm"] button[type="submit"]');
            confirmButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin mengkonfirmasi booking ini?')) {
                        e.preventDefault();
                    }
                });
            });

            // Konfirmasi tolak booking
            const rejectButtons = document.querySelectorAll('form[action*="reject"] button[type="submit"]');
            rejectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin menolak booking ini?')) {
                        e.preventDefault();
                    }
                });
            });

            // Konfirmasi batalkan booking
            const batalkanButtons = document.querySelectorAll('form[action*="destroy"] button[type="submit"]:not([onclick])');
            batalkanButtons.forEach(button => {
                const form = button.closest('form');
                if (form.querySelector('svg path[d*="M6 18L18 6M6 6l12 12"]')) {
                    button.addEventListener('click', function(e) {
                        if (!confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
                            e.preventDefault();
                        }
                    });
                }
            });
        });

        // Loading state management
        document.addEventListener('livewire:load', function() {
            // Show loading indicator when Livewire is making requests
            window.addEventListener('beforeunload', function() {
                const loadingIndicator = document.querySelector('[wire\\:loading]');
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'block';
                }
            });
        });

        // Print functionality (bonus feature)
        function printTable() {
            const printWindow = window.open('', '_blank');
            const table = document.getElementById('peminjamanTable').outerHTML;
            const styles = `
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .no-print { display: none; }
                </style>
            `;
            
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Data Peminjaman Buku</title>
                        ${styles}
                    </head>
                    <body>
                        <h2>Data Peminjaman Buku</h2>
                        <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID')}</p>
                        ${table}
                    </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
        }

        // Export functionality (bonus feature)
        function exportToCSV() {
            const table = document.getElementById('peminjamanTable');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            for (let i = 0; i < rows.length; i++) {
                const row = [];
                const cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length - 1; j++) { // Exclude action column
                    let cellText = cols[j].innerText.replace(/\s+/g, ' ').trim();
                    row.push('"' + cellText + '"');
                }
                csv.push(row.join(','));
            }
            
            const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
            const downloadLink = document.createElement('a');
            downloadLink.download = `peminjaman_${new Date().toISOString().split('T')[0]}.csv`;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>

    <!-- Additional Styles for Better UX -->
    <style>
        /* Custom scrollbar */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Modal backdrop animation */
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }

        /* Responsive table enhancements */
        @media (max-width: 768px) {
            .responsive-table {
                font-size: 14px;
            }
            
            .responsive-table th,
            .responsive-table td {
                padding: 8px 4px;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
</div>