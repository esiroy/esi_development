@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/minitest') }}">Mini Test</a>
                </li> 
                {!! $breadcrumbs !!}
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
                            <span class="text-secondary small float-right">
                                <a href="#">Mini Test  について</a>                               
                            </span>                            
                        </div>

                        <div class="card-body">


                        <div style="border-bottom:1px dashed #d4d4d4">

                            <div class="my-4">
                                <h4 class="card-title">{{ $categoryType->name ?? '' }}</h4>
                                <h5 class="card-subtitle mb-2 text-muted">{{ $categoryType->description ?? '' }}</h5>
                            </div>

                            <!--<strong> Select List  </strong>-->

                            <div class="pb-2 pl-4">
                                @foreach($categories as $category)
                                    <div class="mb-">
                                        <a href="{{ url('minitest/'. $category->slug) }}" class="small">
                                            <strong>{{ $category->name }} </strong>
                                        </a> 
                                        <div class="small text-muted">{{ $category->instructions }}</div>
                                        <div class="small text-muted"> Time Limit : {{ $category->time_limit }} Minutes </div>
                                      
                                    </div>
                                @endforeach
                            </div>


                        </div>

                    </div>

                    
                    @foreach($categorySubTypes as $subType)
                    <div style="border-bottom:1px dashed #d4d4d4">
                        <div class="text-left pl-2 py-4">
                            <table>
                                <tr>
                                    <td class="pl-2 pr-4 align-top">                                            
                                        <a href="{{  url('minitest/category/'.$subType->id) }}" class="strong">
                                            {{ $subType->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-2 pr-4 align-top text-secondary">
                                        {{ $subType->description }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endforeach                    


                </div>

            </div>
        </div>


    </div>


</div>
</div>
@endsection