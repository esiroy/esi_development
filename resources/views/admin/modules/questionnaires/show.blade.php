@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/company') }}">Company</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Questionnaires</li>
            </ol>
        </nav>


        <div class="container">


            <div class="row">

                <!--[left column cards] -->
                <div class="col-md-4">
                    <div class="card esi-card">
                        <div class="card-header esi-card-header small">
                            Member Profile
                        </div>                            
                        <div class="text-center">
                            @if ($userImage == null)
                            <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded">
                            @else
                            <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                            @endif
                        </div>
                    </div>

                    <div class="card esi-card mt-2">
                        <div class="card-header esi-card-header small">
                            Lesson Information
                        </div>
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12 small">  
                                    Member Name: 
                                    <span class="font-weight-bold">
                                        {{ $member->user->firstname ?? '' }}
                                        {{ $member->user->lastname ?? '' }}
                                    </span>
                                </div>

                                <div class="col-md-12 small">  
                                    Tutor: 
                                    <span class="font-weight-bold">
                                    {{ $tutor->user->firstname ?? '' }} {{ $tutor->user->lastname ?? '' }}
                                    </span>
                                </div>

                                <div class="col-md-12 small">  
                                    Gender: 
                                    <span class="font-weight-bold">
                                    @if (strtolower($member->gender) == 'male')
                                        {{ '男' }}
                                    @elseif (strtolower($member->gender) == 'female')
                                        {{ '女' }}
                                    @else 
                                        {{ '-'}}
                                    @endif
                                    </span>                                           
                                </div>

                                <div class="col-md-12 small">  
                                    Lesson Duration: 
                                    <!-- {{  $scheduleItem->lesson_time ?? '' }} -->

                                    <span class="font-weight-bold">
                                        {{ ESIDateTimeFormat($lessonTimeDuration->startTime) }}
                                    </span>
                                        - 
                                    <span class="font-weight-bold">
                                    {{ date('H:i:s',strtotime($lessonTimeDuration->endTime)) }}                                                                                 
                                    </span>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
                <!--[[end] left column cards] -->

                <!--[start Questionnaire card] -->
                <div class="col-md-8">

                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            Questionnaire
                        </div>
                        <div class="card-body">

                    

                            @if ($isMerged == true)
                            
                                <div class="border border-danger rounded p-3">    

                                    <span class="text-danger">
                                        Note: Lesson is a consecutive lesson linked to questionnaire #{{$parentHistoryID}}
                                    </span>

                                    <div>
                                    <a href="{{ url('admin/questionnaires/'.$parentHistoryID) }}">Click this link to view first lesson #{{$parentHistoryID}}</a>
                                    </div>
                                </div>
                                            
                            @endif

                            
                            <p>Questionnaire:</p>

                            <table class="ml-4">
                                <tbody>

                                    <!--Q1-->
                                    <tr>
                                        <td colspan="2">
                                            通信状態が安定していた<br>（通信断、エコー、ノイズなどの雑音が無く安定していた。）
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td class="answer-1  pl-5">
                                            @if (isset($questionnaireItem1->grade))
                                                {{ getQuestionnnaireGradeTranslation( $questionnaireItem1->grade) ?? '' }}
                                            @endif
                                        </td>
                                    </tr>

                                    <!--Q2-->
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            講師の音声がきれいに聞こえた<br>（音量が安定し、他の講師の声などが気にならなかった）
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td class="answer-2  pl-5">
                                            @if (isset($questionnaireItem2->grade))
                                                {{ getQuestionnnaireGradeTranslation( $questionnaireItem2->grade) ?? '' }}
                                            @endif
                                        </td>
                                    </tr>

                                    <!--Q3-->
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            講師の英語能力が高い
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td class="answer-3  pl-5">
                                            @if (isset($questionnaireItem3->grade))
                                                {{ getQuestionnnaireGradeTranslation( $questionnaireItem3->grade) ?? '' }}
                                            @endif
                                        </td>
                                    </tr>

                                    <!--Q4-->
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            レッスン内容が良い<br>（生徒様のレベルと目的に合ったスピードと内容で、継続することで<br>英語能力が高くなるような気がした
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td class="answer-4  pl-5">
                                            @if (isset($questionnaireItem3->grade))
                                                {{ getQuestionnnaireGradeTranslation( $questionnaireItem4->grade) ?? '' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Remarks:
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td>
                                            <div class="ml-3">
                                            {{ $questionnaire->remarks ?? '' }}
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                               

                        </div>
                    </div>
                </div>
                <!--[end Questionnaire card] -->

            </div>
            





        </div>




    </div>
</div>

</div>
@endsection
