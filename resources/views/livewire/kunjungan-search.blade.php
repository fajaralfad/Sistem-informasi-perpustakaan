<div>
    <!-- Di bagian atas, setelah search dan filter -->
    <div class="flex justify-end gap-3 mb-4">
        <a href="{{ route('admin.kunjungan.export.excel') }}?search={{ $search }}&status={{ $status }}&tanggal={{ $tanggal }}" 
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </a>
        
        <a href="{{ route('admin.kunjungan.export.pdf') }}?search={{ $search }}&status={{ $status }}&tanggal={{ $tanggal }}" 
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
                <label for="search" class="sr-only">Cari anggota</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-lg leading-5 bg-gray-700 text-gray-700 placeholder-gray-400 focus:outline-none focus:placeholder-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Cari berdasarkan nama, dan email">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            
            <!-- Status Filter -->
            <div class="flex items-center gap-3">
                <select wire:model.live="status" class="px-4 py-2 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 text-white">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="selesai">Selesai</option>
                </select>
                
                <!-- Date Filter -->
                <input type="date" wire:model.live="tanggal" 
                       class="px-4 py-2 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 text-gray-700">
                
                @if($search || $status || $tanggal)
                    <button wire:click="resetFilters" 
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Reset
                    </button>
                @endif
            </div>
            
            <!-- Total Count -->
            <div class="text-sm text-gray-400">
                Total: <span class="font-semibold text-white">{{ $kunjungans->total() }}</span> kunjungan
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Memuat data kunjungan...</p>
    </div>

    <!-- Kunjungan Table -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700" wire:loading.remove>
        @if($kunjungans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Anggota
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Waktu Masuk</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Waktu Keluar</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Durasi</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tujuan</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kegiatan</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($kunjungans as $kunjungan)
                        <tr class="hover:bg-gray-700 transition-colors duration-150">
                            <!-- Anggota Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($kunjungan->user->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $kunjungan->user->profile_photo_path) }}" alt="{{ $kunjungan->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-600 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $kunjungan->user->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $kunjungan->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Waktu Masuk Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ $kunjungan->waktu_masuk->format('d M Y H:i') }}</div>
                            </td>
                            
                            <!-- Waktu Keluar Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $kunjungan->waktu_keluar ? 'text-white' : 'text-gray-500' }}">
                                    {{ $kunjungan->waktu_keluar ? $kunjungan->waktu_keluar->format('d M Y H:i') : 'Masih di perpustakaan' }}
                                </div>
                            </td>
                            
                            <!-- Durasi Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ !$kunjungan->waktu_keluar ? 'text-yellow-400' : 'text-white' }}">
                                    @if($kunjungan->waktu_keluar)
                                        {{ $kunjungan->formatted_duration }} ({{ $kunjungan->duration_minutes }} menit)
                                    @else
                                        Masih di perpustakaan
                                    @endif
                                </div>
                            </td>
                                                        
                            <!-- Tujuan Column -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200">
                                    {{ $kunjungan->tujuan_formatted }}
                                </span>
                            </td>
                            
                            <!-- Kegiatan Column -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">{{ $kunjungan->kegiatan ?? '-' }}</div>
                            </td>
                            
                            <!-- Status Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($kunjungan->waktu_keluar)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-200">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200">
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    @if(!$kunjungan->waktu_keluar)
                                        <button wire:click="catatKeluar({{ $kunjungan->id }})" 
                                                class="bg-red-900 hover:bg-red-800 text-red-200 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                                title="Catat Keluar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Keluar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-800 px-4 py-3 border-t border-gray-700 sm:px-6">
                {{ $kunjungans->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">
                    @if($search || $status || $tanggal)
                        Tidak ada kunjungan yang ditemukan
                    @else
                        Belum ada data kunjungan
                    @endif
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    @if($search || $status || $tanggal)
                        Coba ubah kata kunci pencarian atau filter yang Anda gunakan.
                    @else
                        Kunjungan anggota akan muncul di sini setelah dicatat.
                    @endif
                </p>
                <div class="mt-6">
                    @if($search || $status || $tanggal)
                        <button wire:click="resetFilters" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset Filter
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>