@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Kategori</h1>
        <p class="text-gray-600">Perbarui informasi kategori "{{ $kategori->nama }}"</p>
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
                    <a href="{{ route('admin.kategori.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Kategori</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Edit Informasi Kategori</h2>
                        <p class="text-amber-100 text-sm">Perbarui informasi kategori di bawah ini</p>
                    </div>
                </div>
            </div>

            <!-- Current Info Display -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Kategori saat ini:</p>
                        <p class="font-semibold text-gray-900">{{ $kategori->nama }}</p>
                        @if($kategori->deskripsi)
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($kategori->deskripsi, 100) }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Dibuat:</p>
                        <p class="text-sm text-gray-700">{{ $kategori->created_at->format('d M Y, H:i') }}</p>
                        @if($kategori->updated_at != $kategori->created_at)
                            <p class="text-xs text-gray-500 mt-1">Diperbarui:</p>
                            <p class="text-sm text-gray-700">{{ $kategori->updated_at->format('d M Y, H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Nama Kategori -->
                <div class="mb-6">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Nama Kategori
                            <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <input type="text" 
                    name="nama" 
                    id="nama" 
                    value="{{ old('nama') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="Masukkan nama kategori (contoh: Fiksi, Non-Fiksi, Sejarah, dll.)"
                    required>
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Deskripsi
                            <span class="text-gray-400 text-xs ml-2">(Opsional)</span>
                        </span>
                    </label>
                    <textarea name="deskripsi" 
                              id="deskripsi" 
                    rows="4"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="Masukkan deskripsi kategori untuk memberikan informasi lebih detail...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Deskripsi akan membantu pengguna memahami jenis buku yang termasuk dalam kategori ini.
                    </p>
                </div>

                <!-- Required Fields Note -->
                <div class="mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-amber-800">Peringatan</h3>
                            <p class="text-sm text-amber-700 mt-1">
                                Perubahan nama kategori akan mempengaruhi semua buku yang terkait dengan kategori ini. 
                                Pastikan perubahan sudah sesuai sebelum menyimpan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                @if(isset($kategori->buku_count))
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Statistik Kategori</h3>
                            <p class="text-sm text-blue-700 mt-1">
                                Kategori ini saat ini digunakan oleh <strong>{{ $kategori->buku_count ?? 0 }} buku</strong> di perpustakaan.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.kategori.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                onclick="resetForm()"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 font-medium transition-all duration-200 inline-flex items-center shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Perbarui Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>

@push('scripts')
<script>
    // Store original values for reset functionality
    const originalValues = {
        nama: '{{ $kategori->nama }}',
        deskripsi: '{{ $kategori->deskripsi ?? '' }}'
    };

    // Auto-focus pada field nama kategori
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('nama').focus();
        
        // Select text for easier editing
        document.getElementById('nama').select();
    });

    // Reset form to original values
    function resetForm() {
        document.getElementById('nama').value = originalValues.nama;
        document.getElementById('deskripsi').value = originalValues.deskripsi;
        
        // Remove any validation styling
        document.getElementById('nama').classList.remove('border-green-500', 'border-red-500');
        document.getElementById('nama').classList.add('border-gray-300');
        document.getElementById('deskripsi').classList.remove('border-green-500', 'border-red-500');
        document.getElementById('deskripsi').classList.add('border-gray-300');
    }

    // Preview changes while typing
    document.getElementById('nama').addEventListener('input', function(e) {
        const value = e.target.value;
        const original = originalValues.nama;
        
        if (value !== original && value.length > 0) {
            e.target.classList.add('border-amber-500');
            e.target.classList.remove('border-gray-300', 'border-red-500');
        } else if (value === original) {
            e.target.classList.remove('border-amber-500', 'border-red-500');
            e.target.classList.add('border-gray-300');
        } else {
            e.target.classList.remove('border-amber-500', 'border-gray-300');
            e.target.classList.add('border-red-500');
        }
    });

    document.getElementById('deskripsi').addEventListener('input', function(e) {
        const value = e.target.value;
        const original = originalValues.deskripsi;
        
        if (value !== original) {
            e.target.classList.add('border-amber-500');
            e.target.classList.remove('border-gray-300');
        } else {
            e.target.classList.remove('border-amber-500');
            e.target.classList.add('border-gray-300');
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key closes modal
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
        
        // Ctrl+S saves form
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.querySelector('form').submit();
        }
        
        // Ctrl+R resets form
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            resetForm();
        }
    });
</script>
@endpush
@endsection