<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>{{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(2))->replace('-', ' ') ) ?? '' }}</title>
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin />
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>

<body class="bg-gray">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                    <img src="{{ url("images/mytutor_logo.png") }}" alt="{{ config('app.name', 'My Tutor') }}" alt="{{ config('app.name', 'My Tutor') }} administratrion panel">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                            




                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>

                    <div class="text-right w-100">
                    
                        <a class="blue pr-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ ucfirst(Auth::user()->firstname) }}

                            @if(Auth::user()->user_type == 'ADMINISTRATOR')
                              {{ '(Administrator)' }}                            
                            @elseif (Auth::user()->user_type == 'TUTOR')                                
                                {{ '(Tutor)' }}
                            @elseif (Auth::user()->user_type == 'MANAGER')   
                                {{ '(Manager)' }}
                            @elseif (Auth::user()->user_type == 'AGENT')                               
                                {{ '(Agent)' }}                              
                            @endif

                            <span class="caret"></span>
                        </a>

                        @if (Auth::user()->user_type == 'TUTOR')  
                            @php                
                                $tutor = App\Models\Tutor::where('user_id', Auth::user()->id )->first();
                                $ctr = 0;
                                $undreadMessages = 0;
                                $scheduleItems = new \App\Models\ScheduleItem;
                                $reservations = $scheduleItems->getTutorAllActiveLessons($tutor);                                                  
                            @endphp

                            <span class="divide">|</span>

                            <div class="tutor-inbox" style="display:inline-block">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle blue" data-toggle="dropdown">Inbox <span id="total_unread_message" class="text-success">({{ $undreadMessages }})</span></a>

                                    <div class="dropdown-menu" style="overflow:auto; min-height:50px; max-height:450px; left: -265px; width:400px">
                                        @foreach ($reservations as $reserve)
                                            @php      
                                                $ctr++;
                                                $userImageObj = new \App\Models\UserImage;
                                                $userImage = $userImageObj->getTutorPhotobyID($reserve->tutor_id); 
                                                $memoReply = new \App\Models\MemoReply;
                                                $latestReplyCount = $memoReply->where('schedule_item_id', $reserve->id)->where('is_read', false)->where('message_type', "MEMBER")->count();   
                                                $latestReply = $memoReply->where('schedule_item_id', $reserve->id)->where('message_type', "MEMBER")->orderBy('created_at', 'DESC')->first();  
                                            @endphp

                                            @if ($latestReplyCount == 0)  
                                                @php 
                                                    $display = "none";                                                
                                                
                                                @endphp
                                            @else 
                                                @php 
                                                    $display = "all"; 
                                                    $undreadMessages = $undreadMessages + $latestReplyCount;
                                                @endphp                                                
                                            @endif

                                            <div id="inbox-{{$reserve->id }}" style="display:{{$display}}"  class="row px-0 mx-0">
                                                @if ($ctr > 1)
                                                    <hr>
                                                @endif

                                                <div class="col-md-3">                                               
                                                    <a href="#" class="dropdown-item small p-0">
                                                        @if ($userImage == null)
                                                            <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" style="width:100%">
                                                        @else 
                                                            <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo"  style="width:100%">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="col-md-9">
                                                    <span id="inbox-message-{{ $reserve->id }}">
                                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $reserve->id }}">
                                                                @if (date('H', strtotime($reserve->lesson_time)) == '00') 
                                                                    {{  date('Y年 m月 d日 24:i', strtotime($reserve->lesson_time ." - 1 day")) }} - {{  date('24:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
                                                                @else 
                                                                    {{  date('Y年 m月 d日 H:i', strtotime($reserve->lesson_time)) }} - {{  date('H:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
                                                                @endif
                                                            </a>
                                                            <br>
                                                            <span class="message small">
                                                                {{ $latestReply->message ?? ""}}
                                                            </span>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if ($undreadMessages == 0)
                                        <div id="unreadMessages" class="text-center small pt-3 pb-3 "> No Unread Message(s) </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif



                        <span class="divide">|</span>
                        <a class="blue pl-2 pr-2" href="{{ route('admin.settings.index') }}">
                            {{ __('Settings') }}
                        </a>

                        <span class="divide">|</span>
                        
                        <a class="red pl-2 pr-2" href="{{ route('admin.AdminLogout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    <br/>           

                    <div class="text-right w-100 pr-4 pt-2">
                      <a class="red pr-2" href="skype:netenglish.cebumanager?call"><span class="flag-ph pr-2"></span> 
                      <span>netenglish.cebumanager</span></a>
                    </div>

                    <form id="logout-form" action="{{ route('admin.AdminLogout') }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        @include('layouts.menu.main')

        <main class="main-container">
            @yield('content')
        </main>
        
        <footer class="container py-4 px-0 bg-light">
            <div class="container border-top">
                <div class="row">
                    <div class="col-12 text-center py-3">
                        <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                            {{ config('app.name', 'My Tutor') }}
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    


    <script type="text/javascript" src="https://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>


    @if (Auth::user()->user_type == 'TUTOR')
        @include('admin.modules.tutor.includes.memo')

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
                        $('#tutorMemoModal #lessonTime').html(data.lesson_time);
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
                        //console.log(data.message)
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
            
            function getTutorInbox() {
                //console.log("heartbeat! " + scheduleID);
                $.ajax({         
                    type: 'POST',
                    dataType: 'json',
                    cache : false,
                    url: "{{ url('api/getTutorInbox?api_token=') }}" + api_token,
                    data: {
                        'tutorID': {{ Auth::user()->id }},
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
                        //clean this inbox
                        $('.dropdown-menu').children('div').each(function () {
                            $(this).css("display", "none");
                        });

                        if (data.unread == 0) {
                            console.log("zero messages 1");
                        }

                        if (data.unread === 0) {
                            console.log("zero messages");
                            $("#unreadMessages").show();
                        }                    

                        $("#total_unread_message").text("("+ data.unread + ")");

                        let inbox = data.inbox;
                        inbox.forEach(updateTutorInboxList);

                        //console.log(data.message);              
                    },
                });
            }        


            function updateTutorInboxList(item, index) 
            {
                //show
                if(document.getElementById('inbox-message-'+ item.schedule_item_id))
                {  
                    $(".tutor-inbox #inbox-"+item.schedule_item_id).show();
                } else {
                    let col1 = '<div class="col-md-3">';
                        col1 += '<a href="#" class="dropdown-item small p-0">';
                        col1 += '<img src="'+ item.tutorOrignalImage  +'" alt="profile photo" class="img-fluid border" style="width: 100%;">';
                        col1 += '</a></div>';
                        
                        
                    //new added schedule, after loaded
                    let col2 = '<div class="col-md-9">';
                        col2 += '<span id="inbox-message-'+ item.schedule_item_id +'">';
                        col2 += '<a href="javascript:void(0)" onclick="openMemo('+item.schedule_item_id+')" data-toggle="modal" data-target="tutorMemoReplyModal" data-id='+ item.schedule_item_id+'>講師への連絡</a> <br>';
                        col2 += '<span class="message small">'+ item.latestReply + '</span></span>';
                        col2 += '</div>';

                    $( ".dropdown-menu" ).append(col1 + col2);       
                }
                
                //update message
                $(".tutor-inbox #inbox-"+item.schedule_item_id+" .message").text(item.latestReply);

                $("#tutor-lesson-memoBox-"+item.schedule_item_id).find( ".btn-container2" ).show();

                console.log("#tutor-lesson-memoBox-"+item.schedule_item_id+ " .memoContainer")

                $("#unreadMessages").hide();        
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

                    setTimeout(() => {  
                        
                        getMemoConversations(scheduleID);

                    }, 200);                
                    

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

                //interval unread message fetching
                intervalInbox = window.setInterval(function(){
                    getTutorInbox()
                }, 3000);
                
                

            });


        </script>

        <style>

            .table-tutor-schedules {ile-row
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
                padding: 0px;    ile-row
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
    @endif

    @yield('styles')
    @yield('scripts')
</body>

</html>
