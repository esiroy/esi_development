@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mini Test</li>
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
                            Mini Test
                        </div>

                        <div class="card-body">

                            <div class="text-secondary py-2">
                               Please select a test
                            </div>


                            <div class="pb-2">
                                @foreach($categories as $category) 

                                    <div class="small">

                                        <a href="{{ url('minitest/'. $category->slug) }}">
                                            {{ $category->name }}
                                        </a>

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