@extends('layouts.esi-app')


@section('content')


<div class="container">

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


                        <questions-component 
                            :multiple="{{ ($category->show_multiple == true) ? 'true': 'false' }}"
                            :memberinfo="{{  json_encode(Auth::user()->memberInfo) }}" 
                            :category="{{ $category }}"
                            api_token="{{ Auth::user()->api_token }}" 
                            csrf_token="{{ csrf_token() }}"
                        >
                        </questions-component>  

                        </div>

                    </div>
            
                </div>

            </div>
        </div>


    </div>


</div>

@endsection

@section('scripts')
<script>    

    window.addEventListener('load', function () 
    {
        
    });
</script>
@endsection