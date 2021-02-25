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
                        <div class="row">

                            <div class="col-lg-3 col-md-3 col-sm-12 pt-2">
                                <label for="inputDate">From:</label>
                                <input id="date_from" name="date_from" type="date" class="inputDate hasDatepicker form-control form-control-sm  d-inline-block col-xl-9 col-lg-8 col-md-7 col-sm-12 col-xs-12" 
                                value="{{ request()->has('date_from') ? request()->get('date_from') : '' }}">                                
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-12 pt-2">
                                <label for="inputDate">To:</label>
                                <input id="date_to" name="date_to" type="date" class="inputDate hasDatepicker form-control form-control-sm col-xl-9 col-lg-8 col-md-7 col-sm-12 col-xs-12  d-inline-block" 
                                 value="{{ request()->has('date_to') ? request()->get('date_to') : '' }}">
                            </div>
                     

                            <div class="col-lg-3 col-md-3 col-sm-12 pt-2">
                                <label for="status">Status:</label>
                                <select name="status" id="shiftValue" class="form-control form-control-sm  d-inline-block col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 ">         
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

                           <div class="col-lg-2 col-md-3 col-sm-12 pt-2">
                                <input type="submit" class="btn btn-primary btn-sm" value="Go">
                            </div>
                        </div>
                    </form>

                    <div id="legend-chart" class="legend table-responsive bg-lightgray mt-2">
                        <table cellspacing="0" cellpadding="5" >
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
                            {{ $from }} 
                            @if(isset($to)) {{ "-" . $to }} @endif
                        </div>
                        <div class="card-body p-0 m-0 b-0">
                            <div class="table-responsive">
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
                                            {{ date("m/d/Y", strtotime($schedule->lesson_time ." + 1 hour")) }}
                                        </td>
                                        <td>
                                            {{ date("H:i", strtotime($schedule->lesson_time ." + 1 hour")) }}
                                        </td>
                                        <td>
                                            {{ formatStatus($schedule->schedule_status) }}                                            
                                        </td>
                                        <td>                                        
                                            {{ $schedule->lesson_shift_id }}
                                        </td>
                                        <td>
                                            @php
                                                $agent = new \App\Models\Agent();                                                
                                                $agentInfo = $agent->getMemberAgent($schedule->member_id);
                                            @endphp   
                                            {{ $agentInfo->firstname ?? ''}}
                                        </td>
                                        <td>
                                            @php                                                
                                                $tutor = \App\Models\Tutor::find($schedule->tutor_id)
                                            @endphp
                                            {{  $tutor->user->firstname ?? "-" }}
                                        </td>
                                        <td>
                                            
                                            @php                                                
                                                $member = \App\Models\Member::find($schedule->member_id);
                                                if (isset($member['user_id'])) {
                                                    $user = \App\Models\User::where('id',$member['user_id'])->first();
                                                }
                                            @endphp
                                           
                                            {{ $user->firstname ?? '' }}  {{ $user->lastname ?? '' }}
                                            
                                        </td>
                                    </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="float-right mt-4">
                        <ul class="pagination pagination-sm">
                            <small class="mr-4 pt-2">
                            Page :</small>
                            {{ $schedules->appends(request()->query())->links() }}
                        </ul>
                    </div>


                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection
