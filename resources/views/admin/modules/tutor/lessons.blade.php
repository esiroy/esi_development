@extends('layouts.admin')
@section('content')
<div class="container bg-light">
    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Page</li>
            </ol>
        </nav>


        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
        @endif
            
        <div class="container">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">Lesson</div>
                <div class="card-body">

                    <form name="dateForm" method="GET">
                        <div class="row">
                            <div class="col-3">
                                <label for="inputDate" class="pr-3">Date:</label>
                                <input type="date" id="dateFrom" required name="dateFrom" value="{{ $dateFrom }}" min="2000-01-01" class="inputDate hasDatepicker form-control form-control-sm d-inline col-8">
                            </div>
                            <div class="col-3">
                                <label for="inputDate" class="pr-3">Date:</label>
                                <input type="date" id="dateTo" required name="dateTo" value="{{ $dateTo }}" min="2000-01-01" class="inputDate hasDatepicker form-control form-control-sm d-inline col-8">
                            </div>
                            <div class="col-2">
                                <input type="submit" class="btn btn-primary btn-sm" value="Go">
                            </div>

                        </div>
                    </form>

                    <div class="legend bg-lightgray mt-2">
                        <table cellspacing="0" cellpadding="5">
                            <tbody>
                                <tr>
                                    <td>Legend</td>
                                    <td>:</td>

                                    <td><img src="{{ url('images/iNothing.gif') }}" alt="Nothing" title="Nothing" align="absmiddle"> Nothing</td>
                                    <td><img src="{{ url('images/iTutorScheduled.gif') }}" alt="Tutor Scheduled" title="Tutor Scheduled" align="absmiddle"> Tutor Scheduled</td>
                                    <td><img src="{{ url('images/iClientReserved.gif') }}" alt="Client Reserved" title="Client Reserved" align="absmiddle"> Client Reserved</td>
                                    <td><img src="{{ url('images/iSuppressed.gif') }}" alt="Suppressed Schedule" title="Suppressed Schedule" align="absmiddle"> Suppressed Schedule</td>
                                </tr>
                                <tr>

                                    <td></td>
                                    <td></td>
                                    <td><img src="{{ url('images/iCompleted.gif') }}" alt="Completed" title="Completed" align="absmiddle"> Completed</td>
                                    <td><img src="{{ url('images/iTutorCancelled.gif') }}" alt="Tutor Cancelled" title="Tutor Cancelled" align="absmiddle"> Tutor Cancelled</td>
                                    <td><img src="{{ url('images/iNotAvailable.gif') }}" alt="Not Available" title="Not Available" align="absmiddle"> Client Not Available</td>
                                    <td></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="card-text"></p>

                    <div class="card-header esi-card-header-title text-center">
                        {{ date('Y', strtotime($dateFrom)) }} 年 {{ date('m', strtotime($dateFrom)) }}月 {{ date('d', strtotime($dateFrom)) }}日
                        -
                        {{ date('Y', strtotime($dateTo)) }} 年 {{ date('m', strtotime($dateTo)) }}月 {{ date('d', strtotime($dateTo)) }}日
                    </div>

                    <div class="card-body scrollable-x p-0">
                        <table class="table table-tutor-schedules">
                            <thead>
                                <tr>
                                    <td class="scheduleTimeList">
                                        <div class="dateContainer">
                                            &nbsp;
                                        </div>
                                        <div style="background-color:#d0e8f7">&nbsp;</div>
                                    </td>
                                    @for($ctr=0; $ctr <= $lessonDays; $ctr++) <td class="schedules">
                                        <div class="dateContainer">
                                            <div class="row p-0 m-0">
                                                <div class="day col-md-4">
                                                    <div class="text-small text-center">
                                                        <strong>{{ date('d', strtotime($dateFrom ." + $ctr day"))}}</strong>
                                                    </div>
                                                </div>
                                                <div class="text-left col-md-8">{{ date('l', strtotime($dateFrom ." + $ctr day"))}}</div>
                                            </div>
                                        </div>
                                        <div class="text-center" style="background-color:#d0e8f7">Member</div>
                                        </td>
                                        @endfor
                                </tr>
                            </thead>

                            @foreach($timeSlots as $timeSlot)
                            <tr>
                                <td class="scheduleTimeList">

                                    <div class="class-schedule-container">

                                        <div class="time">
                                            <span class="flag-ph"></span>
                                            <span class="class-schedule class-schedule-start">{{ $timeSlot['startTime'] }}</span>
                                        </div>

                                        <div>
                                            <span class="flag-jp"></span>
                                            <span class="class-schedule class-schedule-end">
                                                @php
                                                $hour = date('H', strtotime($timeSlot['startTime']) + 60*60);
                                                $minute = date('i', strtotime($timeSlot['startTime']) + 60*60);
                                                if ($hour == '00' || $hour == '0') {
                                                $hour = "24";
                                                }
                                                @endphp
                                                {{ $hour .":" . $minute }}
                                            </span>
                                        </div>
                                    </div>

                                </td>

                                @for($ctr=0; $ctr <= $lessonDays; $ctr++) <td class="tutorSchedules">

                                    @php
                                    if ($timeSlot['startTime'] == "23:00" || $timeSlot['startTime'] == "23:30") {
                                        //next day view
                                        $nextDayCtr = $ctr + 1;
                                        $dateView = date('m/d/Y', strtotime($dateFrom ." + $nextDayCtr day"));
                                    } else {
                                        $dateView = date('m/d/Y', strtotime($dateFrom ." + $ctr day"));
                                    }
                                    @endphp
                  

                                    @if(isset($lessons[$dateView][$timeSlot['startTime']]['status']))

                                    <div class="toggleHide">


                                        <div class="@php echo str_replace(' ', '_', strtolower($lessons[$dateView][$timeSlot['startTime']]['status'])) @endphp" style="width:100%; padding:5px">

                                            <div class="client text-center text-white">

                                                @php
                                                $status = $lessons[$dateView][$timeSlot['startTime']]['status'];
                                                $checkStatus = strtolower(str_replace(' ', '_', $status));
                                                @endphp

                                                <!--@note: get member profile link / name -->
                                                @if(isset( $lessons[$dateView][$timeSlot['startTime']]['member_id']))
                                                    <div class="text-dark">
                                                        <a href="{{ route('admin.member.show', $lessons[$dateView][$timeSlot['startTime']]['member_id']) }}">
                                                            {{$lessons[$dateView][$timeSlot['startTime']]['nickname']}}
                                                        </a>
                                                    </div>
                                                    <div class="hide">

                                                @if ($checkStatus == 'client_reserved' || $checkStatus == 'client_reserved_b' )
                                                    <a href="javascript:void()" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $lessons[$dateView][$timeSlot['startTime']]['id'] }}">
                                                        <div id="memoContent" style="display:none">
                                                            {{ $lessons[$dateView][$timeSlot['startTime']]['memo']}}
                                                        </div>
                                                        <!-- <img src="{{ url('images/iEmail.jpg') }}" border="0" align="absmiddle"> -->
                                                        Send A Message
                                                    </a>
                                                    <span class="text-secondary"> | </span>
                                                @endif
                                                    <a href="{{ route('admin.reportcard.index', ['scheduleitemid' => $lessons[$dateView][$timeSlot['startTime']]['id'] ]) }}">Grade</a>
                                                </div>
                                                @endif

                                            </div>

                                            @if ($checkStatus == 'client_reserved' || $checkStatus == 'client_reserved_b')

                                            <div id="tutor-lesson-memoBox-{{ $lessons[$dateView][$timeSlot['startTime']]['id'] }}">
                                                @if ( $lessons[$dateView][$timeSlot['startTime']]['memo'] )
                                                
                                                    <div id="memoContainer" class="btn-container2 pt-2">
                                                        <!-- open memo -->
                                                        <a href="javascript:void()" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $lessons[$dateView][$timeSlot['startTime']]['id'] }}">
                                                            <div id="memoContent" style="display:none">
                                                                {{ $lessons[$dateView][$timeSlot['startTime']]['memo']}}
                                                            </div>
                                                            <img src="{{ url('images/iEmail.jpg') }}" border="0" align="absmiddle">
                                                        </a>
                                                    </div>

                                                @else

                                                
                                                    <div id="memoContainer" class="btn-container2 pt-2" style="display:none" >
                                                        <!-- open memo -->
                                                        <a href="javascript:void()" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $lessons[$dateView][$timeSlot['startTime']]['id'] }}">
                                                            <div id="memoContent">
                                                             {{ $lessons[$dateView][$timeSlot['startTime']]['memo']}}
                                                            </div>
                                                            <img src="{{ url('images/iEmail.jpg') }}" border="0" align="absmiddle">
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            @endif



                                        </div>
                                    </div>

                                    @endif

                                    </td>
                                    @endfor
                            </tr>
                            @endforeach

                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection