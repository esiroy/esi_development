@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User</li>
            </ol>
        </nav>

        <div class="container pb-5">
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



                <!-- ANNOUNCEMENTS -->
                <div class="col-md-9">

                        @if (isset($announcement->body))
                            <h1 class="callout">お知らせ</h1>                        
                            <div class="blueBrokenLineBox announcements px-4 py-4">                    
                                {!! html_entity_decode($announcement->body) ?? '' !!}
                            </div>
                        @else 
                          
                        @endif
                    <div class="row">
                        <div class="col-md-12 mt-3">

                            <a href="{{ url('lessonrecord') }}">
                                <img src="{{ url('images/btnYellow3.png') }}" alt="受講履歴/添削履歴" title="受講履歴/添削履歴">
                            </a>

                            <a href="{{ url('memberschedule') }}">                                
                                <img src="{{ url('images/btnBlue.gif') }}" alt="レッスンの予約" title="レッスンの予約">
                            </a>

                            <a href="JavaScript:newPopup('http://writing.mytutor-jpn.info/');" data-toggle="modal" data-target="#writingServiceModal" >
                                <img src="{{ url('images/newBtn3.png') }}" alt="添削くん" title="添削くん">
                            </a>                      
                        </div>
                    </div>

                    <div class="grayBackgroundBox mt-4 pt-3 px-2">
                        <p>今日のレッスンはいかがでしたか？ 今後の円滑な運営と質の高いレッスン のご提供のため、何かお気づきの点がありましたら アンケートにお答え下さい！</p>
                        <p class="text-right">
                            <a href="{{ url('lessonrecord?display=none') }}">
                                <img src="images/btnRed2.gif" alt="受講履歴　評価e" title="受講履歴　評価">
                            </a>
                        </p>
                    </div>



                    <div class="reservationTable mt-4">
                            <!--[start reservation table -->
                            <table width="100%" cellspacing="0" cellpadding="5" border="0" align="center">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="heading">
                                            <a href="https://www.mytutor-jpn.com/info/2020/0526183735.html" target="_blank">担任講師による固定レッスン</a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($desiredSchedules as $schedule)
                                    <tr class="border-bottom">
                                        <td class="text-center font-weight-normal">{{ $schedule->day ?? '' }}</td>
                                        <td class="text-center font-weight-normal">
                                            {{ date('H:i', strtotime($schedule->desired_time)) }} 
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>


                    @php                     
                    /*
                    <div id="member-lesson-schedules" class="card esi-card mt-3">                        
                        <div class="card-header esi-card-header text-center">                            
                                レッスンの予約
                        </div>
                        <div class="card-body">   
                            <div id="member-scheduler" class="mt-3">
                                <p>
                                    固定レッスン以外はこちらから予約して下さい
                                    <img src="images/btnHand.jpg">
                                    <a href="memberschedule"><img src="images/btnRed4.gif"></a>
                                </p>
                                <p>30 分前まで予約可能です</p>
                            </div>
                        </div>
                    </div>
                    */
                    @endphp

                    <!--[start] lesson lists-->
                    <a name="reservation_section">

                    <div class="card mt-4" style="">
                        <div class="card-header esi-card-header text-center">
                            予約表
                            <small style="font-size:11px; color:#333">予約は最大15コマまでとなります</small>
                        </div>

                        <div class="card-body px-3">
                            初めての講師の場合、講師からSkype(ZOOM)コンタクトリクエストがあります。 レッスン時間の15分前にSkype(ZOOM)を立ち上げ承認してコールをお待ちください。

                            <table id="memberReservations" cellspacing="0" cellpadding="9" class="tblRegister mt-3" width="100%">
                                <tbody>
                                    <tr>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;" colspan="2">Tutor</th>
                                        <th style="text-align: center;">講師への連絡</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                    <!-- COUNTER FOR PAGINATION -->
                                    @foreach ($reserves as $reserve)

                                    <tr class="row_reserve_{{$reserve->id}} reserved_items">
                                        <td style="text-align: center;">

                                            @if (date('H', strtotime($reserve->lesson_time)) == '00') 
                                                {{  date('Y年 m月 d日 24:i', strtotime($reserve->lesson_time ." - 1 day")) }} - {{  date('24:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
                                            @else 
                                                {{  date('Y年 m月 d日 H:i', strtotime($reserve->lesson_time)) }} - {{  date('H:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
                                            @endif
                                            
                                        </td>
                                        <td style="text-align: center;" colspan="2">                                            
                                            @php
                                                $tutor = \App\Models\Tutor::where('user_id', $reserve->tutor_id)->first();
                                            @endphp

                                            @if (isset($tutor->user_id))
                                                <div id="{{ $tutor->user_id }}" class="tutor_name">{{ $tutor->user->firstname ?? " - " }} {{ $tutor->user->lastname ?? "" }}</div>
                                            @endif

                                        </td>
                                        <td style="text-align: center;">
                                            <!--<a href="javascript:void(0)" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $reserve->id }}">-->

                                            <a href="javascript:void(0)" onClick="openMemo('{{ $reserve->id }}')" data-toggle="modal" data-target="tutorMemoReplyModal" data-id="{{ $reserve->id }}">
                                                <img src="images/iEmail.jpg" border="0" align="absmiddle"> 講師への連絡
                                            </a>
                                        </td>
                                        <td style="text-align: center;">

                                            @php                                                
                                                $date_now =  date("Y-m-d H:i:s");
                                                $valid_time = date("Y-m-d H:i:s", strtotime($date_now ." + 3 hours"));
                                                $lessonTime = date("Y-m-d H:i:s", strtotime($reserve->lesson_time));
                                            @endphp
                                            
                                            @if ($reserve->schedule_status == "CLIENT_RESERVED_B")
                                                <a href="javascript:void(0)" onClick="cancelSchedule('{{$reserve->id}}')"><img src="{{ url('images/btnBlue2.gif') }}" alt="欠席する" title="欠席する"></a>                                                
                                            @else 
                                                @if ($valid_time <= $lessonTime) 
                                                    <!--valid time here since it is greater that 3 hours) -->
                                                    <a href="javascript:void(0)" onClick="deleteSchedule('{{$reserve->id}}')"><img src="{{ url('images/btnRed3.gif') }}" alt="取り消し" title="取り消し"></a>
                                                @else 
                                                    <a href="javascript:void(0)" onClick="cancelSchedule('{{$reserve->id}}')"><img src="{{ url('images/btnBlue2.gif') }}" alt="欠席する" title="欠席する"></a>                                                
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="5">
                                            <div class="float-right">
                                                {{ $reserves->links() }}

                                              
                                            </div>
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                        </div><!--[end] card body-->

                    </div><!--[end] card -->

                </div><!--[end] column-->
            </div><!--[end] row -->
        </div><!--[end] container -->
    </div> <!--[end] esi-box -->
</div>

 @include('modules.member.popup.content')
 @include('modules.member.popup.memo')
 @include('modules.member.popup.memoReply')
 @include('modules.member.popup.loading')
 @include('modules.member.popup.memoSent')
 
@endsection


@section('scripts')
@parent
<script type="text/javascript">   
    let api_token = "{{ Auth::user()->api_token }}";
    
    let currentPage = "{{ app('request')->input('page') }}";    
    let previousPage = currentPage - 1;
    let lastPage = "{{ $reserves->lastPage() }}";
    let previousPageURL = "{{ url('home?page=') }}" + previousPage +"#reservation_section";

    window.addEventListener('load', function() 
    {        
        //$('#loadingModal').modal('show'); //test      

        jQuery.ajaxSetup({
            beforeSend: function() 
            {
                //hide tutor memo modal first
                $('#tutorMemoModal').modal('hide')      

                $('#loadingModal').modal('show');
            },
            complete: function(){
                $('#loadingModal').modal('hide');
            },
            success: function() {
                $('#loadingModal').modal('hide');
            }
        });

    });

    function cancelSchedule(id)
    {
        if (confirm('このレッスンをキャンセル（欠席）されるとポイントは消化されます。キャンセル(欠席）しますか？')) 
        {
            $.ajax({
                type: 'POST', 
                url: 'api/cancelSchedule?api_token=' + api_token,
                data: {
                    id: id
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(data) {              
                    //total credits
                    $('#total_credits').text(data.credits);                      
                    $('.row_reserve_' + id ).hide();
                    $('#loadingModal').modal('hide');          

                    if (isCurrentResevationPageEmpty()) {
                        if (currentPage > 1) {
                            window.location.replace(previousPageURL);
                        }
                    }
                
                }
            });
        }
    }

    function deleteSchedule(id) {
        if (confirm('このレッスンをキャンセルしてもいいですか？')) 
        {
            $.ajax({
                type: 'POST', 
                url: 'api/cancelSchedule?api_token=' + api_token,
                data: {
                    id: id
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(data) {              
                    //total credits
                    $('#total_credits').text(data.credits);                      
                    $('.row_reserve_' + id ).hide();
                    $('#loadingModal').modal('hide');

                    if (isCurrentResevationPageEmpty()) {
                        if (currentPage > 1) {
                            window.location.replace(previousPageURL);
                        }                        
                    }

                }
            });
        }        
    }

    function isCurrentResevationPageEmpty() 
    {
        var rowCount = $('#memberReservations tr.reserved_items:visible').length;
        if (rowCount == 0) {
            return true;
        } else {
            return false
        }        
    }


    function openMemo(id)
    {   
        id = id;
        getMemo(id);
    }

    function getMemo(scheduleID) 
    {
        $.ajax({
            type: 'POST', 
            url: 'api/getMemo?api_token=' + api_token,
            data: {
                'scheduleID': scheduleID,
            }, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found getting memo: " + error);
            },            
            success: function(data) { 
                $('#message').val(data.memo);
                $('#scheduleID').val(scheduleID);
                $('#loadingModal').modal('hide');

                $('#teacherImage').attr('src', data.tutorImage)

                if (data.memo) {
                    $('#tutorMemoReplyModal #scheduleID').val(scheduleID);
                    $('#tutorMemoReplyModal #message').html(data.memo);                    
                    $('#tutorMemoReplyModal').modal('show')    
                } else {
                    //this is where they create a new thread                    
                    $('#tutorMemoModal').modal('show')    
                }  
            },
        });
    }

    function sendMemo(scheduleID, message) {
        $.ajax({
            type: 'POST', 
            url: 'api/sendMemo?api_token=' + api_token,
            data: {
                'scheduleID': scheduleID,
                'message': message
            }, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found getting memo: " + error);    
            },
            success: function(data) {
                $('#tutorMemoModal').modal('hide')   

                $('#tutorMemoSentModal').modal('show');

                setTimeout(function() { $('#tutorMemoSentModal').modal('hide') }, 1500);                
                
            }
        });
    }

    function closePopUp(url) {
        $('#writingServiceModal').modal('hide');
        window.open(url,'popUpWindow','height=600,width=720,left=50,top=50,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes')
        return false;
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
                alert("Error Found while getting memo conversations: " + error);
            },            
            success: function(data) 
            {
               let replies = data.conversations;
               replies.forEach(createReplyBubble);
            },
        });
    }

    function createReplyBubble(item, index) 
    {       
        let teacherProfileImage = $('#teacherProfile').html();

        if (item.message_type === "MEMBER") {
            addMemberReplyBubble(teacherProfileImage, item.message);
        } else {
            addTeacherReplyBubble(teacherProfileImage, item.message);
        }    
        
    }
      
          

    /*[START] MODAL  */  
    function addTeacherReplyBubble(image, message) 
    {
        if (message) {        
            $( "#teacherReplies" ).append( "<div class='row'> <div class='col-md-3'>"+ image +"</div>    <div class='col-md-9'><div class='teacher-speech-bubble'>" +  message + " </div> </div> </div>");         
            setTimeout(() => {  
                    var element = document.getElementById("teacherReplies");
                    element.scrollTop = element.scrollHeight;
            },100);
        }
    }

    function addMemberReplyBubble(image, message) 
    {
        if (message) {        
            $( "#teacherReplies" ).append( "<div class='row'> <div class='col-md-9'> <div class='member-speech-bubble'>"+ message +" </div></div>    <div class='col-md-3'>  " +  image + "  </div> </div>");         
            setTimeout(() => {  
                    var element = document.getElementById("teacherReplies");
                    element.scrollTop = element.scrollHeight;
            },100);
        }
    }    
    /*[END MODAL] */


  

    function sendMemberReply(scheduleID, message) 
    {

        console.log(scheduleID, message);

        $.ajax({         
            type: 'POST',
            dataType: 'json',
            cache : false,
            url: "{{ url('api/sendMemberReply?api_token=') }}" + api_token,
            data: {
                'scheduleID': scheduleID,
                'memberID': {{ Auth::user()->id }},
                'message': message,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found while getting teacher unread memo replies: " + error);
            },      
            beforeSend: function() {   
                $('#loadingModal').modal('hide');
            },               
            success: function(data) 
            {
                let teacherProfileImage = $('#memberProfile').html();

                addMemberReplyBubble(teacherProfileImage, message) 
            },
        });
                
    }

    function getUnreadTeacherMessages(scheduleID) 
    {
        //console.log("heartbeat! " + scheduleID);
        $.ajax({         
            type: 'POST',
            dataType: 'json',
            cache : false,
            url: "{{ url('api/getUnreadTeacherMessages?api_token=') }}" + api_token,
            data: {
                'scheduleID': scheduleID,
                'memberID': {{ Auth::user()->id }},
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found while getting teacher unread memo replies: " + error);
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



    window.addEventListener('load', function () 
    {
        var button;        
        var modal;
        var intervalId;

        //Save to Schedule Item Memo
        $('#saveTutorMemo').on('click', function() 
        {           
            let scheduleID =  $('#scheduleID').val();
            let message = $('#message').val();           
            sendMemo(scheduleID, message);
        });

        $('#btnReply').on('click', function(){
            let scheduleID =  $('#scheduleID').val();
            let message = $('#memberTextReply').val()
            sendMemberReply(scheduleID, message);

            $('#memberTextReply').val("");
        });


        //Detect Memo Reply Modal
        $('#tutorMemoReplyModal').on('show.bs.modal', function (event) 
        {            
            let scheduleID =  $('#scheduleID').val();               
            //clear teacher replies 
            $( "#teacherReplies" ).text(""); 
            //get all memo replies
            getMemoConversations(scheduleID);

            //interval unread message fetching
            intervalId = window.setInterval(function(){
                getUnreadTeacherMessages(scheduleID)
            }, 3000);
        });

        $('#tutorMemoReplyModal').on('hide.bs.modal', function (event) 
        {            
            clearInterval(intervalId);
           // console.log("closed heaertbeat")
        });
    });

</script>
@endsection



@section('styles')
@parent
<style>
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
    margin: 5px 0px 15px;
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