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

                                    <!--@PLOTTER
                                            {{ date('m/d/Y', strtotime($dateFrom ." + $ctr day"))}} - {{ $timeSlot['startTime'] }}
                                            {{  $dateView  }}
                                        -->

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
                                                    <a href="{{ route('admin.reportcard.index', ['scheduleitemid' => $lessons[$dateView][$timeSlot['startTime']]['id'] ]) }}">Grade</a>
                                                </div>
                                                @endif

                                                @if ($checkStatus == 'client_reserved' || $checkStatus == 'client_reserved_b' || $checkStatus == 'completed')

                                                @endif
                                            </div>

                                            @if (isset($lessons[$dateView][$timeSlot['startTime']]['memo']))

                                            @if ($lessons[$dateView][$timeSlot['startTime']]['memo'] != '')

                                            <div id="memoContainer" class="btn-container2 pt-2">
                                                <!-- open memo -->
                                                <a href="javascript:void()" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $lessons[$dateView][$timeSlot['startTime']]['id'] }}">
                                                    <div id="memoContent" style="display:none">
                                                        {{ $lessons[$dateView][$timeSlot['startTime']]['memo']}}
                                                    </div>
                                                    <img src="{{ url('images/iEmail.jpg') }}" border="0" align="absmiddle">
                                                </a>
                                            </div>

                                            @endif
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
    @include('admin.modules.tutor.includes.memo')
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    var api_token = "{{ Auth::user()->api_token }}";

    function getMemo(scheduleID) {
        $.ajax({
            type: 'POST', 
            url: "{{ url('api/getMemo?api_token=') }}" + api_token,
            data: {
                'scheduleID': scheduleID,
            }, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found getting memo: " + error);
            },            
            success: function(data) 
            {            
                $('#tutorMemoModal #scheduleID').val(scheduleID);
                $('#tutorMemoModal #message').html(data.memo);

                $('#memberImage').attr('src', data.memberImage)
            },
        });
    }

    /* FUNCTIONS */
    function getMemoConversations(scheduleID) 
    {
        $.ajax({
         
            type: 'POST',
            dataType: 'json',
            cache : false,
            url: "{{ url('api/getMemoConversations?api_token=') }}" + api_token,
            data: {
                'scheduleID': scheduleID,
                'tutorID': {{ Auth::user()->id }},
            }, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found while sending memo: " + error);
            },            
            success: function(data) 
            {
               let replies = data.conversations;
               replies.forEach(createReplyBubble);
            },
        });
    }



    function getUnreadMemberMessages(scheduleID) 
    {
        //console.log("heartbeat! " + scheduleID);
        $.ajax({         
            type: 'POST',
            dataType: 'json',
            cache : false,
            url: "{{ url('api/getUnreadMemberMessages?api_token=') }}" + api_token,
            data: {
                'scheduleID': scheduleID,
                'memberID': {{ Auth::user()->id }},
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found while getting Member unread memo replies: " + error);
            },      
            beforeSend: function() {   
                $('#loadingModal').modal('hide');
            },               
            success: function(data) 
            {
                let replies = data.conversations;
                replies.forEach(createReplyBubble);             
                console.log(data.message)
            },
        });
    }    




    /*[START] MODAL  */  
    function addTeacherReplyBubble(image, message) 
    {
        $( "#teacherReplies" ).append( "<div class='row'> <div class='col-md-9'><div class='member-speech-bubble'>"+ message +"</div></div><div class='col-md-3'>" + image + "</div> </div>");         
        setTimeout(() => {  
                var element = document.getElementById("teacherReplies");
                element.scrollTop = element.scrollHeight;
        },100);

    }

    function addMemberReplyBubble(image, message) 
    {
        if (message) {        
            $( "#teacherReplies" ).append( "<div class='row'> <div class='col-md-3'>"+ image +"</div>    <div class='col-md-9'><div class='teacher-speech-bubble'>" +  message + " </div> </div> </div>");         
            setTimeout(() => {  
                    var element = document.getElementById("teacherReplies");
                    element.scrollTop = element.scrollHeight;
            },100);
        }
    } 

    /*[END MODAL] */


    function createReplyBubble(item, index) 
    { 
        if (item.message_type === "MEMBER") {
            let memberProfileImage = $('#memberProfile').html();
            addMemberReplyBubble(memberProfileImage, item.message);
        } else {
            let teacherProfileImage = $('#teacherProfile').html();
            addTeacherReplyBubble(teacherProfileImage, item.message);
        }    
    }
    

    window.addEventListener('load', function () 
    {
        var button;
        var id;
        var modal;
        var intervalId;

        $('#tutorMemoModal').on('show.bs.modal', function (event) 
        {   
            button = $(event.relatedTarget) // Button that triggered the modal
            let scheduleID = button.data('id') // Extract info from data-* attributes

            //clear teacher replies 
            $( "#teacherReplies" ).text("");
            $('#tutorMemoModal #message').html("");
            

            getMemo(scheduleID)
            getMemoConversations(scheduleID);

            //interval unread message fetching
            intervalId = window.setInterval(function(){
                getUnreadMemberMessages(scheduleID)
            }, 3000);            
            
        });

        $('#tutorMemoModal').on('hide.bs.modal', function (event) 
        {
            clearInterval(intervalId);
        });

        $("textarea").keydown(function(e) {
            var code = e.keyCode ? e.keyCode : e.which;
            if (code == 13) {  // Enter keycode
                $("#btnReply").click();
            }
        });

    });


