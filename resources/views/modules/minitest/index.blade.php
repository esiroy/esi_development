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
                            <span class="text-secondary small float-right">
                                <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0607200521.html','Mini Test',900,820)">Mini Test  について</a> 
                            </span>
                        </div>

                        <div class="card-body">



                            <!--
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
                            -->

                            @foreach($parents as $parent)
                            <div style="border-bottom:1px dashed #d4d4d4">
                                <div class="text-left pl-2 py-4">
                                    <table>
                                        <tr>
                                            <td class="pl-4 pr-4 align-top">                                            
                                                <a href="{{  URL::current() .'/category/'. $parent->id }}">
                                                    {{ $parent->name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pl-4 pr-4 align-top text-secondary">
                                                {{ $parent->description }}
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


</div>

@endsection