@extends('layouts.esi-videochat')

@section('content')


<div id="slide-component">
    <lesson-slider-component 
        ref="lessonSliderComponent"
        :is-broadcaster="{{ $isBroadcaster }}"
        :editor_id="'canvas'"
        :channelid="{{ $roomID }}"
        :reservation="{{ json_encode($reservationData) }}"            
        canvas_server="{{ env('APP_CANVAS_SERVER_URL') }}"
        canvas_width="1600"
        canvas_height="480"
        :user_info="{{  json_encode(Auth::user()) }}"
        :member_info="{{  json_encode($userInfo) }}"
        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"></lesson-slider-component> 
</div>


<div class="right-fixed">

    <!-- video media for user and teacher -->
    <div class="top-cabinet-holder">
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

                            <button id="btnShareScreen" type="button" class="btn btn-sm">
                                <i class="fas fa-desktop text-white"></i>
                                <!--<span class="small">Screen Share</span>-->
                            </button>

                            

                            <button type="button" class="btn btn-sm"  data-toggle="modal" data-target="#mySettingsModal">
                                <i class="fas fa-cog text-white"></i>
                                <!--<span class="small">Settings</span>-->
                            </button>                


                            <button type="button" id="stopCamera" class="btn btn-sm">
                                <i class="fas fa-video text-white"></i>
                                <i class="fas fa-video-slash text-white"></i>
                            </button>


                            <!--<button type="button" id="toggleCamera">Camera Hide On/Off</button>-->

                            <button type="button" id="toggleAudio"  class="btn btn-sm">
                                <i class="fas fa-volume-up text-white"></i>
                                <i class="fas fa-volume-mute text-white"></i>
                            </button>

                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
    <!-- [end] video media for user and teacher -->


     <div class="middle-cabinet-holder">

        <div class="button-overlap">
            <div class="bg-lightblue float-right mt-3 pr-1 pl-2 rounded-left">
                <a class="toggleLiveChat" href="#"><i class="fas fa-pencil-alt text-white"></i></i></a>
            </div>
        </div>

        <div id="right-chat-sidebar">
            <div class="media">
                <lesson-slider-chatroom-component 
                    ref="lessonSliderComponent"
                    :is-broadcaster="{{ $isBroadcaster }}"
                    :channelid="{{ $roomID }}"
                    :user_info="{{  json_encode(Auth::user()) }}"
                    :member_info="{{  json_encode($userInfo) }}"
                    api_token="{{ Auth::user()->api_token }}" 
                    csrf_token="{{ csrf_token() }}"
                    >
                </lesson-slider-chatroom-component>  
            </div>
        </div>
     </div>


</div>





       
<!--[start] Own Video Settings-->        
<div class="modal fade" id="mySettingsModal" tabindex="-1" role="dialog" aria-hidden="true">


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

@endsection 

@section('scripts')
@parent
    <script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js" defer></script>
    <script src="https://rtcserver.esuccess-inc.com:40002/socket.io/socket.io.js" defer></script>
    <script src="{{ url('js/webRTC.js') }}" charset="utf-8" defer></script>
    <script type="text/javascript" defer>
        var roomID = "{{ $roomID }}";
        var user = "{{ Auth::user()->id }}";
        var user = { userid: "{{ Auth::user()->id }}", userimage: "testimage" }
    </script>
@endsection



@section('styles')
 <link rel="stylesheet" href="{{ asset('css/lessonslider.css') }}">
@endsection