</script>
@endsection



@section('styles')
@parent
<style>

.table-tutor-schedules {
    background: #ffffff;
    border: 1px solid #0076be;
}

.table-tutor-schedules thead td.scheduleTimeList {
    border-bottom: 3px solid #72add2;
}

.table-tutor-schedules .tutorSchedules {
    border-left: 1px solid #7dbae0;
}

.table-tutor-schedules .dateContainer {
    width: 100%; 
    background-color: #f0ebc1;
}

.table-tutor-schedules .dateContainer .day {
    background-color: #f0ebc1;
    background-color: #f3e77f;
}

.table-tutor-schedules tbody td {
    padding: 0px;    
    padding-top: 8px;
    padding-left: 1px;
    padding-right: 4px;
}

.table-tutor-schedules td.scheduleTimeList {
    min-width: 75px;
    width: 75px;
    padding: 0px;
}

.table-tutor-schedules td.scheduleTimeList .class-schedule-container {
    padding: 10px 2px 0px;
   
}

.table-tutor-schedules td.scheduleTimeList .class-schedule-container .class-schedule-start,
.table-tutor-schedules td.scheduleTimeList .class-schedule-container .class-schedule-end
 {  
    font-size:11px;
    font-weight:bold;
    line-height: 1em;
}

.table-tutor-schedules td.schedules {
    min-width: 142px;
    padding: 0px;
    background-color:#f3e77f; 
    border: 1px solid #d0e8f7;
    border-bottom: 3px solid #72add2; 
    border-right: 1px solid #ffffff;
    vertical-align: top;    
}

.table-tutor-schedules .client 
{
    width: 142px;
}

.table-tutor-schedules .client a{
    font-size: 10px;
}

/*Modal Memo Reply*/
.member-speech-bubble {
	position: relative;
	background: #00ff91;
	border-radius: .4em;
    padding-right:30px;
    position: relative;
    background: #00ff91;
    border-radius: .4em;
    padding: 10px 20px 10px;
    float: right;
    margin: 20px -10px 0px;
    text-align: right;
}

.member-speech-bubble:after {
	content: '';
	position: absolute;
	right: 0;
	top: 50%;
	width: 0;
	height: 0;
	border: 18px solid transparent;
	border-left-color: #00ff91;
	border-right: 0;
	border-bottom: 0;
	margin-top: -10px;
	margin-right: -14px;
}

#teacherReplies .row 
{
    /*border: 1px dotted rgb(0, 132, 255);*/
    
    padding: 3px 0px 8px;
}


.teacher-speech-bubble {
	position: relative;
	background: #3e4042;
	border-radius: .4em;
    color: #fff;
    position: relative;
    border-radius: .4em;
    padding-right: 30px;
    margin: 5px 0px 5px;
    padding: 10px;
    display: inline-block;      
}

.teacher-speech-bubble:after {
	content: '';
	position: absolute;
	left: 0;
	top: 50%;
	width: 0;
	height: 0;
	border: 20px solid transparent;
	border-right-color: #3e4042;
	border-left: 0;
	border-bottom: 0;
	margin-top: -10px;
	margin-left: -15px;
}


</style>
@endsection