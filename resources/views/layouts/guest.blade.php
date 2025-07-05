<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perpustakaan Kejaksaan Bandar Lampung') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">

    <style>
        .auth-card {
            width: 100%;
            max-width: 420px; /* Lebar horizontal dipertahankan */
            padding: 1rem; /* Padding diperkecil dari 2rem */
            margin: 0.5rem; /* Margin diperkecil */
        }
        
        .min-h-screen {
            min-height: 100vh;
            padding-top: 1rem;
            padding-bottom: 1rem; /* Mengurangi padding top container */
        }
        
        @media (max-width: 480px) {
            .auth-card {
                padding: 1rem; /* Lebih compact di mobile */
                margin: 0.25rem;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
   
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col sm:justify-center items-center pt-4 sm:pt-0"> <!-- pt dikurangi dari 6 ke 4 -->
        {{-- Card Form --}}
        <div class="auth-card bg-white dark:bg-gray-800 shadow-md rounded-lg">
            {{ $slot }}
        </div>
    </div>
    
</body>
</html>