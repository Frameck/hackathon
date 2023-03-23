<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">

    <!-- Styles -->
    <link href="{{ base_path('public/css/app.css') }}" rel="stylesheet">
    {{-- <link href="{{ base_path('public/css/[working]_app_main.css') }}" rel="stylesheet"> --}}

    <style type="text/css">
        .body {
            max-width: 481.9pt;
            padding: 72px
        }
        p {
            margin: 0;
            font-size: 12pt;
        }
    </style>
</head>
<body class="body">
    <main>
        @yield('main')
    </main>
</body>
</html>
