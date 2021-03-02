@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">
    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report Card</li>
            </ol>
        </nav>
        <div class="container">
        
            <div class="row">
                <!--sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

                <div class="col-md-9">

                    @include('includes.session.message')

                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            アンケート
                        </div>

                        <div class="card-body">

                            <form method="POST" action="{{ route('questionnaire.update', [ $questionnaire->schedule_item_id  ]) }}">
                                @csrf
                                @method('PUT')
                                <table cellspacing="3" cellpadding="3">
                                    <tbody>
                                        <tr>
                                            <td>Lesson Schedule</td>
                                            <td>:</td>
                                            <td>

                                                <strong>
                                                    {{ date('Y年 m月 d日', strtotime(\App\Models\ScheduleItem::find($questionnaire->schedule_item_id)['lesson_time'])) }}
                                                    <span id="time">
                                                        {{ date('H:i', strtotime(\App\Models\ScheduleItem::find($questionnaire->schedule_item_id)['lesson_time'])) }}
                                                        -
                                                        {{ date('H:i', strtotime( \App\Models\ScheduleItem::find($questionnaire->schedule_item_id)['lesson_time'] ." + 25 minutes")) }}
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Tutor</td>
                                            <td>:</td>
                                            <td>
                                                @php
                                                $schedule = \App\Models\ScheduleItem::find($questionnaire->schedule_item_id);
                                                $tutor = \App\Models\Tutor::where('user_id', $schedule->tutor_id)->first();
                                                @endphp
                                                <strong>
                                                    @if (isset($tutor->user->japanese_firstname))
                                                    {!! $tutor->user->japanese_firstname ?? '' !!}
                                                    @else
                                                    {!! $tutor->user->firstname ?? '' !!}
                                                    @endif
                                                </strong>
                                                <input type="hidden" name="tutor_id" value="{{ $schedule->tutor_id }}">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">

                                                <div class="pt-4">
                                                    通信状態が安定していた<br>（通信断、エコー、ノイズなどの雑音が無く安定していた。）
                                                </div>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <input type="radio" name="QUESTION_1grade" id="QUESTION_1GOOD" alt="" value="GOOD" @if (isset($questionnaireItem1->grade) && $questionnaireItem1->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_1grade" id="QUESTION_1AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem1->grade) && $questionnaireItem1->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_1grade" id="QUESTION_1BAD" alt="" value="BAD" @if (isset($questionnaireItem1->grade) && $questionnaireItem1->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp;</td>
                                        </tr>


                                        <tr>
                                            <td colspan="4">
                                                講師の音声がきれいに聞こえた<br>（音量が安定し、他の講師の声などが気にならなかった）
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>
                                                <input type="radio" name="QUESTION_2grade" id="QUESTION_2GOOD" alt="" value="GOOD" @if (isset($questionnaireItem2->grade) && $questionnaireItem2->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_2grade" id="QUESTION_2AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem2->grade) && $questionnaireItem2->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_2grade" id="QUESTION_2BAD" alt="" value="BAD" @if (isset($questionnaireItem2->grade) && $questionnaireItem2->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp;</td>
                                        </tr>


                                        <tr>
                                            <td colspan="4">
                                                講師の英語能力が高い
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>
                                                <input type="radio" name="QUESTION_3grade" id="QUESTION_3GOOD" alt="" value="GOOD" @if (isset($questionnaireItem3->grade) && $questionnaireItem3->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_3grade" id="QUESTION_3AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem3->grade) && $questionnaireItem3->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_3grade" id="QUESTION_3BAD" alt="" value="BAD" @if (isset($questionnaireItem3->grade) && $questionnaireItem3->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp;</td>
                                        </tr>


                                        <tr>
                                            <td colspan="4">
                                                レッスン内容が良い<br>（生徒様のレベルと目的に合ったスピードと内容で、継続することで<br>英語能力が高くなるような気がした
                                            </td>
                                        </tr>

                                        <tr>



                                            <td>
                                                <input type="radio" name="QUESTION_4grade" id="QUESTION_4GOOD" alt="" value="GOOD" @if (isset($questionnaireItem4->grade) && $questionnaireItem4->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_4grade" id="QUESTION_4AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem4->grade) && $questionnaireItem4->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                            </td>
                                            <td>
                                                <input type="radio" name="QUESTION_4grade" id="QUESTION_4BAD" alt="" value="BAD" @if (isset($questionnaireItem4->grade) && $questionnaireItem4->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp;</td>
                                        </tr>


                                        <tr>
                                            <td colspan="4">
                                                <textarea name="remarks" id="remarks" cols="45" rows="4">{{$questionnaire->remarks ?? ''}}</textarea>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td colspan="4">
                                                <input id="submit" type="submit" class="btn btn-pink" value="提出する">
                                            </td>
                                        </tr>


                                    </tbody>



                                </table>

                            </form>


                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

    @include('modules.member.popup.loading')
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
window.addEventListener('load', function() {
    $('#submit').on("click", function() {
        $('#loadingModal').modal('show');      
    });
});
</script>
@endsection
