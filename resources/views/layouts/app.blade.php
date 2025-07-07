<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">

    @livewireStyles

    <style>
        /* Tambahkan style ini untuk memastikan grid bekerja */
        .book-grid-container {
            width: 100%;
            overflow-x: auto; /* Untuk mobile jika perlu scroll horizontal */
        }
        
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            width: 100%;
        }
        
        @media (min-width: 640px) {
            .book-grid {
                grid-template-columns: repeat(2, minmax(200px, 1fr));
            }
        }
        
        @media (min-width: 768px) {
            .book-grid {
                grid-template-columns: repeat(3, minmax(200px, 1fr));
            }
        }
        
        @media (min-width: 1024px) {
            .book-grid {
                grid-template-columns: repeat(4, minmax(200px, 1fr));
            }
        }
        
        .book-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Loading Indicator -->
    <div class="livewire-loading" id="livewire-loading-bar" style="display:none; height: 4px; background: #3b82f6;"></div>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col justify-between">
        <div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="mb-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-center py-6 text-sm text-gray-600 dark:text-gray-300">
            <div class="max-w-7xl mx-auto px-4 flex flex-col items-center">
                <img src="{{ asset('images/webphada.png') }}" alt="Logo" class="h-20 w-20 object-contain mb-2">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                <p>Kejaksaan Negeri Bandar Lampung</p>
            </div>
        </footer>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            let timer;

            Livewire.hook('request', ({ respond, succeed }) => {
                timer = setTimeout(() => {
                    document.getElementById('livewire-loading-bar').style.display = 'block';
                }, 300);

                respond((response) => {
                    clearTimeout(timer);
                    return response;
                });

                succeed((response) => {
                    clearTimeout(timer);
                    document.getElementById('livewire-loading-bar').style.display = 'none';
                    return response;
                });
            });

            Livewire.hook('request.failed', () => {
                clearTimeout(timer);
                document.getElementById('livewire-loading-bar').style.display = 'none';
            });
        });

        // Toast notification handler
        Livewire.on('show-toast', (data) => {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg flex items-center ${
                data.type === 'success' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200'
            }`;
            
            const icon = document.createElement('svg');
            icon.className = 'w-5 h-5 mr-2';
            icon.fill = 'none';
            icon.viewBox = '0 0 24 24';
            icon.stroke = 'currentColor';
            
            const path = document.createElement('path');
            path.strokeLinecap = 'round';
            path.strokeLinejoin = 'round';
            path.strokeWidth = '2';
            
            if (data.type === 'success') {
                path.setAttribute('d', 'M5 13l4 4L19 7');
            } else {
                path.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            }
            
            icon.appendChild(path);
            toast.appendChild(icon);
            
            const message = document.createElement('span');
            message.textContent = data.message;
            toast.appendChild(message);
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.transition = 'opacity 0.5s ease-out';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        });
    </script>

    <!-- Extra Scripts -->
    @stack('scripts')
</body>
</html>