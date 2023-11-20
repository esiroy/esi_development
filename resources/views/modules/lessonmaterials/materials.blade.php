@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('/lessonmaterials') }}">Lesson Materials</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $course->name ?? '' }}</li>

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
                            レッスン教材 
                            <span class="text-right" style="width: 75%;display: block; float: right;font-size: 15px; color: #000; margin:4px 0px 0px">                                
                            （オリジナル教材以外はカスタマーサポートまでご連絡ください）
                            </span>
                        </div>

                        <div class="card-body">

                            <div id="category" class="pb-5">                                
                                <div style="border-bottom:1px dashed #d4d4d4">
                                    <div class="text-left px-3 ml-4 py-3">
                                        <table>
                                            <tr>
                                                <td class="pl-4 pr-4 align-top">
                                                    @if(isset($course->path))
                                                        <img src="{{ Storage::url($course->path) }}">
                                                    @endif
                                                </td>
                                                <td>
                                                    <p><strong>{{ $course->name }}</strong></p>
                                                    <p id="parent-category-description" class="text-secondary">{!! $course->description !!}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>


                                    <div id="course-materials" class="pb-2" style="border-bottom:1px dashed #d4d4d4">
                                        @if (count($lessonMaterials) >= 1)
                                           <strong>Download File</Strong>
                                            @foreach($lessonMaterials as $material)
                                                <div id="{{ $material->id }}" class="my-2">   
                                                    <a href="{{ url('download/'. basename($material->path) ) }}" download class="text-danger">
                                                        <img src="{{ url('images/pdf.gif') }}" alt="{{ basename($material->filename) }}" title="{{ basename($material->filename) }}">
                                                        {{ basename($material->filename) }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else 
                                            <!--
                                            <div>
                                                <small>No File Uploaded</small>
                                            </div>
                                            -->
                                        @endif
                                    
                                    </div>

                                    @if (count($courseSiblings) > 0) 
                                        @foreach($courseSiblings as $item)
                                        <div style="border-bottom:1px dashed #d4d4d4">
                                            <div class="text-left px-5 ml-4 py-4">
                                                <table>
                                                    <tr>
                                                        <td class="pl-4 pr-4 align-top">
                                                            @if (isset($item->path))
                                                            <img src="{{ Storage::url($item->path) }}">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a id="parent-category-name" href="{{ url('lessonmaterials/'.$item->id )}}" class="text-danger font-weight-bold">{{ $item->name }}</a>
                                                            <p id="parent-category-description" class="text-secondary">{!! $item->description !!}</p>
                                                        </td>
                                                </table>
                                            </div>
                                        </div>
                                        @endforeach                                    
                                    @endif



                                </div>                                
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