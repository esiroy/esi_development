@extends('layouts.esi-videochat')

@section('content')
<div class="container">
    <div class="row">

        <div id="videoGrid"></div>

    </div>
</div>
@endsection 

@section('scripts')
@parent
<script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js" defer></script>
<script src="https://rtcserver.esuccess-inc.com:40002/socket.io/socket.io.js" defer></script>
<script src="https://rtcserver.esuccess-inc.com:40002/index.js" charset="utf-8" defer></script>
<script type="text/javascript">
    var roomID = "test"
</script>
@endsection

@section('styles')


@endsection