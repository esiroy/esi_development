<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>
    {{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(3))->replace('-', ' ') ) ?? '' }} {{ " - " . ucwords( Str::of(Request::segment(2))->replace('-', ' ') ) ?? '' }}
    </title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    
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

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
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
                            <!--
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                            -->
                        @else

                        <!--
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ ucfirst(Auth::user()->first_name) }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout_member') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout_member') }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            -->

                            
                                @php 
                                    $member = App\Models\Member::where('user_id', Auth::user()->id )->first();
                                    $agentTransaction = new App\Models\AgentTransaction();
                                    $credits = $agentTransaction->getCredits($member->user_id)
                                @endphp
                            
                                <span><a class="blue" href="{{ url('/user/?id='. Auth::user()->id)}}"><strong>ユーザ名 {{ $member->nickname }}</strong></a></span>                                

                                <span class="text-success">({{ number_format($credits, 2) }})</span>
                                <span class="px-2 text-success">|</span>

                                <span><a class="blue" href="{{ url('/settings') }}">設定</a></span>
                                <span class="px-2 text-success">|</span>

                                <span>
                                    <a class="red" href="{{ route('logout_member') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    ログアウト</a>
                                </span>

                                <form id="logout-form" action="{{ route('logout_member') }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                </form>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

    
        <div class="bg-lightblue">
          <div class="container px-0">
            <nav class="nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('/home') }}">マイページ </a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="JavaScript:newPopup('http://www.mytutor-jpn.com/faq.html');">よくあるご質問</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('customersupport') }}">カスタマー　サポート</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('lessonmaterials') }}">オリジナル教材</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="JavaScript:newPopup('http://www.mytutor-jpn.com/lesson.html');">レッスンコース</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="JavaScript:newPopup('http://www.mytutor-jpn.com/service.html');">レッスン料金</a>
            </nav>
          </div>
        </div>


        <main class="main-container mb-4">
            <div class="container bg-light pb-5 rounded-bottom" style="border-bottom-right-radius: 0.50rem !important; border-bottom-left-radius: 0.50rem !important;">
                @yield('content')
            </div>
        </main>

    </div>

    <script type="text/javascript">
        window.addEventListener('load', function () {

            //events here
        });

        function newPopup(url) {
            popupWindow = window.open(url,'popUpWindow','height=500,width=700,left=0,top=0,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
        }



    </script>

    @yield('scripts')
</body>

</html>
