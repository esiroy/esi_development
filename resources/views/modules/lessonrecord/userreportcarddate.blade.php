@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report Card Date</li>
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

                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.membertestscores')
                    </div>
                    
                                        
                </div>
                <!--[end sidebar]-->

                <div class="col-md-9">
                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            最新のライティングレポート
                        </div>

                        <div class="card-body">


                            <table border="0" cellspacing="5" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td>Lesson Date</td>
                                        <td class="px-3"> : </td>
                                        <td>
                                            @php 
                                                echo ESIDateFormat($userreportcard->lesson_date);
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Course</td>
                                        <td class="px-3"> : </td>
                                        <td>
                                            {{ $userreportcard->lesson_course }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Material</td>
                                        <td class="px-3"> : </td>
                                        <td>{{ $userreportcard->lesson_material }}</td>
                                    </tr>
                                    <tr>
                                        <td>Subject</td>
                                        <td class="px-3"> : </td>
                                        <td>{{ $userreportcard->lesson_subject }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tutor</td>
                                        <td class="px-3"> : </td>
                                        <td>
                                            @php
                                                $tutor = \App\Models\Tutor::where('user_id', $userreportcard->tutor_id)->first();
                                            @endphp
                                            {{ $tutor->user->firstname ?? " - " }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Grade</td>
                                        <td class="px-3"> : </td>
                                        <td>{{ $userreportcard->grade }}</td>
                                    </tr>
                                    <tr>
                                        <td>Comment</td>
                                        <td class="px-3"> : </td>
                                        <td>{{ $userreportcard->comment }}</td>
                                    </tr>
                                    <tr>
                                        <td>Uploaded File </td>
                                        <td class="px-3"> : </td>
                                        <td><a href="{{ Storage::url("uploads/report_files/". basename($userreportcard->file_path)) }}" download>DOWNLOAD</a></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

            </div>
        </div>


    </div>


</div>
</div>
@endsection