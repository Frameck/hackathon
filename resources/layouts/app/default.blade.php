<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Scripts and Styles yield -->
    @yield('scripts')
    @yield('styles')

    <!-- Assets -->
    @vite(['resources/scss/app.scss', 'resources/css/app.css', 'resources/js/app.js'])

    <!-- Title -->
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
</head>
<body class="flex flex-col h-screen" id="body">
    <x-navbar />

    <div class="container flex-grow py-10 px-7 md:px-0">
        @yield('main')
    </div>

    <x-footer />
</body>
</html>
