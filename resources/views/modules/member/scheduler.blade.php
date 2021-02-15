@extends('layouts.esi-app')
@section('content')
<div class="container bg-light">

    <div class="esi-box mb-5 pb-4">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Page</li>
            </ol>
        </nav>


        <div class="container mb-4">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">講師スケジュール </div>
                <div class="card-body">

                    <div class="mt-2 mb-4">
                        <div>予約= 予約可能です</div>
                        <div>済　= 本人で予約済みです</div>
                    </div>

                    <!--[start] Update Date -->
                    <form name="dateForm" method="GET">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-3">
                                <label for="dateToday" class="pr-0">Date:</label>
                                <input type="date" id="dateToday" name="dateToday" value="{{ $dateToday }}" min="2000-01-01" class="inputDate hasDatepicker form-control form-control-sm d-inline col-7">
                                <input type="submit" class="btn btn-primary btn-sm form-control form-control-sm d-inline col-3" value="Go">
                            </div>
                        </div>
                    </form>
                    <!--[end] Update Date -->

                    <div class="card-header esi-card-header-title text-center">
                        {{ date('Y', strtotime($dateToday)) }} 年 {{ date('m', strtotime($dateToday)) }}月 {{ date('d', strtotime($dateToday)) }}日
                    </div>

                    <!--[start] card-body -->
                    <div class="card-body scrollable-x p-0 text-center" style="height:720px">
                        <table class="table table-bordered table-schedules">
                            <thead>
                                <tr>
                                    <th class="schedTime static">
                                        <div class="bordered">
                                            <div class="class-schedule-container">
                                                <span class="flag-jp" style="vertical-align: bottom;"></span>
                                            </div>
                                        </div>
                                    </th>
                                    @foreach($lessonSlots as $lessonSlot)
                                    <td class="schedTime">
                                        <div class="bordered">
                                            <div class="gen-small">{{ $lessonSlot['startTime'] }}</div>
                                            <div class="gen-small">{{ $lessonSlot['endTime'] }}</div>
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($tutors as $tutor)
                            <tr>
                                <!--[start] Tutor Information-->
                                <th id="{{ $tutor->id }}">
                                    <div id="{{ $tutor->user_id }}" style="width:110px"  class="hbordered">

                                        <div class="photo pt-1">
                                            @if ($tutor->filename == null)
                                                <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" width="60px">
                                            @else 
                                                <img src="{{ Storage::url("$tutor->original") }}" class="img-fluid border" alt="profile photo" width="60px">
                                            @endif
                                        </div>

                                        <div class="tutor-name">
                                            <a href="javascript:void(0);" onclick="window.open('viewtutor/{{ $tutor->user_id }}','家庭教師の詳細')" class="text-danger font-weight-normal">
                                                @if (isset($tutor->user->japanese_firstname)) {!! $tutor->user->japanese_firstname !!}@endif <br/>
                                                @if (isset($tutor->user->firstname)) {!! "(" . $tutor->user->firstname . ")" !!} @endif
                                            </a>
                                        </div>

                                    </div>
                                </th>

                                @foreach($lessonSlots as $lessonSlot)
                                <td>
                                    @php
                                        $startTimePH = date('H:i', strtotime($lessonSlot['startTime'] ." - 1 hour "));

                               

                                        if ($lessonSlot['startTime'] >= '24:00') {
                                            $date = $nextDay;
                                        } else {
                                            $date = $dateToday;
                                        }
                                    @endphp

                                    @if (isset($schedules[$tutor->user_id][$date][$startTimePH]))

                                        @php
                                            $scheduleID = $schedules[$tutor->user_id][$date][$startTimePH]['id'];
                                            $scheduleMemberID =  $schedules[$tutor->user_id][$date][$startTimePH]['member_id'];


                                            $status = $schedules[$tutor->user_id][$date][$startTimePH]['status'];  
                                        @endphp
                                      
                                        @if ($status == "TUTOR_SCHEDULED")

                                            <div class="button_{{ $scheduleID }}">

                                                <a class="bookTutorSchedule" onclick="book('{{ $scheduleID }}', '{{ Auth::user()->id }}')" href="javascript:void(0)">予約</a>

                                                <div class="cancel" style="display:none">
                                                    <div id="{{ $scheduleID }}" style="float:right">
                                                        <a href="javascript:void(0)" onClick="cancel('{{ $scheduleID }}')"><img src="{{ url('/images/btnDelete.png') }}"></a>
                                                    </div>
                                                    <br />
                                                    <a href="javascript:void(0)">済</a>
                                                </div>
                                            </div>

                                        @elseif($status == 'CLIENT_RESERVED' || $status == 'CLIENT_RESERVED_B')                                            

                                            <div class="button_{{$scheduleID}}">

                                                <a class="bookTutorSchedule" onclick="book('{{$scheduleID}}','{{ Auth::user()->id }}')" href="javascript:void(0)" style="padding:15px; display:none">予約</a>                                               

                                                @if ($member->user_id == $scheduleMemberID)
                                                    <div class="cancel">
                                                        <div id="{{ $scheduleID }}" style="float:right">
                                                            <a href="javascript:void(0)" onClick="cancel('{{$scheduleID}}')"><img src="{{ url('/images/btnDelete.png') }}"></a>
                                                        </div>
                                                        <br />
                                                        <a href="javascript:void(0)" onClick="cancel('{{$scheduleID}}')">済</a>
                                                    </div>
                                                @else 
                                                    <div id="scheduleID_{{$scheduleID}}" class="gen-med">済他</div>
                                                @endif

                                            </div>

                                        @elseif($status == 'SUPPRESSED_SCHEDULE' )
                                            <div id="scheduleID_{{$scheduleID}}" class="gen-med">{{'済他'}}</div>

                                        @elseif($status == 'CLIENT_NOT_AVAILABLE')
                                            <div id="scheduleID_{{$scheduleID}}"  class="gen-med">{{'欠席'}}</div>

                                        @elseif($status == 'TUTOR_CANCELLED')
                                            <div id="scheduleID_{{$scheduleID}}"  class="gen-med">{{ "予約" }}</div>

                                        @elseif ($status == "COMPLETED")                                         
                                            <a id="scheduleID_{{$scheduleID}}" href="javascript:void(0)"  class="gen-med">completed</a>

                                        @endif
                                    @endif


                                </td>
                                @endforeach

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


