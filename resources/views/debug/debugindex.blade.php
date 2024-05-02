@extends('layouts.debug-app')

@section('content')

<debug-chat-component                
    userid="{{ Auth::user()->id }}" 
    username="{{ Auth::user()->username }}"
    user_image="{{ '' }}"        
    nickname="{{ 'debuggerDevRoy' }}"        
    customer_support_image="{{ url('images/cs-profile.png') }}"        
    chatserver_url="{{ 'https://chatserver.mytutor-jpn.info:30001' }}"
    api_token="{{ Auth::user()->api_token }}"
    csrf_token="{{ csrf_token() }}"
    :show_sidebar="false"
>
</debug-chat-component>  

@endsection 
