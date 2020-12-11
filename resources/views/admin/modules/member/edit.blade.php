@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/lesson') }}">Member</a>
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
        <div class="container">
            <member-update-component
                :memberships="{{ json_encode($memberships) }}"
                :attributes="{{ json_encode($attributes) }}"
                :shifts="{{ json_encode($shifts) }}"
                :member="{{ json_encode($memberInfo) }}"
                :lessonclasses="{{ json_encode($lessonClasses) }}"
                :desiredschedule="{{ json_encode($desiredSchedule) }}"
                :purposes="{{ json_encode($purposes) }}"
                api_token="{{ Auth::user()->api_token }}"
                csrf_token="{{ csrf_token() }}"                     
            />
        </div>
    </div>
</div>

</div>
@endsection
