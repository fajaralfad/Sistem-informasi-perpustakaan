@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ isset($anggota) ? 'Edit' : 'Tambah' }} Anggota</h1>
    
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ isset($anggota) ? route('anggota.update', $anggota->id) : route('anggota.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($anggota))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto -->
                <div class="md:col-span-2">
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Anggota</label>
                    
                    <!-- Preview Foto -->
                    <div class="mb-4">
                        <div id="foto-preview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden">
                            @if(isset($anggota) && $anggota->foto)
                                <img src="{{ asset('storage/anggota/' . $anggota->foto) }}" alt="Foto {{ $anggota->nama }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center text-gray-400">
                                    <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-sm">Preview Foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <input type="file" name="foto" id="foto" accept="image/*" 
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.</p>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap" autofocus required
                           value="{{ old('nama', $anggota->nama ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="contoh@email.com" required
                           value="{{ old('email', $anggota->email ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telepon" id="telepon" placeholder="08xxxxxxxxxx" required
                           value="{{ old('telepon', $anggota->telepon ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('telepon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Daftar -->
                <div>
                    <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700">Tanggal Daftar</label>
                    <input type="date" name="tanggal_daftar" id="tanggal_daftar" required
                           value="{{ old('tanggal_daftar', isset($anggota) && $anggota->tanggal_daftar ? $anggota->tanggal_daftar->format('Y-m-d') : now()->format('Y-m-d')) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_daftar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" required
                              placeholder="Masukkan alamat lengkap"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol -->
            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    {{ isset($anggota) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('anggota.index') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Preview foto sebelum upload
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('foto-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection