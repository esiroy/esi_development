@extends('layouts.esi-app')

@section('content')

 <div class="esi-box">
    <div class="row">

        <div class="col-12">

            Channel ID : {{ app('request')->input('channel_id') }}


            <lesson-slider-component 
                
                :is-broadcaster="false"

                canvas-Server="{{ env('APP_CANVAS_SERVER_URL') }}"
                editor-ID="canvas"
                canvas-Width="500"
                canvas-Height="500"
                :channelid="{{ app('request')->input('channel_id') }}"
                :member-Info="{{  json_encode(Auth::user()->memberInfo) }}" 
                api_token="{{ Auth::user()->api_token }}" 
                csrf_token="{{ csrf_token() }}"
                >
            </lesson-slider-component>  
        </div>
    </div>

</div>

@endsection