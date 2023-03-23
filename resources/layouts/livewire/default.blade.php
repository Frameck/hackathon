<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        [x-cloak] { 
            display: none !important; 
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('scripts')

    <!-- Title -->
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
</head>

<body class="antialiased">
    {{ $slot }}

    @livewireScripts
    @livewire('notifications')
</body>
</html>