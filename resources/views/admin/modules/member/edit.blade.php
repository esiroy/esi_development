@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

 
    @include('admin.modules.member.includes.menu')

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
