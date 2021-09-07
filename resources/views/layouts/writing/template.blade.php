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
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com" crossorigin />

    <!-- Styles -->
    <link rel="icon" href="{{ url('images/favicon.ico') }}" />
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!--steps-->
    <link rel="stylesheet" href="{{ asset('css/steps/steps.css') }}">

    @yield('styles')
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
    <style>
        body {
            font-size: 16px;background: #E2E2E2;
        }

        .wrapper {
            margin: 50px auto;
            max-width: 750px;
        }

        .writing-header {
            margin-bottom: 30px;
            font-size: 25px;
            border-top: 3px solid #00AFEF;
            margin-top: 20px;
            padding-top: 20px;
        }

    </style>
</head>

<body>
    <div id="app" class="container">
        <div class="wrapper">
            <div class="card esi-card-grey">
                <a href="{{ url('writing') }}">
                    <img style="margin:0 auto;" class="img-fluid" src="{{ url('images/title_full.png') }}"
                        alt="{{ config('app.name', 'My Tutor') }}" alt="{{ config('app.name', 'My Tutor') }}">
                </a>
                <div class="writing-header">
                    WRITING SERVICE
                </div>

                @yield('content')
            </div>
        </div>
    </div>


    <script type="text/javascript">   
      let api_token = "{{ Auth::user()->api_token }}";    
    </script>
    
    @yield('scripts')
</body>
