@extends('layouts.esi-videochat')

@section('content')

<div class="container">
    <div class="row">

        <div id="lesson-slider-container" class="col-8">

            <lesson-slider-component 
                ref="lessonSliderComponent"
                :is-broadcaster="{{ $isBroadcaster }}"
                :editor_id="'canvas'"
                :channelid="{{ $roomID }}"
                :reservation="{{ json_encode($reservationData) }}"            
                canvas_server="{{ env('APP_CANVAS_SERVER_URL') }}"
                canvas_width="500"
                canvas_height="500"
                :user_info="{{  json_encode(Auth::user()) }}"
                :member_info="{{  json_encode($userInfo) }}"
                api_token="{{ Auth::user()->api_token }}" 
                csrf_token="{{ csrf_token() }}"
                >
            </lesson-slider-component> 
        </div>

        <div class="col-md-4">

            <!---
            <button type="button" id="stopCamera">Stop Camera </button>
            <button type="button" id="toggleCamera">Camera Hide On/Off</button>
            <button type="button" id="toggleAudio">Audio On/Off</button>
            <button type="button" id="shareScreen">shareScreen</button>
            -->

            <div class="modal fade" id="mySettingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">


                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title" id="modal-setting-title">Media Settings</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="select">
                            <label for="audioSource">Audio input source: </label><select id="audioSource"></select>
                        </div>

                        <div class="select">
                            <label for="audioOutput">Audio output destination: </label><select id="audioOutput"></select>
                        </div>

                        <div class="select">
                            <label for="videoSource">Video source: </label><select id="videoSource"></select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                    </div>
                </div>


            </div>

            <div class="media">

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
                </div>

            </div>
            

            <div id="videoGrid"></div>

        </div>            

    </div>

    
</div>

@endsection 

@section('scripts')
@parent
<script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js" defer></script>
<script src="https://rtcserver.esuccess-inc.com:40002/socket.io/socket.io.js" defer></script>

<script src="{{ url('js/webRTC.js') }}" charset="utf-8" defer></script>

<script type="text/javascript">
    var roomID = "{{ $roomID }}";
    var user = "{{ Auth::user()->id }}";
    var user = { userid: "{{ Auth::user()->id }}", userimage: "testimage" }
</script>

@endsection

@section('styles')
<style>

    .media {
        display:block;
        border:1px solid #009fd9;
        background-color:#d7f3fa;
         margin-bottom:4px;
    }

    #myMediaContainer {
        justify-content: center;
        display: flex;
        min-height: 112.5px;
       
    }

    .myButtonsContainer {
        background-color:#a9a8a7;
        
    }


     #myMediaContainer  #myVideo {
        width: 150px;
    }


    #lesson-slider-container #lessonSharedContainer #sharedVideo {
        width: 100%
    }

    #videoGrid {            
        min-width: 100%;
        min-height: 264px;
        display: inline-block;
        border: 3px solid #ccc;
    }

    #videoGrid video , #video {
        width: 100%;
        display:  flex;
        margin:0px;
        padding:0px;        
    }



    


    /*
    #videoGrid #myVideo {
        width: 50px;
        position: absolute;
    }

     #videoGrid #callerVideo {
        min-width: 450px;
       
     }
     */

</style>

@endsection