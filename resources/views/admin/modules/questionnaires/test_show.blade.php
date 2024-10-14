@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">


    @include('admin.menus.manage')
    
    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Questionnaires</li>
            </ol>
        </nav>


        <div class="container">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Questionnaire
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3">

                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="7">
                                            @if ($userImage == null)
                                            <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded">
                                            @else
                                            <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tutor</td>
                                        <td>:</td>
                                        <td colspan="5">
                                            {{ $tutor->user->firstname ?? '' }} {{ $tutor->user->lastname ?? '' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td>
                                            @if (strtolower($member->gender) == 'male')
                                                {{ '男' }}
                                            @elseif (strtolower($member->gender) == 'female')
                                                {{ '女' }}
                                            @else 
                                                {{ '-'}}
                                            @endif                                            
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>
                                    <tr>
                                        <td>Lesson Date</td>
                                        <td>:</td>
                                        <td colspan="5">
                                           {{  $scheduleItem->lesson_time ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Member</td>
                                        <td>:</td>
                                        <td colspan="5">
                                            {{ $member->user->firstname ?? '' }}
                                            {{ $member->user->lastname ?? '' }}
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div id="questionnaire-content" class="col-md-4">
                            
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
            </div>
            <!--[end] card-->


        </div>




    </div>
</div>

</div>
@endsection
