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
        
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.1/jquery.min.js" crossorigin="anonymous"></script>

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

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Tracking Script -->
    <script>
		var url = window.location.hostname;
		//alert(url);
		if(url != 'mypage.mytutor-jpn.com' && url != 'mytutor-test.ivant.com'){
			// window.location.replace("http://mypage.mytutor-jpn.com/signup.do");
		}
	</script> 
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1013785582"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'AW-1013785582');
	</script>
</head>
<body>

    <!--<div id="new_member_ID"> {{ $user->id }} </div> -->

    <img src="https://px.a8.net/cgi-bin/a8fly/sales?pid=s00000012345001&so={{ $user->id  ?? ''}}&si=926.1.926.a8" width="1" height="1" style="height: 1px;display: block;background-color:yellow"/>
	<img src="https://advack.net/m/img.php?pcode=248&aid={{ $user->id  ?? ''}}" width="1" height="1" style="height: 1px;display: block;background-color:yellow"/>     

    <!-- Google Code for &#20250;&#21729;&#30331;&#37682; Conversion Page -->
    <script type="text/javascript" defer>
        / <![CDATA[ /
        var google_conversion_id = 1013785582;
        var google_conversion_language = "en";
        var google_conversion_format = "2";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "-1x3CLKOuwUQ7se04wM";
        var google_conversion_value = 1.00;
        var google_conversion_currency = "JPY";
        var google_remarketing_only = false;
        / ]]> /
    </script>
    
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"> </script>		
    <span id="a8sales" style="height: 1px;display: block;background-color:yellow"></span>

    <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt=""  src="//www.googleadservices.com/pagead/conversion/1013785582/?value=1.00&amp;currency_code=JPY&amp;label=-1x3CLKOuwUQ7se04wM&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>  
    

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
    <script>
        console.log("member activated");
    </script>
    @yield('scripts')
</body>
</html>
