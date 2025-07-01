@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Peminjaman</h1>
            <p class="text-gray-600">Kelola peminjaman buku perpustakaan</p>
        </div>
        <a href="{{ route('admin.peminjaman.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200 shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Peminjaman
        </a>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Peminjaman</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

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
                        <input type="text" id="search" name="search" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               placeholder="Cari peminjaman..." onkeyup="searchTable()">
                    </div>
                </div>
                <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Filter
                    </button>
                </form>
            </div>
            <div class="text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $peminjamans->count() }}</span> peminjaman
            </div>
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
                                Anggota
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Denda
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
                                    <div class="text-sm font-medium text-gray-900">{{ $peminjaman->anggota->nama }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $peminjaman->anggota->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $peminjaman->tanggal_kembali->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($peminjaman->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                            @if($peminjaman->status == 'terlambat' && $peminjaman->denda)
                                <div class="mt-1">
                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                        {{ $peminjaman->denda->status_pembayaran ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $peminjaman->denda->status_pembayaran ? 'Denda Lunas' : 'Menunggu Pembayaran' }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($peminjaman->status == 'terlambat' && $peminjaman->denda)
                                <div class="text-sm font-medium text-red-600">
                                    Rp {{ number_format($peminjaman->denda->jumlah, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $peminjaman->denda->keterangan }}
                                </div>
                            @else
                                <div class="text-sm text-gray-500">-</div>
                            @endif
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
                                @endif

                                @if($peminjaman->status === 'terlambat' && $peminjaman->denda && !$peminjaman->denda->status_pembayaran)
                                    <a href="{{ route('admin.denda.bayar', $peminjaman->denda->id) }}" 
                                       class="bg-orange-100 hover:bg-orange-200 text-orange-800 px-3 py-2 rounded-lg text-xs font-medium inline-flex items-center transition-colors duration-200"
                                       title="Bayar Denda">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Bayar
                                    </a>
                                @endif
                                
                                <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline" onsubmit="return confirmDelete('{{ $peminjaman->anggota->nama }}', '{{ $peminjaman->buku->judul }}')">
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
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan peminjaman buku pertama.</p>
            <div class="mt-6">
                <a href="{{ route('admin.peminjaman.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Peminjaman
                </a>
            </div>
        </div>
        @endif
    </div>
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

<script>
// Search function
function searchTable() {
    const input = document.getElementById('search');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('peminjamanTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < td.length - 1; j++) { // -1 to exclude action column
            if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

// Modal functions
function openPerpanjangModal(peminjamanId) {
    document.getElementById('perpanjangForm').action = `/peminjaman/${peminjamanId}/perpanjang`;
    document.getElementById('perpanjangModal').classList.remove('hidden');
    document.getElementById('perpanjangModal').classList.add('flex');
}

function closePerpanjangModal() {
    document.getElementById('perpanjangModal').classList.add('hidden');
    document.getElementById('perpanjangModal').classList.remove('flex');
}

// Confirm delete function
function confirmDelete(namaAnggota, judulBuku) {
    return confirm(`Apakah Anda yakin ingin menghapus peminjaman:\n\nAnggota: ${namaAnggota}\nBuku: ${judulBuku}\n\nTindakan ini tidak dapat dibatalkan.`);
}

// Auto hide flash messages
document.addEventListener('DOMContentLoaded', function() {
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
});

// Close modal when clicking outside
document.getElementById('perpanjangModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePerpanjangModal();
    }
});
</script>
@endsection