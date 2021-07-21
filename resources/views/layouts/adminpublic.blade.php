<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'My Tutor') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>    
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>    
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" >
</head>
<body>
    <div>
        <main class="py-0">
            <div id="app">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
