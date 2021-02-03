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
                        </div>
                        <div class="card-body">

                            <div id="parent-category" class="pb-5">

                                @if (isset( $courseParent->name ))
                                <div style="border-bottom:1px dashed #d4d4d4">
                                    <div class="text-left px-5 ml-4 py-4">                                        
                                        <div class="font-weight-bold" style="margin-left:160px">{{ $courseParent->name ?? '' }}</div>
                                        <p id="parent-category-description" class="text-secondary">{!! $courseParent->description ?? '' !!}</p>
                                    </div>
                                </div>
                                @endif

                                @foreach($courseParents as $item)
                                <div style="border-bottom:1px dashed #d4d4d4">
                                    <div class="text-left px-5 ml-4 py-4">
                                        <table>
                                            <tr>
                                                <td class="w-25 align-top">
                                                    @if (isset($item->path))
                                                    <img src="{{ Storage::url($item->path) }}">
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