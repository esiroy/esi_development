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
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
    @yield('styles')
</head>
<body>
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light shadow-sm bg-darkblue">

            <div class="container-fluid">

                <ul class="navbar-nav mr-auto">

                    <li class="pr-4">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-home fa-2x text-white"></i>
                        </a>
                    </li>

                    <li class="pr-4">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-book-open fa-2x text-white"></i>
                        </a>
                    </li>


                    <li class="pr-4">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="far fa-list-alt  fa-2x text-white"></i>
                        </a>
                    </li>


                    <li class="pr-4">              
                        <a class="navbar-brand" href="{{ url('/') }}">         
                            <i class="fas fa-atlas fa-2x text-white"></i>        
                        </a>
                    </li>

                    <li class="pr-4">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-cloud-upload-alt  fa-2x text-white"></i>
                        </a>
                    </li>
                </ul>              

             
                <!--[start]right navigation -->
                <ul class="navbar-nav">
                    <li class="pr-4">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-headset  fa-2x text-white"></i>
                        </a>
                    </li>

                    <li class="pr-4 pt-2">
                        <span class="text-white font-weight-bold h2 mt-3" id="countDownTimer">00:25:00</span>                       
                    </li>


                    <li class="pr-4 pt-2">
                       <i class="fas fa-signal fa-2x text-success"></i>          
                    </li>
                </ul>
                <!--[end] right navigation-->


            </div>
        </nav>

        <main class="py-0">
            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
</html>
