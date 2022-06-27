@extends('layouts.esi-app')

@section('content')

 <div class="esi-box">
    <div class="row">

        <div class="col-12">
            <lesson-slider-component 
                canvas-Server="{{ env('CHAT_SERVER') }}"
                editor-Id="canvas"
                canvas-Width="500"
                canvas-Height="500"
                :member-Info="{{  json_encode(Auth::user()->memberInfo) }}" 
                api_token="{{ Auth::user()->api_token }}" 
                csrf_token="{{ csrf_token() }}"
                >
            </lesson-slider-component>  
        </div>
    </div>

</div>

@endsection