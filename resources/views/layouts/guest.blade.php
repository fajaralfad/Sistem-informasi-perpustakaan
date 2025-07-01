<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perpustakaan Kejaksaan Bandar Lampung') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

    <div class="min-h-screen gradient-bg bg-pattern bg-pattern-blur pattern-default pattern-normal flex flex-col sm:justify-center items-center pt-6 sm:pt-0 animate-pattern-float">
        {{-- Logo Laravel (opsional) --}}
        {{-- 
        <div class="animate-floating">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-white opacity-80 hover:opacity-100 transition duration-300" />
            </a>
        </div> 
        --}}

        {{-- Card Form --}}
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 card-glass shadow-2xl overflow-hidden sm:rounded-2xl border border-white border-opacity-20 animate-fade-in">
            {{ $slot }}
        </div>

        {{-- Decorative Elements --}}
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-floating" style="animation-delay: 0.5s;"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-white bg-opacity-10 rounded-full animate-floating" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-5 w-12 h-12 bg-white bg-opacity-10 rounded-full animate-floating" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-20 right-20 w-8 h-8 bg-white bg-opacity-10 rounded-full animate-floating" style="animation-delay: 2s;"></div>
    </div>

</body>
</html>