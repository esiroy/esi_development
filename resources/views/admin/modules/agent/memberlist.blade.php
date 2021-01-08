@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>

        <div class="container bg-light">

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif


            <div id="member-transaction" class="card esi-card mt-4">
                <div class="card-header esi-card-header">
                    Member List
                </div>

                <div class="card-body esi-card">
                    <div class="member mt-3">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2">Name</div>
                                <div class="col-md-9">
                                    {{ $member->user->firstname  ?? ' - ' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body esi-card-body">
                        <div class="table-responsive">
                            <table class="table table esi-table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>Level</th>
                                        <th>Course</th>
                                        <th>Class(月間使用可能ポイント)</th>
                                        <th>Remaining points ポイント残</th>
                                        <th>Lesson Record</th>
                                        <th>Monthly Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $key => $member)
                                    <tr>
                                        <td>{{ $member->user_id}}</td>
                                        <td>
                                            {{ $member->firstname ?? "-" }}
                                            {{ $member->lastname ?? "-" }}
                                        </td>
                                        <td>
                                            {{ str_replace('_', ' ', ucwords(strtolower($member->english_level))) ?? "-" }}
                                        </td>

                                        <td>
                                            <!--@note: Course name -->
                                            @php 
                                                $courseCategory = new App\Models\CourseCategory();
                                                $course = $courseCategory->where('id', $member->course_category_id)->first();
                                            @endphp
                                            {{ $course->name ?? "-" }}
                                        </td>

                                        <td>
                                            <!--@note: lesson limit (Class(月間使用可能ポイント)) -->
                                            @php 
                                                $attribute = new App\Models\MemberAttribute();
                                                $availableClasses = $attribute->getLessonLimit($member->user_id)
                                            @endphp
                                        
                                            {{ number_format($availableClasses, 0) }}          
                                        </td>
                                        <td>
                                            <!--@note: Credits-->
                                            @php 
                                                $agentTransaction = new App\Models\AgentTransaction();
                                                $credits = $agentTransaction->getCredits($member->user_id)
                                            @endphp
                                            {{ number_format($credits, 2) }}                                        
                                        </td>
                                        <td><a href="/admin/reportcardlist/{{$member->user_id}}"><img src="{{ url('images/iReportCard.jpg')}}"></a></td>
                                        <td>
                                            <a href="/admin/reportcarddatelist/{{$member->user_id}}" alt="List of Monthly Report Card" title="List of Monthly Report Card">
                                                <img src="{{ url('images/iMonthlyRC.jpg')}}">
                                            </a>                                        
                                        </td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>




            </div>


        </div>

    </div>

    @endsection
