@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lesson Slide History</li>
            </ol>
        </nav>

        <div class="container">

            <div class="row">

                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->

                <div class="col-md-9">
                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            予約表
                        </div>

                        <div class="card-body">

                            <table  cellspacing="5" cellpadding="3" width="100%">

                                <tbody>
                                    <tr>
                                        <td width="150">Member Name</td>
                                        <td>: </td>
                                        <td><strong>
                                            @php
                                                $member = \App\Models\Member::where('user_id', $lessonHistory->member_id)->first(); 
                                            @endphp

                                            {{ $member->user->firstname ?? '' }}
                                            {{ " " }}
                                            {{ $member->user->lastname ?? '' }}
                                            
                                        </strong></td>
                                    </tr>

                                    <tr>
                                        <td>Tutor</td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                $schedule = \App\Models\ScheduleItem::find($lessonHistory->schedule_id);                                               
                                            @endphp

                                            @php
                                                $tutor = \App\Models\Tutor::where('user_id', $lessonHistory->tutor_id)->first(); 
                                            @endphp

                                            {{ $tutor->user->firstname }}

                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <h5 class="font-weight-bold mt-4">Lesson Details</h5>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Lesson Date</td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                $schedule = \App\Models\ScheduleItem::find($lessonHistory->schedule_id);                                                
                                            @endphp

                                            {{ $schedule->lesson_time ?? '' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Lesson Course</td>
                                        <td>:</td>
                                        <td>
                                            {{ $lessonTitle ?? ' - ' }}
                                        </td>
                                    </tr>


                                    <tr>
                                         <td class="mt-3">
                                            
                                            <h5 class="font-weight-bold mt-4">Slide Materials </h5>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <lesson-viewer-component  
                                                ref="lessonSliderComponent" 
                                                :channelid="{{ $lessonHistory->schedule_id }}"   
                                                :reservation="{{ json_encode($reservationData) }}"   
                                                :lesson_history="{{ json_encode($lessonHistory) }}"
                                                :slide_history="{{ json_encode($slideHistory) }}"
                                                :audio_files="{{ json_encode($audioFiles) }}"
                                                :member_feedback="{{ json_encode($memberFeedback) }}"
                                                api_token="{{ Auth::user()->api_token }}" 
                                                csrf_token="{{ csrf_token() }}"
                                                ></lesson-viewer-component>                                        
                                        </td>
                                    </tr>

                                    <tr>
                                         <td class="mt-3">                                            
                                            <h5 class="font-weight-bold mt-4">Chat Messages</h5>
                                        </td>  
                                    </tr>
                                    <tr>                                  
                                        <td colspan="3">
                                            <lesson-chat-viewer-component  
                                                ref="lessonSliderComponent"
                                                :user="{{  json_encode(Auth::user()) }}"                                                
                                                :messages="{{ json_encode($messages) }}"
                                            >
                                            </lesson-chat-viewer-component>                                        
                                        </td>
                                    </tr>

                                  
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

            </div>
        </div>


    </div>



</div>

@endsection


@section('styles')
    <style>
        canvas {
            width: 100% !important;
            height: auto !important;
        }

        .canvas-container {
            width: 100% !important;         
        }
    </style>

@endsection