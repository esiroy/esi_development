<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} ">
    <title>{{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(1))->replace('-', ' ') ) ?? '' }}</title>
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>

    <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') .'?id=5_14_2024'  }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') .'?id=5_14_2024'  }}">
    
    <!-- Scripts -->    
    <script src="{{ asset('js/app.js') .'?id=5_14_2024'  }}" defer ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />

    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>
<body class="bg-gray">

<div class="container">

    <div class="row">
        <div class="col-8">
      
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    {{ $page->title }}                    
                </div>
                <div class="card-body">

                    <div class="text-right small">
                        <span class="small text-muted">{{ $page->created_at }}</span>
                    </div>

                    <div class="text-left">
                        {!! $page->content !!}
                    </div>

                </div>
            </div>
    
        </div> 

        <div class="col-4">
            
            <div class="card esi-card">
                <div class="card-header bg-primary esi-card-header">
                    <h4 class="text-white">
                        List
                    </h4>
                </div>
                <div class="card-body">

                    <ul class="list-group list-group-flush">
                        @foreach($lists as $list)
                            <li class="list-group-item "><a href="{{ url('pages/'.$list->slug.'?id='.$list->id) }}" class="text-primary"> {{ $list->title }}</a></li>
                        @endforeach
                    </ul>

                    @if ($lists->lastPage() > 1)
                    <div class="float-right mt-5">
                        {{ $lists->links() }}
                    </div>  
                    @endif

                </div>
              
            </div>
            
        </div>       

    </div>

</div>


<script>    alert ("Test");</script>