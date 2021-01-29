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
                <!--sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

                <div class="col-md-9">


                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            レッスン教材
                        </div>
                        <div class="card-body">

                            <div id="category" class="pb-5">                                
                                <div style="border-bottom:1px dashed #d4d4d4">

                                    <div class="text-left px-5 ml-4 py-4">

                                        <table>
                                            <tr>
                                                <td style="width:150px">
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


                                    <div id="course-materials" class="pb-5">
                                        <strong>Download File</Strong>

                                        @if (count($lessonMaterials) >= 1)
                                            @foreach($lessonMaterials as $material)
                                                <div id="{{ $material->id }}" class="my-2">   
                                                    <a href="{{ url('download/'. basename($material->path) ) }}" class="text-danger">
                                                        <img src="{{ url('images/pdf.gif') }}" alt="{{ basename($material->filename) }}" title="{{ basename($material->filename) }}npm">
                                                        {{ basename($material->filename) }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else 
                                            <div>
                                                <small>No File Uploaded</small>
                                            </div>
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
</div>
@endsection