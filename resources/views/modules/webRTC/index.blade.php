@extends('layouts.esi-videochat')

@section('content')


{{  $reservationData }}


<div id="slide-component">

    <lesson-slider-component 
        ref="lessonSliderComponent"
        :is-broadcaster="{{ $isBroadcaster }}"
        :editor_id="'canvas'"
        :channelid="{{ $roomID }}"
        :reservation="{{ json_encode($reservationData) }}"            
        canvas_server="{{ env('APP_CANVAS_SERVER_URL') }}"
        canvas_width="1000"
        canvas_height="480"
        :user_info="{{  json_encode(Auth::user()) }}"
        :member_info="{{  json_encode($userInfo) }}"
        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"
        >
    </lesson-slider-component> 
</div>



<div id="camera-button-container">
    <div class="bg-lightblue float-right mt-3 pr-1 pl-2 rounded-left">
        <i class="fas fa-camera text-white"></i>
    </div>
</div>



<div id="chatroom-button-container">
    <div class="bg-lightblue float-right mt-3 pr-1 pl-2 rounded-left">
        <i class="fas fa-pencil-alt text-white"></i>
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
 <link rel="stylesheet" href="{{ asset('css/lessonslider.css') }}">
@endsection