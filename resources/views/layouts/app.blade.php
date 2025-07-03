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
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        /* Loading indicator styles */
        .livewire-loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background-color: rgba(99, 102, 241, 0.2);
            z-index: 9999;
            display: none;
        }
        .livewire-loading:after {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgb(99, 102, 241);
            animation: livewireLoading 1.5s ease-in-out infinite;
        }
        @keyframes livewireLoading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- Loading Indicator -->
    <div class="livewire-loading" id="livewire-loading-bar"></div>

    <div class="app-wrapper fade-in">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Content Wrapper -->
        <div class="pt-16 flex"> <!-- pt-16 memberi ruang di bawah navbar fixed -->
            <div class="flex-1">
                @isset($header)
                    <header class="shadow relative z-20 bg-white">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="relative">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            // Modern Livewire 3+ approach
            let timer;
            
            Livewire.hook('request', ({ uri, options, payload, respond, succeed }) => {
                // Show loading after 300ms delay (for fast requests)
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
    </script>

    <!-- Extra Scripts -->
    @stack('scripts')
</body>
</html>