<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>{{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(2))->replace('-', ' ') ) ?? '' }}</title>
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <script src="{{ asset('js/vfs_fonts.js') }}" defer></script>
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>

<body class="bg-gray">
    <div id="app"><!--Add Your Vue Here--></div>
    <div id="appNoJS">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                   <img src="{{ url("images/mytutor_logo.png") }}" alt="{{ config('app.name', 'My Tutor') }}" alt="{{ config('app.name', 'My Tutor') }} administratrion panel">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>

                    <div class="text-right w-100">
                    
                        <a class="blue pr-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ ucfirst(Auth::user()->firstname) }}
                            @if(Auth::user()->user_type == 'ADMINISTRATOR')
                              {{ '(Administrator)' }}                            
                            @elseif (Auth::user()->user_type == 'TUTOR')                                
                                {{ '(Tutor)' }}
                            @elseif (Auth::user()->user_type == 'MANAGER')   
                                {{ '(Manager)' }}
                            @elseif (Auth::user()->user_type == 'AGENT')                               
                                {{ '(Agent)' }}                              
                            @endif
                            <span class="caret"></span>
                        </a>
                        
                       
                        <span class="divide">|</span>
                        <a class="blue pl-2 pr-2" href="{{ route('admin.settings.index') }}">
                            {{ __('Settings') }}
                        </a>
                      

                        <span class="divide">|</span>
                        
                        <a class="red pl-2 pr-2" href="{{ route('admin.AdminLogout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    <br/>

                    <div class="text-right w-100 pr-4 pt-2">
                      <a class="red pr-2" href="skype:netenglish.cebumanager?call"><span class="flag-ph pr-2"></span> 
                      <span>netenglish.cebumanager</span></a>
                    </div>

                    <form id="logout-form" action="{{ route('admin.AdminLogout') }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

    
    
        <div class="bg-lightblue">
          <div class="container px-0">
            <nav class="nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/lesson') }}">My Page</a>

                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/member') }}">User</a>

                @can('manage_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('admin/questionnaires') }}">Manage</a>
                @endcan
                
                @can('report_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/lessons') }}">Report</a>
                @endcan

            </nav>
          </div>
        </div>


        <main class="main-container">
            @yield('content')
        </main>


        <footer class="container py-4 px-0 bg-light">
            <div class="container border-top">
                <div class="row">
                    <div class="col-12 text-center py-3">
                        <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                            {{ config('app.name', 'My Tutor') }}
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @yield('scripts')
</body>

</html>