@extends('layouts.admin')
@section('content')
 <div class="esi-box">
    <div class="row">
        <div class="col-12">
            <lesson-slider-component 
                :is-broadcaster="true"                
                canvas-Server="{{ env('APP_CANVAS_SERVER_URL') }}"
                editor-ID="canvas"
                canvas-Width="500"
                canvas-Height="500"
                :channelid="{{ app('request')->input('channel_id') }}"
                
                :user-Info="{{  json_encode(Auth::user()->username) }}" 
                :member-Info="{{  json_encode(Auth::user()->tutorInfo) }}" 
                api_token="{{ Auth::user()->api_token }}" 
                csrf_token="{{ csrf_token() }}"
                >
            </lesson-slider-component>
        </div>
    </div>
</div>
@endsection