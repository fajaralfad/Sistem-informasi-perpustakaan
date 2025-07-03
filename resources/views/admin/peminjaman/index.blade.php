@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Peminjaman</h1>
            <p class="text-gray-600">Kelola peminjaman dan booking buku perpustakaan</p>
        </div>
        <a href="{{ route('admin.peminjaman.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200 shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Peminjaman
        </a>
    </div>

    <!-- Livewire Component -->
    <livewire:peminjaman-search />
</div>
@endsection