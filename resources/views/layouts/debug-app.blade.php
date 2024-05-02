<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>{{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(1))->replace('-', ' ') ) ?? '' }}</title>
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>

   <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') .'?id=1_0_0'  }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') .'?id=1_0_0'  }}">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') .'?id=1_0_0'  }}" defer ></script>
    <!-- Fonts -->
    
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    
 
    @yield('styles')

    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>
<body class="bg-gray">
    <div id="app">
        @yield('content')
        
    </div>
</body>

@yield('scripts')