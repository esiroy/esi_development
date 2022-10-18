@extends('layouts.esi-videochat')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-7">
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

    #videoGrid video {
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