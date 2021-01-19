@extends('layouts.esi-app')
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

                    <div class="card-body scrollable-x p-0">
                        <table class="table table-bordered table-schedules">
                            <tr>
                                <td class="schedTime"></td>
                                @foreach($lessonSlots as $lessonSlot)
                                <td class="schedTime">
                                    <div>
                                        <div class="text-small">{{ $lessonSlot['startTime'] }}</div>
                                        <div class="text-small">{{ $lessonSlot['endTime'] }}</div>
                                    </div>
                                </td>
                                @endforeach
                            </tr>


                            @foreach($tutors as $tutor)
                            <tr>
                                <!--[start] Tutor Information-->
                                <td id="{{ $tutor->id }}">
                                    <div id="{{ $tutor->user_id }}" style="width:125px">
                                        @if (isset($tutor->user->firstname))
                                            {!! $tutor->user->firstname !!}
                                        @endif
                                    </div>
                                </td>

                                @foreach($lessonSlots as $lessonSlot)
                                <td>
                                    @php
                                        $startTimePH = date('H:i', strtotime($lessonSlot['startTime'] ." - 1 hour "));

                                        if ($lessonSlot['startTime'] == '23:00' || $lessonSlot['startTime'] == '23:30') {
                                            $date = $nextDay;
                                        } else {
                                            $date = $dateToday;
                                        }
                                    @endphp


                                    @if (isset($schedules[$tutor->id][$date][$startTimePH]))

                                        @php
                                            $scheduleID = $schedules[$tutor->id][$date][$startTimePH]['id'];
                                            $scheduleMemberID =  $schedules[$tutor->id][$date][$startTimePH]['member_id'];
                                            $status = $schedules[$tutor->id][$date][$startTimePH]['status'];  
                                        @endphp

                                      
                                        @if ($status == "TUTOR_SCHEDULED")

                                            <div class="button_{{ $scheduleID }}">

                                                <a class="bookTutorSchedule" onclick="book('{{ $scheduleID }}', '{{ Auth::user()->id }}')" href="javascript:void(0)">予約</a>

                                                <div class="cancel" style="padding:15px; display:none">
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
                                                        <a href="javascript:void(0)">済</a>
                                                    </div>
                                                @endif

                                            </div>

                                        @elseif($status == 'SUPRESSED_SCHEDULE' )
                                            <div id="scheduleID_{{$scheduleID}}">{{'済他'}}</div>

                                        @elseif($status == 'CLIENT_NOT_AVAILABLE')
                                            <div id="scheduleID_{{$scheduleID}}">{{'欠席'}}</div>

                                        @elseif($status == 'TUTOR_CANCELLED')
                                            <div id="scheduleID_{{$scheduleID}}">{{ "予約" }}</div>

                                        @elseif ($status == "COMPLETED")                                         
                                            <a id="scheduleID_{{$scheduleID}}" href="javascript:void(0)">completed</a>

                                        @endif
                                    @endif


                                </td>
                                @endforeach

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
            // Do nothing!
            //console.log('Thing was not saved to the database.');
        }
    }


    function cancel(id) {
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
    }

    window.addEventListener('load', function() {
        disablePreviousDates();
    });

</script>
@endsection
