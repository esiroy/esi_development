@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/lessons') }}">Lessons</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/salary') }}">Salary</a>

            </nav>
        </div>
    </div>

    <div class="esi-box">


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lessons</li>
            </ol>
        </nav>



        <div class="container">

            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Lesson List
                </div>
                <div class="card-body">
                    <form name="dateForm" method="GET">                     
                            
                         <table>
                            <td>
                               <label for="inputDate">From:</label>
                            </td>
                            <td style="width:150px">
                                <input id="date_from" name="date_from" type="date" data-date-format="YYYY年 M月 DD日" class="inputDate hasDatepicker form-control form-control-sm col-12"
                                 value="{{ request()->has('date_from') ?  request()->get('date_from') : $dateFrom }}" style="min-width:120px">
                            </td>

                            <td>
                                <label for="inputDate"  class="ml-2">To:</label>
                            </td>
                            <td style="width:150px">
                                <input id="date_to" name="date_to" type="date" data-date-format="YYYY年 M月 DD日" class="inputDate hasDatepicker form-control form-control-sm  col-12" 
                                value="{{ request()->has('date_to') ? request()->get('date_to') : $dateTo }}" style="min-width:120px">
                           </td>

                            <td>                                
                                <label for="status"  class="ml-2">Status:</label>
                            </td>

                            <td style="width:150px">
                                <select name="status" id="status" class="form-control form-control-sm" style="min-width:120px">
                                    <option value="">All</option>
                                    <option value="Tutor Scheduled" {{ (request()->has('status') && request()->get('status') == "Tutor Scheduled") ? "selected" : '' }}>Tutor Scheduled</option>
                                    <option value="Client Reserved" {{ (request()->has('status') && request()->get('status') == "Client Reserved") ? "selected" : '' }}>Client Reserved</option>
                                    <option value="Client Reserved B" {{ (request()->has('status') && request()->get('status') == "Client Reserved B") ? "selected" : '' }}>Client Reserved B</option>
                                    <option value="Suppressed Schedule" {{ (request()->has('status') && request()->get('status') == "Suppressed Schedule") ? "selected" : '' }}>Suppressed Schedule</option>
                                    <option value="Completed" {{ (request()->has('status') && request()->get('status') == "Completed") ? "selected" : '' }}>Completed</option>
                                    <option value="Tutor Cancelled" {{ (request()->has('status') && request()->get('status') == "Tutor Cancelled") ? "selected" : '' }}>Tutor Cancelled</option>
                                    <option value="Client Not Available" {{ (request()->has('status') && request()->get('status') == "Client Not Available") ? "selected" : '' }}>Client Not Available</option>
                                    <option value="Nothing" {{ (request()->has('status') && request()->get('status') == "Nothing") ? "selected" : '' }}>Nothing</option>
                                </select>
                            </td>

                            <td>
                                <div class="d-inline-block ml-2">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Go">
                                </div>
                            </td>
                        </table>                                                                             
                           


                      
                    </form>

                    <div id="legend-chart" class="legend table-responsive bg-lightgray mt-2">
                        <table cellspacing="0" cellpadding="5">
                            <tbody>
                                <tr>
                                    <td>Legend</td>
                                    <td>:</td>
                                    <td><img src="{{ url('images/iNothing.gif') }}" alt="Nothing" title="Nothing" align="absmiddle"> Nothing</td>
                                    <td><img src="{{ url('images/iTutorScheduled.gif') }}" alt="Tutor Scheduled" title="Tutor Scheduled" align="absmiddle"> Tutor Scheduled</td>
                                    <td><img src="{{ url('images/iClientReserved.gif') }}" alt="Client Reserved" title="Client Reserved" align="absmiddle"> Client Reserved</td>
                                    <td><img src="{{ url('images/iSuppressed.gif') }}" alt="Suppressed Schedule" title="Suppressed Schedule" align="absmiddle"> Suppressed Schedule</td>
                                    <td class="w-25"></td>
                                </tr>
                                <tr>

                                    <td></td>
                                    <td></td>
                                    <td><img src="{{ url('images/iCompleted.gif') }}" alt="Completed" title="Completed" align="absmiddle"> Completed</td>
                                    <td><img src="{{ url('images/iTutorCancelled.gif') }}" alt="Tutor Cancelled" title="Tutor Cancelled" align="absmiddle"> Tutor Cancelled</td>
                                    <td><img src="{{ url('images/iNotAvailable.gif') }}" alt="Not Available" title="Not Available" align="absmiddle"> Client Not Available</td>
                                    <td></td>
                                    <td class="w-25"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <div id="schedule-report" class="card mt-3">
                        <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold ">                        
                            {{ date("Y年 m月 d日", strtotime($dateFrom)) }}
                            @if(isset($dateTo)) {{ " - " . date("Y年 m月 d日", strtotime($dateTo)) }} @endif
                        </div>
                        <div class="card-body p-0 m-0 b-0">
                            <div class="table-responsive mb-0">
                                <table class="table esi-table table-bordered table-striped  ">
                                    <thead>
                                        <td>I.D.</td>
                                        <td>Date</td>
                                        <td>Time</td>
                                        <td>Status</td>
                                        <td>Shift</td>
                                        <td>Agent</td>
                                        <td>Tutor</td>
                                        <td>Member</td>
                                    </thead>

                                    @foreach($schedules as $schedule)
                                    
                                    <tr>
                                        <td>
                                            {{ $schedule->id }}
                                        </td>
                                        <td>
                                            <!-- Date -->
                                            @if ( date("H", strtotime($schedule->lesson_time)) == "00")
                                                {{ date("m/d/Y", strtotime($schedule->lesson_time ." -1 day" )) }}    
                                            @else 
                                                {{ date("m/d/Y", strtotime($schedule->lesson_time )) }}    
                                            @endif
                                            
                                        </td>

                                        <td>
                                            <!-- Time -->      
                                            @if (date("H", strtotime($schedule->lesson_time)) == "00")
                                                {{ date("24:i", strtotime($schedule->lesson_time)) }}
                                            @else 
                                                {{ date("H:i", strtotime($schedule->lesson_time)) }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ formatStatus($schedule->schedule_status) }}
                                        </td>
                                        <td>
                                            @php
                                                $shift = App\Models\Shift::where('id', $schedule->lesson_shift_id)->first();
                                            @endphp

                                            {{ $shift->name ?? ''}}
                                        </td>
                                        <td>
                                            @php
                                                //AGENT NAME
                                                $agent = new \App\Models\Agent();
                                                $agentInfo = $agent->getMemberAgent($schedule->member_id);
                                            @endphp
                                            {{ $agentInfo->user->firstname ?? '' }} {{ $agentInfo->user->lastname ?? '' }}
                                        </td>
                                        <td>
                                            @php
                                                //TUTOR NAME
                                                $tutor = \App\Models\Tutor::where('user_id', $schedule->tutor_id)->first();                                    
                                            @endphp

                                            {{ $tutor->user->firstname ?? "-" }}
                                        </td>
                                        <td>
                                            @php
                                                //MEMBER NAME
                                                $member = \App\Models\Member::where('user_id', $schedule->member_id)->first();                                            
                                            @endphp
                                            {{ $member->user->firstname ?? '' }} {{ $member->user->lastname ?? '' }}
                                        </td>
                                    </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                    </div>

                      <div class="row mt-2">

                        <div class="col-md-5">
                            <span class="mr-4">
                                <a href="/downloadlessonReport?type=pdf&dateFrom={{$dateFrom}}&dateTo={{$dateTo}}&status={{ Request::get('status')}}">
                                    <img src="{{ url('images/pdf.gif') }}"> Download As PDF
                                </a>
                            </span>
                            <span>
                                <a href="/downloadlessonReport?type=excel&dateFrom={{$dateFrom}}&dateTo={{$dateTo}}&status={{ Request::get('status')}}">
                                    <img src="{{ url('images/excel.gif') }}"> Download As Excel
                                </a> 
                            </span>
                        </div>

                        <div class="col-md-7">
                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    {{ $schedules->appends(request()->query())->links() }}
                                </ul>
                            </div>
                        </div>
                        
                    </div>


                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection

@section('styles')
@parent
<style>
    input.inputDate {
        overflow: hidden;
    }

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
        $(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")
    });
</script>
@endsection