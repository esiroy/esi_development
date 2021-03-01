@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lesson Materials</li>
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
                        @include('modules.member.sidebar.customersupport')
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
                            <span class="text-right" style="width: 75%;display: block; float: right;font-size: 15px; color: #000; margin:4px 0px 0px">                               
                            （オリジナル教材以外はカスタマーサポートまでご連絡ください）
                            </span>                                                        
                        </div>
                        
                        <div class="card-body">

                            <div id="parent-category" class="pb-5">
                                @if (isset( $courseParent->name ))
                                    <!--[START] PARENT IMAGE -->
                                    <div style="border-bottom:1px dashed #d4d4d4">
                                        <div class="text-left pl-2 py-4">
                                            <table>
                                            <tr>
                                                <td class="pl-4 pr-4 align-top">
                                                    @php 
                                                        $courseCategoryImage = new App\Models\CourseCategoryImage();
                                                        $courseParentImage = $courseCategoryImage::where('course_category_id', $id)->first();                                                        
                                                    @endphp
                                                    <a id="parent-category-name" href="{{ url('lessonmaterials/'.$id )}}" class="text-danger font-weight-bold"><img src="{{ Storage::url($courseParentImage->path) }}"></a>                                                
                                                </td>
                                                <td class="align-top">
                                                    <div class="font-weight-bold">{{ $courseParent->name ?? '' }}</div>
                                                    <p id="parent-category-description" class="text-secondary">{!! $courseParent->description ?? '' !!}</p>
                                                </td>
                                            </table>
                                        </div>

                                        <!-- LESSON MATERIALS FOR PARENT -->
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
                                             @endif
                                        </div>                                        
                                    </div>
                                    <!--[END] PARENT IMAGE -->
                                @endif

    

                               

                                @foreach($courseParents as $item)
                                <div style="border-bottom:1px dashed #d4d4d4">
                                    <div class="text-left pl-2 py-4">
                                        <table>
                                            <tr>
                                                <td class="pl-4 pr-4 align-top">

                                                    @php 
                                                        $courseCategoryImage = new App\Models\CourseCategoryImage();
                                                        $courseImage = $courseCategoryImage::where('course_category_id', $item->id)->first();                                                        
                                                    @endphp

                                                    @if (isset($item->path))
                                                        <img src="{{ Storage::url($item->path) }}">
                                                    @elseif ($courseImage)
                                                         <a id="parent-category-name" href="{{ url('lessonmaterials/'.$item->id )}}" class="text-danger font-weight-bold"><img src="{{ Storage::url($courseImage->path) }}"></a>
                                                    @endif
                                                </td>
                                                <td class="align-top">
                                                    <a id="parent-category-name" href="{{ url('lessonmaterials/'.$item->id )}}" class="text-danger font-weight-bold">{{ $item->name }}</a>
                                                    <p id="parent-category-description" class="text-secondary">{!! $item->description !!}</p>
                                                </td>
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


</div>
</div>
@endsection