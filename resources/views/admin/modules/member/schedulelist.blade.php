@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>

        <div class="container bg-light">

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif

            <div id="member-details" class="card esi-card">
                <div class="card-header esi-card-header">
                    Schedule List 
                    <span class="small text-dark ml-5"> Total Reserved Current For the Month ({{ $totalReserved }}) </span>
                </div>

                <!--[start] HEADER MEMBER INFO DETAILS
                <div class="member mt-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2">Name</div>
                            <div class="col-md-9">
                                {{ $member->lastname  ?? ' - ' }},
                                {{ $member->firstname  ?? ' - ' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">Agent</div>
                            <div class="col-md-9" id="agent-{{ $agentInfo->user_id ?? '' }}">
                                {{ $agentInfo->user->firstname  ?? ' - ' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">Tutor</div>
                            <div class="col-md-9">
                         
                            {{ $tutorInfo->user->japanese_firstname  ?? ' - ' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">Lesson Class</div>
                            <div class="col-md-9">
                                毎月 {{$lessonLimit ?? '0'}} 回クラス (あと　残り {{ $memberLessonsRemaining ?? '0'}} 回)
                            </div>
                        </div>
                    </div>
                </div>
                [end] HEADER MEMBER INFO DETAILS--> 

                <div class="card-body esi-card-body">

                    <div class="table-responsive">
                        <table class="table table esi-table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Lesson Date</th>
                                    <th scope="col">Lesson Time</th>
                                    <th scope="col">ACCOUNT</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tutor</th>
                                    <th scope="col">Memo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $key => $schedule)
                                <tr>
                                    <td id="{{ $schedule->id  }}">
                                        {{ ESIDate($schedule->lesson_time) }}
                                    </td>
                                    <td>
                                        {{ ESIFormatTime($schedule->lesson_time) }}
                                        -
                                        @if ($schedule->schedule_status == "WRITING") 
                                            {{ ESIFormatTime(date('H:i',strtotime($schedule->lesson_time ."+ 25 minutes"))) }}
                                        @else 
                                            {{ ESIFormatTime(date('H:i',strtotime($schedule->lesson_time ."+ $schedule->duration minutes"))) }}
                                        @endif
                                    </td>
                                    <td class="pl-1 text-left">
                                        @if (isset($schedule->multiAccount->member_multi_account_id))
                                            <span class="badge badge-primary small">AC{{ $schedule->multiAccount->member_multi_account_id ?? '' }}</span>
                                            <span class="account-popover" data-toggle="popover" data-content="{{ $schedule->multiAccount->name }}">{!! Str::limit($schedule->multiAccount->name, 10, ' ...') !!}</span>
                                        @else 
                                            <span class="badge badge-primary small">AC1</span>
                                            <span class="small text-muted"> ~ </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($schedule->valid == false) 
                                            <div class="text-danger">
                                                {{ ucwords(str_replace('_', ' ', strtolower($schedule->schedule_status))) }}
                                                (OVERRIDED)
                                            </div>
                                        @else 
                                            {{ ucwords(str_replace('_', ' ', strtolower($schedule->schedule_status))) }}
                                        @endif                                        
                                    </td>

                                    <td>
                                        @php
                                            $tutor= new \App\Models\Tutor();
                                            $tutorInfo = $tutor->where('user_id', $schedule->tutor_id )->first();
                                        @endphp

                                        @if ($schedule->schedule_status == "WRITING") 
                                             {{ $tutorInfo->user->firstname ?? ' ~ not selected ~ ' }}
                                        @else 
                                            {{ $tutorInfo->user->firstname ?? '' }}
                                        @endif   

                                    </td>

                                    <td style="width:320px">
                                        {{ $schedule->memo }}

                                        @if ($schedule->schedule_status == "WRITING") 
                                            @php
                                                $writingEntry = new \App\Models\WritingEntries();  
                                                $entry = $writingEntry->where('schedule_id', $schedule->id)->first();
                                            @endphp
                                            
                                            <div style="display:none">
                                            
                                                Schedule ID {{ $schedule->id  }}
                                                Point : {{ $entry->total_points ?? "" }}  
                                            </div>

                                        @endif   

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                   

                    <div class="float-right">
                         {{ $schedules->links() ?? "" }}
                    </div>

                </div>
                <!--[end] card body-->



            </div>
        </div>


    </div>

</div>

@endsection


@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() {           

        $('.account-popover').popover({
            trigger: 'manual' // Don't trigger popover on click by default
        });

        // Show popover on hover
        $('.account-popover').on('mouseenter', function(){
            $(this).popover('show');
        });

        // Hide popover on mouse leave
        $('.account-popover').on('mouseleave', function(){
            $(this).popover('hide');
  });

    });
    </script>
@endsection