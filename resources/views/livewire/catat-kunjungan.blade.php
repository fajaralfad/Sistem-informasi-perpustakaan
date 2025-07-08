<div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-700">
    <h3 class="text-lg font-medium text-white mb-4">Catat Kunjungan Masuk</h3>
    
    <form wire:submit.prevent="catatMasuk">
        <!-- User Search -->
        <div class="mb-4">
            <label for="search" class="block text-sm font-medium text-gray-300 mb-1">Cari Anggota</label>
            <div class="relative">
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-lg leading-5 bg-gray-700 text-gray-700 placeholder-gray-400 focus:outline-none focus:placeholder-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Cari berdasarkan nama, dan email"
                       autocomplete="off">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                </div>
            </div>
            
            <!-- User Suggestions -->
            @if($search && !$user_id)
                <div class="mt-1 bg-gray-700 rounded-lg shadow-lg z-10">
                    @if(count($users) > 0)
                        <ul class="py-1">
                            @foreach($users as $user)
                                <li wire:click="selectUser({{ $user->id }})" 
                                    class="px-4 py-2 hover:bg-gray-600 cursor-pointer flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($user->profile_photo_path)
                                            <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-300">{{ $user->email }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-4 py-2 text-sm text-gray-400">Tidak ditemukan anggota</div>
                    @endif
                </div>
            @endif
            
            @error('user_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <!-- Tujuan -->
        <div class="mb-4">
            <label for="tujuan" class="block text-sm font-medium text-gray-300 mb-1">Tujuan Kunjungan</label>
            <select wire:model="tujuan" 
                    class="block w-full px-4 py-2 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 text-white">
                <option value="">Pilih Tujuan</option>
                @foreach($tujuanOptions as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('tujuan') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <!-- Kegiatan -->
        <div class="mb-4">
            <label for="kegiatan" class="block text-sm font-medium text-gray-300 mb-1">Kegiatan (Opsional)</label>
            <input type="text" 
                   wire:model="kegiatan"
                   class="block w-full px-4 py-2 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 text-white">
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Catat Kunjungan Masuk
            </button>
        </div>
    </form>
</div>