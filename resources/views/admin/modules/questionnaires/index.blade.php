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
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Questionnaire List
                </div>
                <div class="card-body">

                    <!--Search-->
                    <div class="row">
                        <form class="form-inline" style="width:100%" method="GET">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from" class="small col-4">From:</label>
                                    <input id="date_from" required name="date_from" type="date" data-date-format="YYYY年 M月 DD日" class="inputDate form-control form-control-sm col-8" value="{{ request()->has('date_from') ? request()->get('date_from') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to" class="small col-4">To:</label>
                                    <input id="date_to" required name="date_to" type="date" data-date-format="YYYY年  M月 DD日" class="inputDate form-control form-control-sm col-8" value="{{ request()->has('date_to') ? request()->get('date_to') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm col-1 ml-1">Go</button>
                            </div>
                        </form>
                    </div>

                    <!-- Gemerate -->


                    <div class="row">
                        <div class="col-12 pt-3">

                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    {{ $questionnaires->appends(request()->query())->links() }}
                                </ul>
                            </div>

                            <div class="table-responsive ">
                                <table class="table table-bordered table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="small text-center bg-light text-dark font-weight-bold">ID</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Start Time</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Member Name</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Tutor Name</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Q1</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Q2</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Q3</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Q4</th>

                                            <th class="small text-center bg-light text-dark font-weight-bold">Stars Rating</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @foreach($questionnaires as $questionnaire)

                                            @php
                                            //Get and Assign Stars Raiting
                                            $starsRating = null;


                                            $satisfactionSurveyObj = new \App\Models\SatisfactionSurvey();
                                            
                                            $satisfactionSurvey = $satisfactionSurveyObj->where('schedule_id', $questionnaire->schedule_item_id)->first();

                                            if ($satisfactionSurvey) {

                                                $satisfactionSurveyDetailObj = new \App\Models\SatisfactionSurveyDetails();
                                                 
                                                $satisfactionSurveyDetails = $satisfactionSurveyDetailObj->where('lesson_survey_id', $satisfactionSurvey->id)->where('name', 'teacher_performace_rating')->first();

                                                    if ($satisfactionSurveyDetails) {
                                                        //assign the stars
                                                        $starsRating = $satisfactionSurveyDetails->value;
                                                    }
                                            }
                                        @endphp
                                        <tr>
                                           
                                            <td id="{{ $questionnaire->id }}" class="small text-center bg-light text-dark font-weight-bold">
                                                {{ $questionnaire->schedule_item_id }}
                                            </td>
                                           
                                            <td class="small text-center">
                                             
                                                @if ( date("H", strtotime($questionnaire->lesson_time)) == "00")
                                                    {{ date("Y/m/d", strtotime($questionnaire->lesson_time ." -1 day" )) }}    
                                                @else 
                                                    {{ date("Y/m/d", strtotime($questionnaire->lesson_time )) }}    
                                                @endif    
                                                    
                                                @if (date("H", strtotime($questionnaire->lesson_time)) == "00")
                                                    {{ date("24:i", strtotime($questionnaire->lesson_time)) }}
                                                @else 
                                                    {{ date("H:i", strtotime($questionnaire->lesson_time)) }}
                                                @endif        
                                            </td>
                                           

                                          
                                            <td class="small text-center">
                                                @php
                                                $member = \App\Models\Member::where('user_id', $questionnaire->member_id)->first()
                                                @endphp
                                                {{ $member->user->lastname ?? '' }} {{ ", "}} {{ $member->user->firstname ?? '' }}
                                            </td>

                                            <td class="small text-center">
                                                <!-- TUTOR NAME -->
                                                @php
                                                    $tutor = \App\Models\Tutor::where('user_id', $questionnaire->tutor_id)->first();
                                                @endphp
                                                {{ $tutor->user->firstname ?? "" }}                                                
                                            </td>

                                            @php
                                                $questionnaireItem1 = \App\Models\QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', 'QUESTION_1')->where('valid', true)->first();
                                                $questionnaireItem2 = \App\Models\QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', 'QUESTION_2')->where('valid', true)->first();
                                                $questionnaireItem3 = \App\Models\QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', 'QUESTION_3')->where('valid', true)->first();
                                                $questionnaireItem4 = \App\Models\QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', 'QUESTION_4')->where('valid', true)->first();
                                            @endphp

                                            <td class="question_1">
                                                {{ $questionnaireItem1->grade ?? '' }}
                                            </td>
                                            <td class="question_1">
                                                {{ $questionnaireItem2->grade ?? '' }}
                                            </td>
                                            <td class="question_1">
                                                {{ $questionnaireItem3->grade ?? '' }}
                                            </td>
                                            <td class="question_1">
                                                {{ $questionnaireItem4->grade ?? '' }}
                                            </td>
                                             <td class="small text-center" style="width:300px">
                                                @for ($count = 0; $count < $starsRating; $count++)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path fill="#FFAE42" stroke="none" d="M8 0l2.47 5.03 5.53.8-3.99 3.89.94 5.51L8 12.52l-4.95 2.61.94-5.51L0 5.83l5.53-.8L8 0z"/>
                                                    </svg>
                                                @endfor
                                                
                                            </td>
                                            <td class="small text-center" style="width:300px">{{ $questionnaire->remarks }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="float-right mt-4">
                                    <ul class="pagination pagination-sm">
                                        {{ $questionnaires->appends(request()->query())->links() }}
                                    </ul>
                                </div>
                                
                                <!--
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFAE42" class="bi bi-star-half" viewBox="0 0 16 16">
                                <path d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z"/>
                                </svg>
                                -->

                            </div>

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


@section('styles')
@parent
<style>
    input.inputDate {}

    input.inputDate:before {
        content: attr(data-date);
    }

    input.inputDate::-webkit-datetime-edit,
    input.inputDate::-webkit-inner-spin-button,
    input.inputDate::-webkit-clear-button {
        display: none;
    }

    input.inputDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }

</style>
@endsection


@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
<script type="text/javascript">
    window.addEventListener('load', function() {
        @if(request()->has('date_from') && request()->has('date_to'))
            $(".inputDate").on("change", function() {
                this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
            }).trigger("change");
        @else
            $(".inputDate").on("change", function() {
                this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
            });
        @endif
    });

</script>
@endsection