@section('scripts')
@parent
<script type="text/javascript">
    var api_token = "{{ Auth::user()->api_token }}";

    function disablePreviousDates() {
        var input = document.getElementById("dateToday");
        var today = new Date();
        var day = today.getDate();

        // Set month to string to add leading 0
        var mon = new String(today.getMonth()+1); //January is 0!
        var yr = today.getFullYear();
        if(mon.length < 2) { 
            mon = "0" + mon;
        }
        var date = new String( yr + '-' + mon + '-' + day );
        input.disabled = false; 
        input.setAttribute('min', date);
    }



    function book(scheduleID, memberID) {      
        if (confirm('予約してもいいですか？')) {
            $.ajax({
                type: 'POST',
                url: 'api/book?api_token=' + api_token,
                data: {
                    scheduleID: scheduleID,
                    memberID: memberID
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    //$("#msg").html(data.msg);

                    console.log(scheduleID);

                    $('.button_' + scheduleID + ' .bookTutorSchedule').hide();
                    $('.button_' + scheduleID + ' .cancel').show();
                }
            });
        } else {
            return false;                        
        }
    }


    function cancel(id) {

        if (confirm('このレッスンをキャンセルしてもいいですか？')) {

            $.ajax({
                type: 'POST',
                url: 'api/cancelSchedule?api_token=' + api_token,
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    //$("#msg").html(data.msg);
                    $('.button_' + id + ' .bookTutorSchedule').show();
                    $('.button_' + id + ' .cancel').hide();
                }
            });
        } else {
            return false;
        }
    }

    window.addEventListener('load', function() {
        disablePreviousDates();
    });

</script>
@endsection

@section('styles')
@parent
    <style>
        .table-schedules td a,  .table-schedules td a:hover {
            color: #c60000;
            font: 14px Arial;
            text-align: center;            
        }

        .table-schedules td {
            min-width: 44px;
            padding: 0px;
            width: 44px;
            padding-top:38px;
        }

        .table-schedules td .gen-small {
            font: bold 11px Arial;
        }


        .table-schedules td.schedTime {
            background: #d0e8f7;
            text-align: center;
            font: bold 12px Arial;
            vertical-align: top;
            width: 37px;
            height: 30px;
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            top: -1px;
            padding-top:0px;
            padding-left:0px;
            padding-right:0px;
            padding-bottom:0px;
            z-index: 99;
        }

        .table-schedules th.schedTime .bordered {
            background: #d0e8f7;
            height: 40px;
            padding: 0px;
            border-bottom: 3px solid #72add2;
            border-right: 3px solid #72add2;        
        }
        

        .table-schedules td .bordered {
            border-top: 0px;
            border-left: 0px;
            border-bottom: 3px solid #72add2;
            border-right: 1px solid #fff;
            width: 100%;
            padding-top: 5px;
            padding-bottom: 5px; 
            height: 40px;

        }

        .table-schedules thead th {
            height: 40px;
            /*border-bottom: 3px solid #72add2;*/
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            background-color: #fff;
            z-index: 999;
            top: -1px;
            padding:0px;
        }

        .table-schedules tbody th, .static {
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            left: -1px;
            background-color: #fff;
            border: 0px;
            padding: 0px;
            margin: 0px;
            height: 60px;        
            z-index: 100;
        }

        .table-schedules tbody th .hbordered {
            border-top: 0px;
            border-left: 0px;
            border-right: 3px solid #72add2;
            border-bottom: 1px solid #72add2;
            width: 100%;
            height: 100%;
            padding-left: 5px;
            padding-right: 5px;
        }

        .table-schedules td , .table-schedules td div.gen-med {
            vertical-align: top;
            height: 39px;
            width: 37px;
            font: 14px Arial;
            text-align: center;
        }


    </style>
@endsection    