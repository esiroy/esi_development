@extends('layouts.esi-videochat')

@section('content')


<div class="container">
    <div class="row">
        <div id="videoGrid"></div>
    </div>

    <button type="button" id="toggleCamera">Camera On/Off</button>
    <button type="button" id="toggleAudio">Audio On/Off</button>

</div>

@endsection 

@section('scripts')
@parent
<script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js" defer></script>
<script src="https://rtcserver.esuccess-inc.com:40002/socket.io/socket.io.js" defer></script>

<script src="{{ url('js/webRTC.js') }}" charset="utf-8" defer></script>

<script type="text/javascript">
    var roomID = "{{ $roomID }}";
    var testID = "THIS IS A TEST ID";

    window.addEventListener('DOMContentLoaded', function() {
      console.log(testing)
    });

   

</script>
@endsection

@section('styles')
<style>
    #videoGrid {
    }
    #videoGrid video {
        width: 350px;
        display: inline-block;
        margin: 5px;
    }
</style>

@endsection