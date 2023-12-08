@extends('layouts.esi-app')

@section('content')

 <div class="esi-box">
    <div class="row">

        <div class="col-12">

            Channel ID : {{ app('request')->input('channel_id') }}


            <lesson-slider-component 
                :is-broadcaster="true"
                canvas-Server="{{ env('APP_CANVAS_SERVER_URL') }}"
                :editor_id="'canvas'"
                :canvas_width="500"
                :canvas_height="500"
                :channel_id="{{ app('request')->input('channel_id') }}"
                :user_info="{{  json_encode(Auth::user()->memberInfo) }}"
                :member_info="{{  json_encode(Auth::user()->memberInfo) }}" 
                api_token="{{ Auth::user()->api_token }}" 
                csrf_token="{{ csrf_token() }}"
                >
            </lesson-slider-component>  
        </div>
    </div>

</div>

@endsection