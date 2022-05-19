@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/minitest') }}">Minitest</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Result</li>
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
                       {{ $result->name }}
                    </div>
                    <div class="card-body esi-card-body">
                        <!-- get mini test result view component -->
                        <x-mini-test-result :items="$items" :ctr="$ctr"></x-mini-test-result>
                    </div>

                </div>

            </div>


        </div>

    </div>


</div>
</div>
@endsection