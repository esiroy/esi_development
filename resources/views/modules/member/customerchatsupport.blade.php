@extends('layouts.esi-chat')

@section('content')


<div style="padding-top:0px; padding-bottom:0px">
    <customer-chat-component userid="{{ Auth::user()->id }}" username="{{ Auth::user()->username }}"></chat-component>     
</div>


@endsection

