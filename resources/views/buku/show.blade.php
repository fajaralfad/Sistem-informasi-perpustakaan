@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
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
                    <a href="{{ route('admin.buku.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Buku</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Book Detail Card -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
        <!-- Book Header with Cover -->
        <div class="md:flex">
            <div class="md:flex-shrink-0 bg-gray-100 p-6 flex items-center justify-center">
                @if($buku->cover)
                    <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" class="h-64 w-auto object-contain rounded-lg shadow-sm">
                @else
                    <div class="h-64 w-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                        </svg>
                    </div>
                @endif
            </div>
            <div class="p-8 flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $buku->judul }}</h1>
                        <p class="text-gray-600 mt-1">ISBN: {{ $buku->isbn }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.buku.edit', $buku->id) }}" 
                           class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50 transition duration-150"
                           title="Edit Buku">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Book Metadata -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Informasi Buku</h2>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Kategori</p>
                                    <p class="font-medium">{{ $buku->kategori->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Pengarang</p>
                                    <p class="font-medium">{{ $buku->pengarang->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tahun Terbit</p>
                                    <p class="font-medium">{{ $buku->tahun_terbit }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Stok Tersedia</p>
                                    <p class="font-medium">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                            {{ $buku->stok > 5 ? 'bg-green-100 text-green-800' : 
                                               ($buku->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $buku->stok }} buku
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Deskripsi</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! $buku->deskripsi ? nl2br(e($buku->deskripsi)) : '<p class="text-gray-400">Tidak ada deskripsi</p>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan History Section -->
        <div class="border-t border-gray-200 px-8 py-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Riwayat Peminjaman</h2>
                <span class="text-sm text-gray-500">Total: {{ $buku->peminjamans->count() }} transaksi</span>
            </div>

            @if($buku->peminjamans->isEmpty())
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                    </svg>
                    <p class="mt-2 text-gray-600">Belum ada riwayat peminjaman untuk buku ini</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($buku->peminjamans as $peminjaman)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($peminjaman->user->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $peminjaman->user->profile_photo_path) }}" alt="{{ $peminjaman->user->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                    {{ substr($peminjaman->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $peminjaman->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $peminjaman->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $peminjaman->tanggal_kembali->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($peminjaman->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-between">
            <a href="{{ route('admin.buku.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Buku
            </a>
            <div class="space-x-2">
                <a href="{{ route('admin.buku.edit', $buku->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Buku
                </a>
            </div>
        </div>
    </div>
</div>
@endsection