@extends('layouts.esi-videochat')

@section('content')

    <!-- this is cancelled-->

    {{ $folderID }}, {{ $isBroadcaster}}

    <new-lesson-slide-selector-component        
        ref="newLessonSlideSelectorComponent"
        :reservation="{{ json_encode($reservationData) }}"            
        
        :folder_id="{{ $folderID }}"
        :is-broadcaster="{{ $isBroadcaster }}"

        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"        
        />  

@endsection

