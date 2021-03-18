<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>{{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(1))->replace('-', ' ') ) ?? '' }}</title>

    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>
<body class="bg-gray">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                   <img src="{{ url("images/title_full.png") }}" alt="{{ config('app.name', 'My Tutor') }}" alt="{{ config('app.name', 'My Tutor') }} administratrion panel">
                </a>
            </div>
        </nav>
    
        <div class="bg-lightblue">
          <div class="container px-0">
            <nav class="nav nav-pills flex-column flex-sm-row pt-3">
                &nbsp;
            </nav>
          </div>
        </div>


        <main class="main-container"  style="position:relative;top:-42px">
            <div class="container bg-white rounded-bottom" style="border-bottom-right-radius: 0.50rem !important; border-bottom-left-radius: 0.50rem !important;">
                @yield('content')
            </div>
        </main>

    </div>

    <script type="text/javascript">
        window.addEventListener('load', function () {

            //events here
        });
    </script>

    @yield('scripts')
</body>

</html>
