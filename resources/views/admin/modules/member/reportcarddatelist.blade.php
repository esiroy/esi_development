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

                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.modules.member.includes.info')
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
                                        <td>{{ $item->lesson_date }}</td>
                                        <td>{{ $item->lesson_subject }}</td>
                                        <td>{{ $item->lesson_course }}</td>
                                        <td>{{ $item->lesson_material }}</td>
                                        <td>{{ $item->grade }}</td>
                                        <td>{{ $item->comment }}</td>
                                        @php

                                        $tutorID = \App\Models\ScheduleItem::find($item->schedule_item_id)['tutor_id'];
                                        $tutor = \App\Models\Tutor::find($tutorID);

                                        @endphp
                                        <td>
                                            @if (isset($tutor->name))
                                            {{ $tutor->name_en }}
                                            @endif
                                        </td>
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
