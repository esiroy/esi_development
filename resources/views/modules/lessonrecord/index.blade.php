@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lesson Record</li>
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
                            受講履歴
                        </div>

                        <div class="card-body">


                            <div class="my-2">
                                <span class="">これまで受講した一覧です。</span>
                                <span class="ml-3">（「欠席」も含まれます）</span>
                            </div>

                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Date</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Tutor</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">View</th>
                                    </tr>
                                    @foreach ($scheduleItems as $scheduleItem)
                                    <tr id="{{ 'scheduleItem-'. $scheduleItem->id }}">
                                        <td class="text-center">

                                           {{ ESIDateFormat($scheduleItem->lesson_time) }} {{ ESILessonTimeRange($scheduleItem->lesson_time) }}
                                           

                                        </td>

                                        <td class="text-center">
                                            <!-- Tutor -->                                            
                                            @php                                             
                                                $tutor =  \App\Models\Tutor::where('user_id', $scheduleItem->tutor_id)->first();                                                                                            
                                            @endphp


                                            @if (isset($tutor->user->japanese_firstname)) 
                                                {{ $tutor->user->japanese_firstname ?? '' }} {{ $member->tutor->japanese_lastname ?? '' }}
                                            @else 
                                                {{ $tutor->user->firstname ?? '' }} {{ $member->tutor->lastname ?? '' }}
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            <!--@todo: create a link if there is lesson slider -->
                                            @php                                                
                                                $lessonHistory = new App\Models\LessonHistory;
                                                $lessonHistory = $lessonHistory->where('schedule_id', $scheduleItem->id)
                                                                ->orderBy('id', 'DESC')
                                                                ->first();
                                            @endphp

                                            @if ($lessonHistory) 

                                                <a href="lessonslidehistory/{{$lessonHistory->schedule_id}}">» 評価</a> 

                                            @else

                                                <!--@done: create a link for the graded report card only-->
                                                @php                                                
                                                    $reportcard = new App\Models\ReportCard;
                                                    $gradedReportcard = $reportcard->where('schedule_item_id', $scheduleItem->id)->first();
                                                @endphp

                                                @if ($gradedReportcard)                                                
                                                    <a href="reportcard/{{$gradedReportcard->id}}">» 評価</a>                                                
                                                @else 
                                                    » 評価
                                                @endif

                                            @endif


                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    {{ $scheduleItems->appends(request()->query())->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div class="card esi-card mt-4">
                        <div class="card-header esi-card-header">
                            添削履歴
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Date</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Tutor</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">View</th>
                                    </tr>
                                </thead>
                                <tbody id="reportCardBody">
                                    @foreach ($datereportcards as $datereportcard)
                                    <tr>
                                        <td class="text-center">
                                            {{ ESIDateFormat($datereportcard->lesson_date) }}
                                        </td>
                                        <td class="text-center">
                                          <!-- Tutor -->                                            
                                            @php                                             
                                                $tutor =  \App\Models\Tutor::where('user_id', $datereportcard->tutor_id)->first();                                                                                            
                                            @endphp

                                            @if (isset($tutor->user->japanese_firstname)) 
                                                {!! $tutor->user->japanese_firstname ?? '' !!} {!! $member->tutor->japanese_lastname ?? '' !!}
                                            @else 
                                                {!! $tutor->user->firstname ?? '' !!} {!! $member->tutor->lastname ?? '' !!}
                                            @endif
                                        </td>
                                        <td class="text-center"><a href="userreportcarddate/{{$datereportcard->id}}">» 評価</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    {{ $datereportcards->appends(request()->query())->links() }}
                                </ul>
                            </div>

                        </div>
                    </div>


                     <div class="card esi-card mt-4">
                        <div class="card-header esi-card-header">
                            Mini Test Results
                        </div>

                        <div class="card-body">


                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Test</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Time Started - Time Ended</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Score</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">View</th>
                                    </tr>
                                </thead>
                               
                                <tbody id="reportCardBody">

                                    @if(count($miniTestResults) >= 1)
                                        @foreach ($miniTestResults as $result)
                                        <tr>
                                            <td class="text-left pl-4 small">
                                                {{ $result->name }}
                                            </td>
                                            <td class="text-left pl-4 small">
                                                {{ ESIDateTimeFormat($result->time_started)  ." - " }} 
                                                @if(!(int)$result->time_ended)                                                 
                                                    <span class="text-danger">{{ "Unfinished" }}</span>
                                                @else 
                                                    <span > {{ ESIDateTimeFormat($result->time_ended) }} </span>                                                    
                                                @endif                                        
                                            </td>
                                            <td class="text-center">
                                                {{ $result->correct_answers }} / {{ $result->total_questions }}
                                            </td>
                                            <td class="text-center"><a href="minitest/results/{{$result->id}}">» 見る</a></td>
                                        </tr>
                                        @endforeach
                                    @else 
                                        <tr>
                                            <td class="text-center text-primary small" colspan="3">
                                                {{ "No Minitest Submitted" }}
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table> 
                                
                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    {{ $miniTestResults->appends(request()->query())->links() }}
                                </ul>
                            </div>


                        </div>
                    </div>


                </div>

            </div>
        </div>


    </div>


</div>
</div>
@endsection