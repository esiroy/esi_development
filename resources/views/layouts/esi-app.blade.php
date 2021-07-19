<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>{{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(1))->replace('-', ' ') ) ?? '' }}</title>
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    
    <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>
<body class="bg-gray">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <a class="navbar-brand" href="{{ url('/') }}">
                   <img src="{{ url("images/title_full.png") }}" alt="{{ config('app.name', 'My Tutor') }}" alt="{{ config('app.name', 'My Tutor') }} administratrion panel">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <!--
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                            -->
                        @else

                        <!--
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ ucfirst(Auth::user()->first_name) }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout_member') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout_member') }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            -->

                            
                                @php 
                                    $member = App\Models\Member::where('user_id', Auth::user()->id )->first();
                                    $agentTransaction = new App\Models\AgentTransaction();
                                    $credits = $agentTransaction->getCredits($member->user_id)
                                @endphp
                            
                                <span><a class="blue" href="{{ url('/user/?id='. Auth::user()->id)}}"><strong>ユーザ名 {{ $member->nickname }}</strong></a></span>                                

                                @if ($member->isMemberCreditExpired(Auth::user()->id))
                                    <span id="total_credits" class="text-danger">(0)</span>
                                    <span class="px-2 text-success">|</span>
                                @else
                                    <span id="total_credits" class="text-success">({{ number_format($credits, 2) }})</span>
                                    <span class="px-2 text-success">|</span>
                                @endif

                                <!--<span><a id="inbox" class="blue" href="{{ url('#inbox') }}">受信トレイ</a></span>
                                
                                <span class="px-2 tuser_idext-success">|</span>-->

                                <span><a class="blue" href="{{ url('/settings') }}">設定</a></span>

                                <span class="px-2 text-success">|</span>

                                @php
                                    $ctr = 0;
                                    $undreadMessages = 0;
                                    $scheduleItems = new \App\Models\ScheduleItem;
                                    $reservations = $scheduleItems->getMemberAllActiveLessons($member);
                                @endphp
                                
                                <div class="member-inbox">
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle blue" data-toggle="dropdown">{{ "受信トレイ" }}
                                        <span id="total_unread_message" class="text-success">({{ $undreadMessages }})</span></a>

                                        <div class="dropdown-menu" style="overflow:auto; min-height:50px; max-height:450px; left: -265px; width:400px">
                                            @php                                             
                                                $latestReplyCount = 0;
                                            @endphp

                                            @foreach ($reservations as $reserve)
                                                @php      
                                                    $ctr++;
                                                    $userImageObj = new \App\Models\UserImage;
                                                    $memoReply = new \App\Models\MemoReply;

                                                    $userImage = $userImageObj->getTutorPhotobyID($reserve->tutor_id); 

                                                    $latestReplyCount = $memoReply->where('schedule_item_id', $reserve->id)->where('is_read', false)->where('message_type', "TUTOR")->count();   
                                                    $latestReply = $memoReply->where('schedule_item_id', $reserve->id)->orderBy('created_at', 'DESC')->first(); 

                                                    if ($latestReplyCount >= 1)
                                                    {
                                                        $readStatus = "message-read";
                                                        $colorClass = "blue font-weight-bold";
                                                        $undreadMessages =  $undreadMessages + $latestReplyCount;
                                                    }
                                                    else {
                                                        $readStatus = "message-unread";
                                                        $colorClass = "text-muted font-weight-light";
                                                    }
                                                    
                                                @endphp
                                                
                                                <div id="inbox-{{$reserve->id }}" class="row px-0 mx-0 {{$readStatus}} {{$colorClass}}">
                                                    <div class="col-md-3">                                               
                                                        <a href="#" class="dropdown-item small p-0 {{$colorClass}}">                                                        
                                                            @if ($userImage == null)                                                                
                                                                <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" style="width:100%">
                                                            @else 
                                                                <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo"  style="width:100%">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span id="inbox-message-{{ $reserve->id }}">
                                                            <a href="javascript:void(0)" class="{{$colorClass}}" onClick="openMemo('{{ $reserve->id }}')" data-toggle="modal" data-target="tutorMemoReplyModal" data-id="{{ $reserve->id }}">
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

                                            @php
                                                if ($ctr == 0)
                                                    $display = "block";
                                                else
                                                   $display = "none";                                                
                                            @endphp

                                             

                                            <div id="noInboxMessages" class="text-center small pt-3 pb-3" style="display:{{$display}}"> 
                                                No Inbox Message(s) 
                                            </div>

                                        </div>

                                        

                                    </div>
                                </div>    

                                <span class="px-2 text-success">|</span>
                            

                                <span>
                                    <a class="red" href="{{ route('logout_member') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    ログアウト</a>
                                </span>

                                <form id="logout-form" action="{{ route('logout_member') }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                </form>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

    
        <div class="bg-lightblue">
          <div class="container px-0">
            <nav class="nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('/home') }}">マイページ </a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="JavaScript:newPopup('http://www.mytutor-jpn.com/faq.html');">よくあるご質問</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('customersupport') }}">カスタマー　サポート</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('lessonmaterials') }}">オリジナル教材</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="JavaScript:newPopup('http://www.mytutor-jpn.com/lesson.html');">レッスンコース</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="JavaScript:newPopup('http://www.mytutor-jpn.com/service.html');">レッスン料金</a>
            </nav>
          </div>
        </div>


        <main class="main-container mb-4">
            <div class="container bg-light pb-5 rounded-bottom" style="border-bottom-right-radius: 0.50rem !important; border-bottom-left-radius: 0.50rem !important;">
                @yield('content')
            </div>
        </main>

    </div>

    <script type="text/javascript">
        window.addEventListener('load', function () 
        {            
            //Inbox
            if(window.location.hash) {
                console.log("hash found");
            } else {
                // Fragment doesn't exist
            }

            $('#inbox').click(function(){
                console.log("show inbox");
            })

        });

        function newPopup(url) {
            popupWindow = window.open(url,'popUpWindow','height=500,width=700,left=0,top=0,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
        }

        function PopupCenter(url, title, w, h) {  
            // Fixes dual-screen position                         Most browsers      Firefox  
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  
                    
            width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  
            height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  
                    
            var left = ((width / 2) - (w / 2)) + dualScreenLeft;  
            var top = ((height / 2) - (h / 2)) + dualScreenTop;  
            var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);  

            // Puts focus on the newWindow  
            if (window.focus) {  
                newWindow.focus();  
            }  
        }
    </script>


    <script type="text/javascript">   
        @php 
            if (!isset($reserves)) {
                $reserves = $scheduleItems->getMemberActiveLessons($member);
            }        
        @endphp

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
                //url: 'api/getMemo?api_token=' + api_token,
                url: "{{ url('api/getMemo?api_token=') }}" + api_token,
                data: {
                    'scheduleID': scheduleID,
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (data, error) {             
                   //alert("Error Found getting memo: " + error);
                },            
                success: function(data) { 
                    $('#message').val(data.memo);
                    $('#scheduleID').val(scheduleID);
                    $('#loadingModal').modal('hide');

                    $('#teacherImage').attr('src', data.tutorImage)

                    if (data.memo) {
                        $('#tutorMemoReplyModal #scheduleID').val(scheduleID);
                        $('#tutorMemoReplyModal #message').html(data.memo);
                        $('#tutorMemoReplyModal #lessonTime').html(data.lesson_time);
                        $('#tutorMemoReplyModal').modal('show')    
                    } else {
                        //***[old] this is where they create a new thread 
                        //$('#tutorMemoModal').modal('show')    
                        $('#tutorMemoReplyModal').modal('show')  
                    }  
                },
            });
        }

        function sendMemo(scheduleID, message) {
            $.ajax({
                type: 'POST', 
                //url: 'api/sendMemo?api_token=' + api_token,
                url: "{{ url('api/sendMemo?api_token=') }}" + api_token,
                data: {
                    'scheduleID': scheduleID,
                    'message': message
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (data, error) {             
                    //alert("Error Found getting memo: " + error);    
                    console.log("cant send memo")
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
                url: "{{ url('api/getMemberMemoConversations?api_token=') }}" + api_token,
                data: {
                    'scheduleID': scheduleID,
                    'tutorID': {{ Auth::user()->id }},
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (data, error) {             
                    //alert("Error Found while getting memo conversations: " + error);
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
            if (item.message_type === "MEMBER") 
            {
                let teacherProfileImage = $('#memberProfile').html();
                addMemberReplyBubble(teacherProfileImage, item.message);
            } else {
                let teacherProfileImage = $('#teacherProfile').html();
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
                }, 300);
            }
        }

        function addMemberReplyBubble(image, message) 
        {
            if (message) {        
                $( "#teacherReplies" ).append( "<div class='row'> <div class='col-md-9'> <div class='member-speech-bubble'>"+ message +" </div></div>    <div class='col-md-3'>  " +  image + "  </div> </div>");         
                setTimeout(() => {  
                        var element = document.getElementById("teacherReplies");
                        element.scrollTop = element.scrollHeight;
                }, 300);
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
                    //alert("Error Found while getting teacher unread memo replies: " + error);
                    console.log("Error Found while getting teacher unread memo replies");
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
                    //alert("Error Found while getting teacher unread memo replies: " + error);
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

        function getMemberInbox() 
        {
            //Output this message when you have no inbox (no read, no unread)   
            let noInbox = '<div id="noInboxMessages" class="text-center small pt-3 pb-3"> No Inbox Message(s) </div>';

            $.ajax({         
                type: 'POST',
                dataType: 'json',
                cache : false,
                url: "{{ url('api/getMemberInbox?api_token=') }}" + api_token,
                data: {
                    'memberID': {{ Auth::user()->id }},
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (data, error) {             
                   // alert("Error Found while getting teacher unread memo replies: " + error);
                   console.log("(getMemberInbox) Error Found while getting teacher unread memo replies")
                },      
                beforeSend: function() {   
                    $('#loadingModal').modal('hide');
                },               
                success: function(data) 
                {
                    $("#total_unread_message").text("("+ data.unread + ")");
                    $( ".dropdown-menu" ).children().remove();                    
                    
                    if (data.inboxCount == 0){                                             
                        $( ".dropdown-menu" ).append(noInbox); 
                    } else {
                        let inbox = data.inbox;
                        inbox.forEach(updateInboxList);
                    }

                    
                },
            });
        }

        function updateInboxList(item, index) 
        {
            let readMessage = "";
            let colorClass = "";

            if (item.unreadMessageCount >= 1)  {
                 readStatus = "message-read";
                 colorClass = "blue font-weight-bold";
            } else {
                 readStatus = "message-unread";
                 colorClass = "text-muted font-weight-light";
            }
            

            let row  = "<div id='inbox-"+ item.schedule_item_id +"' class='row px-0 mx-0 "+ readStatus + " " + colorClass +"'>";

            let col1 = '<div class="col-md-3">';
                col1 += '<a href="#" class="dropdown-item small p-0 ' + colorClass + '">';
                col1 += '<img src="'+ item.tutorOrignalImage  +'" alt="profile photo" class="img-fluid border" style="width: 100%;">';
                col1 += '</a></div>';
                
                
            //new added schedule, after loaded
            let col2 = '<div class="col-md-9">';
                col2 += '<span id="inbox-message-'+ item.schedule_item_id +'">';
                col2 += '<a href="javascript:void(0)" class="' + colorClass + '" onclick="openMemo('+item.schedule_item_id+')" data-toggle="modal" data-target="tutorMemoReplyModal" data-id="'+ item.schedule_item_id+'">';
                col2 += item.lessonTime;
                col2 += '</a><br/>';
                col2 += '<span class="message small">'+ item.latestReply + '</span></span>';
                col2 += '</div>';

            let rowend = "</div>";                    

            $( ".dropdown-menu" ).append(row + col1 + col2 + rowend); 

            /*
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
            
            

            //update message
            $(".member-inbox #inbox-"+item.schedule_item_id+" .message").text(item.latestReply);
            */

            $("#unreadMessages").hide();        
        }


        function updateInboxList_standard(item, index) 
        {
            //show
            if(document.getElementById('inbox-message-'+ item.schedule_item_id))
            {  
                $(".member-inbox #inbox-"+item.schedule_item_id).show();
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
            $(".member-inbox #inbox-"+item.schedule_item_id+" .message").text(item.latestReply);

            $("#unreadMessages").hide();        
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


            //interval unread message fetching
            intervalInbox = window.setInterval(function(){
                getMemberInbox()
            }, 5000);
            

        });
    </script>


    @include('modules.member.popup.memo')
    @include('modules.member.popup.memoReply')

    @yield('scripts')
</body>

</html>






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