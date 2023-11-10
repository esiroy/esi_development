@extends('layouts.esi-videochat')
@section('content')
    @php            
        $userImageObj = new \App\Models\UserImage;
        $userImage = $userImageObj->getMemberPhotoByID(Auth::user()->id);
        if ($userImage == null) {
            $memberProfileImage = Storage::url('user_images/noimage.jpg');
        } else {
            $memberProfileImage = Storage::url("$userImage->original");
        }
    @endphp 
    
    

    <div id="slide-component">

        <div id="webrtc_url" style="display:none">{{ "TEST URL " . env('APP_WEBRTC_SERVER_URL', 'https://rtcserver.esuccess-inc.com:40002')}}</div>

        <lesson-slider-component  
            ref="lessonSliderComponent"
            user_image="{{ $memberProfileImage ?? '' }}"
            :is_broadcaster="{{ $isBroadcaster }}"
            :editor_id="'canvas'"
            :channelid="{{ $roomID }}"
            :folder_id="{{ json_encode($folderID) ?? null }}"
            :folder_url_array="{{ json_encode($folderURLArray) ?? null }}"

            :consecutive_schedules="{{ json_encode($consecutiveSchedules) }}"

            :is_lesson_started="{{ json_encode($isLessonStarted) }}"
            :is_lesson_completed="{{ json_encode($isLessonCompleted) }}"

            :lesson_completed="{{ json_encode($lessonCompleted) }}"            
            :feedback_completed="{{ json_encode($feedbackCompleted)  }}"

            :lesson_history="{{ json_encode($lessonHistory) }}"
            :reservation="{{ json_encode($reservationData) }}"            

            webrtc_server="{{  env('APP_WEBRTC_SERVER_URL') }}"
            canvas_server="{{ env('APP_CANVAS_SERVER_URL') }}"

            canvas_width="1920"
            canvas_height="1080"

            :user_info="{{  json_encode(Auth::user()) }}"
            :member_info="{{  json_encode($userInfo) }}"
            :recipient_info="{{ json_encode($recipientInfo) }}"

            api_token="{{ Auth::user()->api_token }}" 
            csrf_token="{{ csrf_token() }}"

            chatserver_url="{{ env('APP_CHATSERVER_URL', 'https://chatserver.mytutor-jpn.info:30001') }}"

            ></lesson-slider-component> 
    </div>

    @if ($isLessonCompleted == false)
        <div class="right-fixed">

            <div class="main-cabinet">
                <!-- [start] video media for user and teacher -->
                <div class="cabinet-holder top-cabinet-holder">
                    <div class="button-overlap">
                        <div class="bg-lightblue float-right mt-3 pr-1 pl-2 rounded-left">
                            <a class="toggleCamera" href="#"><i class="fas fa-camera text-white"></i></a>
                        </div>
                    </div>

                    <div id="right-video-sidebar">

                        <div class="media">

                            <ul class="nav nav-tabs" style="background-color:#ccc">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab-mycontact">
                                        <!--MENU -->
                                        @if (Auth::user()->user_type == "TUTOR") {{ "STUDENT" }} @else {{ "TEACHER" }} @endif                    
                                    </a>
                                </li>   

                                <li class="nav-item">
                                    <a class="nav-link " data-toggle="tab"  href="#tab-mymedia">
                                        <!-- MENU ITEMS -->
                                        @if (Auth::user()->user_type == "TUTOR") {{ "TEACHER" }} @else {{ "STUDENT" }} @endif
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <div id="tab-mycontact" class="tab-pane active show ">
                                    <div id="videoGrid"></div>
                                </div>

                                <div id="tab-mymedia" class="tab-pane fade">
                                    <div id="myMediaContainer" class="row">
                                    </div>

                                    <div class="myButtonsContainer" class="row">

                                        @if (Auth::user()->user_type == "TUTOR")
                                        <button id="btnShareScreen" type="button" class="btn btn-sm">
                                            <i class="fas fa-desktop text-white"></i>
                                            <!--<span class="small">Screen Share</span>-->
                                        </button>
                                        @endif
                                        

                                        <button id="mySettingsBtn" type="button" class="btn btn-sm" data-toggle="modal" data-target="#mySettingsModal">
                                            <i class="fas fa-cog text-white"></i>
                                            <!--<span class="small">Settings</span>-->
                                        </button>                

                                    
                                        <button id="toggleCamera"  type="button"class="btn btn-sm">
                                            <i class="fas fa-video text-white"></i>
                                            <i class="fas fa-video-slash text-white" style="display:none"></i>
                                        </button>
                                        
                                    
                                        <!--
                                        <input type="range" id="volume-control" min="0" max="1" step="0.1" value="1" style="width:50px">
                                        -->

                                        <button id="toggleAudio" class="btn btn-sm">
                                            <i class="fas fa-volume-up text-white" ></i>
                                            <i class="fas fa-volume-mute text-white" style="display:none"></i>
                                        </button>
                                    


                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- [end] video media for user and teacher -->

                <!-- [start] chat media for user and teacher -->
                <div class="cabinet-holder middle-cabinet-holder">
                    <div id="toggleLiveChatContainer" class="button-overlap">
                        <div class="bg-lightblue float-right mt-3 pr-1 pl-2 rounded-left">
                            <a class="toggleLiveChat" href="#">
                                <i class="fas fa-pencil-alt text-white"></i>
                            </a>
                        </div>
                    </div>
                    <div id="toggleLiveChatMaximizedContainer" class="button-overlap">
                        <div class="bg-lightblue float-right pr-1 pl-2 rounded-left">                        
                            <a class="toggleLiveChatMaximized" href="#">
                                <b-icon icon="square" class="text-white" aria-hidden="true"></b-icon>
                            </a>
                        </div>
                    </div>

                    <div id="right-chat-sidebar">
                        <div class="media">
                            <lesson-slider-chatroom-component 
                                ref="lessonSliderComponent"
                                user_image="{{ $memberProfileImage ?? '' }}"
                                :is-broadcaster="{{ $isBroadcaster }}"
                                :channelid="{{ json_encode($roomID) }}"
                                canvas_server="{{ env('APP_CANVAS_SERVER_URL') }}"
                                :user_info="{{  json_encode(Auth::user()) }}"            
                                :reservation="{{ json_encode($reservationData) }}"
                                :member_info="{{  json_encode($userInfo) }}"
                                :recipient_info="{{ json_encode($recipientInfo) }}"
                                api_token="{{ Auth::user()->api_token }}" 
                                csrf_token="{{ csrf_token() }}"
                                >
                            </lesson-slider-chatroom-component>  
                        </div>
                    </div>
                </div>
                <!--[end] chat media for user and teachervideo media for user and teacher -->

            </div>

            <button class="btn btn-primary" id="destroy-session-media" style="display:none">End Session</button>
        </div>
        
        <!--[start] Own Video Settings-->        
        <div class="modal" id="mySettingsModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="modal-setting-title">Media Settings</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="select mb-2">
                        <label for="audioSource" class="font-weight-bold my-0">Audio input source: </label>
                        <select id="audioSource" class="form-control form-control-sm py-0" ></select>
                    </div>
                    <div class="select mb-2">
                        <label for="audioOutput" class="font-weight-bold my-0">Audio output destination: </label>
                        <select id="audioOutput" class="form-control form-control-sm py-0"></select>
                    </div>
                    <div class="select mb-2">
                        <label for="videoSource"  class="font-weight-bold my-0">Video source: </label>
                        <select id="videoSource" class="form-control form-control-sm py-0"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
                </div>
            </div>
        </div>
        <!--[end] Settings -->
    @else

        <!-- Lesson has been completed -->

    @endif

