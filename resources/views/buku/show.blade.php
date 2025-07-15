@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header with Breadcrumb - Dark Version -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Buku</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.buku.index') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-white">Buku</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-white">Detail</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.buku.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.buku.edit', $buku->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Buku
                </a>
            </div>
        </div>

        <!-- Book Detail Card - Dark Version -->
        <div class="bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-700">
            <!-- Book Header with Cover -->
            <div class="md:flex">
                <div class="md:w-1/3 bg-gray-900 p-8 flex items-center justify-center">
                    @if($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" 
                             class="h-80 w-auto object-contain rounded-lg shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                    @else
                        <div class="h-80 w-64 bg-gray-700 rounded-lg flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                            </svg>
                            <span class="mt-2 text-sm">No Cover Available</span>
                        </div>
                    @endif
                </div>
                
                <div class="md:w-2/3 p-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ $buku->judul }}</h1>
                            <div class="mt-2 flex items-center">
                                <span class="bg-purple-900 text-purple-200 text-xs px-2 py-1 rounded-full">{{ $buku->tahun_terbit }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Book Metadata -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <!-- Information Card -->
                            <div class="bg-gray-700 p-5 rounded-xl border border-gray-600">
                                <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Informasi Buku
                                </h2>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kategori</p>
                                        <p class="mt-1 text-white font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                            {{ $buku->kategori->nama }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pengarang</p>
                                        <p class="mt-1 text-white font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $buku->pengarang->nama }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Card -->
                        <div class="bg-gray-700 p-5 rounded-xl border border-gray-600">
                            <h2 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                Deskripsi Buku
                            </h2>
                            <div class="prose prose-invert max-w-none text-gray-300">
                                {!! $buku->deskripsi ? nl2br(e($buku->deskripsi)) : '<p class="text-gray-400 italic">Tidak ada deskripsi tersedia</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan History Section -->
            <div class="border-t border-gray-700 px-8 py-6 bg-gray-900">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Peminjaman
                    </h2>
                    <span class="text-sm text-gray-400">Total: {{ $buku->peminjamans->count() }} transaksi</span>
                </div>

                @if($buku->peminjamans->isEmpty())
                    <div class="bg-blue-900/30 border border-blue-800 rounded-xl p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477 4.5 1.253" />
                        </svg>
                        <p class="mt-3 text-gray-300">Belum ada riwayat peminjaman untuk buku ini</p>
                        <p class="text-sm text-gray-500 mt-1">Peminjaman akan muncul di sini ketika buku dipinjam</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-lg border border-gray-700">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Pinjam</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Kembali</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach($buku->peminjamans as $peminjaman)
                                <tr class="hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($peminjaman->user->profile_photo_path)
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-600" src="{{ asset('storage/' . $peminjaman->user->profile_photo_path) }}" alt="{{ $peminjaman->user->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-700 border border-gray-600 flex items-center justify-center text-gray-300 font-medium">
                                                        {{ substr($peminjaman->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-white">{{ $peminjaman->user->name }}</div>
                                                <div class="text-sm text-gray-400">{{ $peminjaman->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $peminjaman->tanggal_kembali->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-900 text-yellow-200' : 
                                               ($peminjaman->status == 'dikembalikan' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200') }}">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition duration-150 ease-in-out flex items-center justify-end">
                                            Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection