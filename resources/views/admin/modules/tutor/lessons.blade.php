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
                        <table class="table table-bordered table-schedules">
                            <tr>
                                <td class="schedTime">
                                   
                                </td>
                                @for($ctr=0; $ctr <= $lessonDays; $ctr++) 
                                <td style="background-color:#f3e77f; border: 1px solid #d0e8f7;border-bottom: 3px solid #72add2; padding: 10px 0px 0px; border-right: 1px solid #ffffff;vertical-align: top;">
                                    <div style="width:125px; background-color:#f3e77f">
                                        <div class="float-left text-small">{{ date('d', strtotime($dateFrom ." + $ctr day"))}}</div>
                                        <div class="text-center">{{ date('l', strtotime($dateFrom ." + $ctr day"))}}</div>
                                    </div>
                                    <div class="pt-1" style="background-color:#d0e8f7">Member</div>
                                </td>
                                @endfor
                            </tr>

                            @foreach($timeSlots as $timeSlot)
                            <tr>
                                <td class="" style="padding:3px 0px 3px">
                                    <div style="width:125px">
                                        <div class="class-schedule-container">
                                            <span class="flag-ph"></span>
                                            <span class="class-schedule class-schedule-start">{{ $timeSlot['startTime'] }}</span>
                                        </div>
                                        <div class="class-schedule-container">
                                            <span class="flag-jp"></span>
                                            <span class="class-schedule class-schedule-end">{{ date('H:i', strtotime($timeSlot['startTime']) + 60*60) }}</span>
                                        </div>
                                    </div>
                                </td>

                                @for($ctr=0; $ctr <= $lessonDays; $ctr++) 
                                <td class>
                                    <!--@PLOTTER
                                    {{ date('m/d/Y', strtotime($dateFrom ." + $ctr day"))}} - {{ $timeSlot['startTime'] }}
                                    -->
                                    @php                                       
                                        $dateView = date('m/d/Y', strtotime($dateFrom ." + $ctr day"));
                                    @endphp                              

                                    @if(isset($lessons[$dateView][$timeSlot['startTime']]['status']))

                                    <div class="@php echo str_replace(' ', '_', strtolower($lessons[$dateView][$timeSlot['startTime']]['status'])) @endphp" style="width:100%">
                                        <div class="client text-center text-white">
                                            <small>
                                                {{ $timeSlot['startTime'] }}

                                                @if (isset($lessons[$dateView][$timeSlot['startTime']]['member_name_en'])) 
                                                    <!--@todo: add a form link -->
                                                    {{$lessons[$dateView][$timeSlot['startTime']]['status']}}
                                                    {{$lessons[$dateView][$timeSlot['startTime']]['member_name_en']}}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="btn-container">
                                            <!--<div class="iEdit"><a href="javascript:void(0);"><img src="/images/iEdit.gif"></a></div>-->
                                            &nbsp;
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

        @endsection
