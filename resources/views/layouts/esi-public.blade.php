<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="My Tutor">
    <meta name="keywords" content="Tutor, Japan, Lesson">
    <title>{{ config('app.name', 'My Tutor') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />

    <!-- Styles -->
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>
<body>
    <div id="app">
        
        <!--<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm"  style="background-color:#2d9bb6">-->

        <nav class="navbar navbar-expand-md navbar-light shadow-sm"  style="background-color:#2d9bb6">
            <div class="container">

            <!--
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'My Tutor') }}
                </a>

            -->

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white font-weight-bold" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>

                            <!--
                            @if (Route::has('signup'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('signup') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                            -->
                            
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ ucfirst(Auth::user()->first_name) }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout_member') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="main-container py-4">
            @yield('content')
        </main>

        <!--
        <footer class="pt-4 pt-md-4 pb-md-4 border-top">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md text-center">
                        <a class="navbar-brand" href="{{ url( route('home') ) }}">
                            {{ config('app.name', 'My Tutor') }}
                        </a>
                    </div>
                </div>
            </div>
        </footer>
        -->

    </div>

    @yield('scripts')
</body>
</html>
