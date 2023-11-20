@extends('layouts.esi-app')

@section('content')

<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Time Manager</li>
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
                            Time Manager
                        </div>
                        <div class="card-body">

                           

                            <member-time-manager-component 
                                :memberinfo="{{  json_encode(Auth::user()->memberInfo)  }}" 
                                api_token="{{ Auth::user()->api_token }}" 
                                csrf_token="{{ csrf_token() }}">
                            </member-time-manager-component>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>


@endsection


