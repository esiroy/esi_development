@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary " href="{{ url('admin/lessons') }}">Lessons</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/salary') }}">Salary</a>

            </nav>
        </div>
    </div>

    <div class="esi-box">


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Salary</li>
            </ol>
        </nav>

        <div class="container">
            <div class="card esi-card">
                <!--[start card] -->
                <div class="card-header esi-card-header h5">Salary</div>
                <div class="card-body">

                    <form name="dateForm" method="GET">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12 pt-2">
                                <label for="inputDate">From:</label>
                                <input id="date_from" name="date_from" type="date" data-date-format="YYYY年  M月 DD日" class="inputDate hasDatepicker form-control form-control-sm  d-inline-block col-xl-9 col-lg-8 col-md-7 col-sm-12 col-xs-12" value="{{ request()->has('date_from') ? request()->get('date_from') : $from  }}">
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12 pt-2">
                                <label for="inputDate">To:</label>
                                <input id="date_to" name="date_to" type="date" data-date-format="YYYY年  M月 DD日" class="inputDate hasDatepicker form-control form-control-sm col-xl-9 col-lg-8 col-md-7 col-sm-12 col-xs-12 d-inline-block" value="{{ request()->has('date_to') ? request()->get('date_to') : $to }}">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-lg-3 col-md-3 col-sm-12 pt-2">
                                <label for="status">Status:</label>
                                <select name="status" id="status" class="form-control form-control-sm  d-inline-block col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
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
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 pt-2">
                                <label for="status">Tutor:</label>
                                <select name="tutor" id="tutor" class="form-control form-control-sm  d-inline-block col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <option value="">All</option>
                                    @foreach($tutors as $key => $tutor)
                                    <option value="{{ $tutor->user_id }}" {{ (request()->has('tutor') && request()->get('tutor') == $tutor->user_id) ? "selected" : '' }}>
                                        {!! $tutor->user->firstname ?? '' !!} {!! $tutor->user->lastname ?? '' !!}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="d-inline-block">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Go">
                                </div>
                            </div>


                        </div>

                    </form>


                    <div id="schedule-report" class="card mt-3">
                        <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                            {{ date("Y年 m月 d日", strtotime($from)) }}
                            @if(isset($to)) {{ " - " . date("Y年 m月 d日", strtotime($to)) }} @endif
                        </div>
                        <div class="card-body p-0 m-0 b-0">

                            <div class="table-responsive mb-0">
                                <table class="table esi-table table-bordered table-striped  ">
                                    <thead>
                                        <td>I.D.</td>
                                        <td>Tutor</td>
                                        <td>Shift</td>
                                        <td>Date</td>
                                        <td>Time</td>
                                        <td>Status</td>
                                        <td>Salary</td>
                                        <td>Cost</td>
                                    </thead>

                                    @foreach($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->id }}</td>
                                        <td>
                                            @php
                                                $tutor = \App\Models\Tutor::where('user_id', $schedule->tutor_id)->first();
                                            @endphp
                                            {!! $tutor->user->firstname ?? "" !!}
                                        </td>

                                        <td>
                                           
                                            @php
                                                $shift = App\Models\Shift::where('id', $schedule->lesson_shift_id)->first();
                                            @endphp

                                            {{ $shift->name ?? ''}}                                            
                                        </td>

                                        <td>
                                            <!-- Date -->
                                            @if ( date("H", strtotime($schedule->lesson_time)) == "00")
                                                {{ date("F j, Y", strtotime($schedule->lesson_time ." -1 day" )) }}
                                            @else
                                                {{ date("F j, Y", strtotime($schedule->lesson_time)) }}                                                
                                            @endif
                                        </td>

                                        <td>
                                            <!-- Time -->      
                                            @if (date("H", strtotime($schedule->lesson_time)) == "00")
                                                {{ date("24:i", strtotime($schedule->lesson_time)) }}
                                                {{ " - " . date("24:i", strtotime($schedule->lesson_time ." +25 minutes")) }}
                                            @else 
                                                {{ date("H:i", strtotime($schedule->lesson_time)) }}
                                                {{ " - " . date("H:i", strtotime($schedule->lesson_time ." +25 minutes")) }}                                                
                                            @endif
                                        </td>                                        


                                        <td>
                                            {{ ucwords(str_replace("_", " ", strtolower($schedule->schedule_status))) }}
                                        </td>
                                        <td>
                                            @php
                                            $tutor = \App\Models\Tutor::where('user_id', $schedule->tutor_id)->first();
                                            @endphp
                                            {{ $tutor->salary_rate ?? "" }}
                                        </td>
                                        <td>

                                            @if (isset($tutor->salary_rate))
                                                @if ($schedule->schedule_status == "COMPLETED" || $schedule->schedule_status == "CLIENT_NOT_AVAILABLE") 
                                                    {{ number_format($tutor->salary_rate, 1) }}
                                                @elseif($schedule->schedule_status == "SUPPRESSED_SCHEDULE" )
                                                    @php
                                                        $newSalary = $tutor->salary_rate / 2;
                                                    @endphp
                                                    {{ number_format($newSalary, 1) }}                                                
                                                @else
                                                    {{ number_format(0, 1) }}
                                                @endif
                                            @endif

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
                                <a href="/downloadSalaryReport?type=pdf&dateFrom={{$from}}&dateTo={{$to}}&tutorid={{ Request::get('tutor') }}&status={{ Request::get('status')}}">
                                    <img src="{{ url('images/pdf.gif') }}"> Download As PDF
                                </a>
                            </span>
                            <span>
                                <a href="/downloadSalaryReport?type=excel&dateFrom={{$from}}&dateTo={{$to}}&tutorid={{ Request::get('tutor') }}&status={{ Request::get('status')}}">
                                    <img src="{{ url('images/excel.gif') }}"> Download As Excel
                                </a> 
                            </span>
                        </div>

                        <div class="col-md-7">
                            <div class="float-right">
                            <ul class="pagination pagination-sm pb-0 mb-0">
                                {{ $schedules->appends(request()->query())->links() }}
                            </ul>
                            </div>
                        </div>
                    </div>


                </div>
                <!--[end] card body-->



            </div>
            <!--[end] card-->




        </div>
        <!--[end] container -->
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
        $(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")
    });

</script>
@endsection