@endsection 

@section('scripts')      

  
    <!-- this will only get the script if lesson  is not complete to reduce loading -->

    @if ($lessonCompleted == false)
 
        <script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js" defer></script>
        <script src="https://rtcserver.esuccess-inc.com:40002/socket.io/socket.io.js" defer></script>
        <script src="{{ url('js/webRTC.js') }}" charset="utf-8" defer></script>
        <script type="text/javascript" defer>
            var roomID = "{{ $roomID }}";
            var user = "{{ Auth::user()->id }}";
            var user = { userid: "{{ Auth::user()->id }}", userimage: "testimage" }
            var APP_WEBRTC_SERVER_URL = "{{ env('APP_WEBRTC_SERVER_URL', 'https://rtcserver.esuccess-inc.com:40002') }}";            
        </script>

        <script type="text/javascript" defer>

            let isCameraActive = false;
            let isChatboxActive = false;
            let isChatboxMaximized = false;

            window.addEventListener('load', function () {

                /*** TOGGLE MEDIA CABINETS EFFECTS */
                $(".toggleCamera").click(function() {

                    $('#right-video-sidebar').animate({ width: 'toggle' }, 25, () => {
                        // Animation complete.
                        if (document.getElementById("right-video-sidebar").style.display == 'none') {
                            //alert('this Element is hidden');
                            isCameraActive = false;
                        } else {
                            //alert('this Element is block');
                            isCameraActive = true;
                        }

                        resizeAspectRatio();

                    })
                });

               

                $(".toggleLiveChat").click(function() {

                    document.getElementById("right-chat-sidebar").style.width = "225px";
                    $('#right-chat-sidebar').animate({ width: 'toggle' }, 25, () => {
                        // Animation complete.
                        if (document.getElementById("right-chat-sidebar").style.display == 'none') {
                            //Close Chat sidebar
                            isChatboxActive = false;
                        } else {
                            //Open Chat sidebar
                            lessonSliderChatroom.markMessageRead()
                            isChatboxActive = true;
                        }
                        resizeAspectRatio();  
                    })
                });



                $(".toggleLiveChatMaximized").click(function() {

                    document.getElementById("right-chat-sidebar").style.width = "400px";

                    if (isChatboxActive == true) {
                        if (isChatboxMaximized == false) {
                            $('#right-chat-sidebar').animate({ width: '425px', height: '', }, 25, () => {                            
                                isChatboxMaximized = true;
                            });
                        } else {                        
                            $('#right-chat-sidebar').animate({ width: '225px' }, 25, () => {
                                isChatboxMaximized = false;
                            });                        
                        }
                        resizeAspectRatio();                  
                    } else {
                    
                        $('#right-chat-sidebar').animate({ width: 'toggle' }, 25, () => {
                            // Animation complete.
                            if (document.getElementById("right-chat-sidebar").style.display == 'none') {
                                //Close Chat sidebar
                                isChatboxActive = false;
                            } else {
                                //Open Chat sidebar
                                lessonSliderChatroom.markMessageRead()
                                isChatboxActive = true;                          
                        }
                            resizeAspectRatio();  
                        });
                    }
                });



                function resizeAspectRatio()  
                {
                    //console.log(isCameraActive, isChatboxActive)
                    

                    if (isCameraActive == true || isChatboxActive == true) {                  
                        //console.log("at least one cabinet opened, we are going to resize");
                        
                        const elements = document.getElementsByTagName("canvas");
                        for (i = 0; i < elements.length; i++) {
                            //it does work
                            elements[i].className = "canvas-windowed";
                        }
                    } else {
                        const elements = document.getElementsByTagName("canvas");
                        for (i = 0; i < elements.length; i++) {
                            //it does work
                            elements[i].classList.remove("canvas-windowed");
                        }
                    }
                }
                
                $(".toggleLiveChat").trigger('click');
                $(".toggleCamera").trigger('click');

            });

          

        </script>

    @endif

@endsection



@section('styles')
    <link rel="stylesheet" href="{{ asset('css/lessonslider.css') }}">
    <style>
        canvas {
            width: 100% !important;
            height: auto !important;
        }

        .canvas-container {
            width: 100% !important;         
        }

        /* Slide Controls*/
        .slide-controls {
          
        }


        .tab-content > .active {
            display: grid;
        }

        .media {                   
            background-image: url('{{ url("images/audio.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            min-height: 125px;
            background-position-x: center;
            background-color: #fff            
        }

     
        #myMediaContainer audio, #videoGrid audio {
            min-height: 125px;
            width: 100%;
            margin: 10px;
            opacity: 0;
        }

        #toggleLiveChatMaximizedContainer {
            margin-top: 42px;
        }
           
    </style>
@endsection

