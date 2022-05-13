@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Minitest</li>
            </ol>
        </nav>

        <div class="container">

            <div class="row">

                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->
                <div class="col-md-9">
                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            Minitest
                        </div>

                        <div class="card-body">


                            <div class="text-secondary py-2">
                                Select test category

                            </div>


                            <div class="pb-2">
                                @foreach($parents as $parent) 

                                    <div class="small">

                                        <a href="{{  URL::current() .'/category/'. $parent->id }}">
                                            {{ $parent->name }}
                                        </a>

                                        {{ $parent->getCategoryTypeSubLinks($parent, 1) }}

                                    </div>

                                @endforeach
                            </div>


                        </div>

                    </div>
              
                </div>

            </div>
        </div>


    </div>


</div>
</div>
@endsection