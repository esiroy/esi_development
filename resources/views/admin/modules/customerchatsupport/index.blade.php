@extends('layouts.admin')

@section('content')

@php
    $memberProfileImage = Storage::url('user_images/noimage.jpg');
    $nickname = "Customer Support";

   $chatserver_url = env('APP_CHATSERVER_URL', 'https://chatserver.mytutor-jpn.info:30001');
   
@endphp


<admin-chat-component 
        userid="{{ Auth::user()->id }}" 
        username="{{ Auth::user()->username }}"
        user_image="{{ $memberProfileImage }}"
        nickname="{{ $nickname }}"
        api_token="{{ Auth::user()->api_token }}"
        csrf_token="{{ csrf_token() }}"
        chatserver_url="{{$chatserver_url}}"
    >
</admin-chat-component> 
@endsection

@section('scripts')
@parent
<script type="text/javascript">
 
</script>
@endsection