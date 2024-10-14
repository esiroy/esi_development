@extends('layouts.master')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light ">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item text-left"><a href="{{ url('/pages') }}">Pages</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $page->title ?? '' }}</li>
    </ol>
</nav>

<div class="container bg-light">

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

@endsection
