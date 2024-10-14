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
                                            <th class="small text-center bg-light text-dark font-weight-bold">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($questionnaires as $questionnaire)
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
