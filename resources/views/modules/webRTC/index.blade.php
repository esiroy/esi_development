@extends('layouts.esi-videochat')

@section('content')

<div class="container">
    <div class="row">

        <div class="col-9">
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

        <div class="col-md-3">
            <button type="button" id="stopCamera">Stop Camera </button>
            <button type="button" id="toggleCamera">Camera Hide On/Off</button>
            <button type="button" id="toggleAudio">Audio On/Off</button>
            <button type="button" id="shareScreen">shareScreen</button>


            <div class="select">
                <label for="audioSource">Audio input source: </label><select id="audioSource"></select>
            </div>

            <div class="select">
                <label for="audioOutput">Audio output destination: </label><select id="audioOutput"></select>
            </div>

            <div class="select">
                <label for="videoSource">Video source: </label><select id="videoSource"></select>
            </div>


            <video id="video" playsinline autoplay></video>

            

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
    #videoGrid {            
        min-width: 350px;
        min-height: 250px;
        display: inline-block;
        border: 3px solid #ccc;
    }

    #videoGrid video , #video {
        width: 350px;
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