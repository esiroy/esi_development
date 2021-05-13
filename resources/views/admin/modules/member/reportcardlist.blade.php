@extends('layouts.admin')
@section('content')
<div class="container bg-light px-0">
    @include('admin.modules.member.includes.menu')
    <div class="esi-box">
        <!--@include('admin.modules.member.includes.breadcrumbs')-->
        <div class="container mt-5">
            <div class="card esi-card mt-5">
                <div class="card-header esi-card-header">
                    Report Card List
                </div>
                
                <div class="card-body">

                    <div class="member mt-3">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2">Name</div>
                                <div class="col-md-9">
                                    {{ $member->lastname  ?? ' - ' }},
                                    {{ $member->firstname  ?? ' - ' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">Agent</div>
                                <div class="col-md-9">
                                    {{ $agentInfo->user->firstname  ?? ' - ' }}
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">Tutor</div>
                                <div class="col-md-9">

                                    {{ $tutorInfo->user->japanese_firstname  ?? ' - ' }}
                                </div>
                            </div>

                            <!--
                            <div class="row">
                                <div class="col-md-2">Lesson Class</div>
                                <div class="col-md-9">
                                    毎月 {{$memberAttribute->lesson_limit ?? '0'}} 回クラス (あと　残り {{$memberAttribute->lesson_limit ?? '0'}}回)
                                </div>
                            </div>
                            -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-4">

                            <table class="table esi-table table-bordered table-striped  ">
                                <thead>
                                    <tr>
                                        <th>Lesson Date &amp; Time</th>
                                        <th>Lesson Subject</th>
                                        <th>Lesson Course</th>
                                        <th>Lesson Material</th>
                                        <th>Grade</th>
                                        <th>Comment</th>
                                        <th>Tutor</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($reportcards as $item)
                                    <tr>
                                        <td>                                            
                                            @php 
                                                if (isset(\App\Models\ScheduleItem::find($item->schedule_item_id)['lesson_time'])) {
                                                    $time = \App\Models\ScheduleItem::find($item->schedule_item_id)['lesson_time'];
                                                    echo date('F d, Y H:i', strtotime($time));
                                                } else {
                                                    echo "-";
                                                }                                                
                                            @endphp                                            

                                        </td>
                                        <td>
                                            {!! wordwrap($item->lesson_subject, 20, "<br />\n") !!}
                                        </td>
                                        <td>                                           
                                            {!! wordwrap($item->lesson_course, 20, "<br />\n") !!}
                                        </td>
                                        <td>
                                            {!! wordwrap($item->lesson_material, 20, "<br />\n") !!}
                                        </td>
                                        <td>{{ $item->grade }}</td>
                                        <td>


                                            {!! wordwrap($item->comment, 40, "<br />\n") !!}
                                        </td>
                                        @php

                                        $tutorID = \App\Models\ScheduleItem::find($item->schedule_item_id)['tutor_id'];
                                        $tutor = \App\Models\Tutor::where('user_id', $tutorID)->first();

                                        @endphp
                                        <td>{{ $tutor->user->firstname }}</td>
                                    </tr>
                                    @endforeach


                                    @if (count($reportcards) == 0)
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center">No Result</div>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>
                            </table>

                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">
                                        Page :</small>
                                    {{ $reportcards->appends(request()->query())->links() }}
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
