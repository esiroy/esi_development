<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="My Tutor, {{ $title ?? '' }}">
    <meta name="keywords" content="Tutor, Japan, Lesson, {{ $title ?? '' }}">
    <title>{{ config('app.name', 'My Tutor') }} </title>
 

    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
    @yield('styles')
    @yield('scripts')
</head>
<body>
    <div id="app">
        <main class="py-0">
            @yield('content')
        </main>
    </div>
    
</body>
</html>