@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User</li>
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
                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            予約表
                        </div>

                        <div class="card-body">

                            <table border="0" cellspacing="5" cellpadding="0">

                                <tbody>
                                    <tr>
                                        <td width="100">Member Name</td>
                                        <td>:</td>
                                        <td><strong>
                                            @php
                                                $member = \App\Models\Member::find($reportcard->member_id);                                                
                                            @endphp
                                            {{ $member->user->first_name }}
                                            {{ " " }}
                                            {{ $member->user->last_name }}
                                            
                                        </strong></td>
                                    </tr>

                                    <tr>
                                        <td>Tutor</td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                $schedule = \App\Models\ScheduleItem::find($reportcard->schedule_item_id); 
                                                $tutor = \App\Models\Tutor::find($schedule->tutor_id);                                                
                                            @endphp
                                            {{ $tutor->name_en }}                                            
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td>Lesson Date</td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                $schedule = \App\Models\ScheduleItem::find($reportcard->schedule_item_id);                                                
                                            @endphp

                                            <strong>{{ $schedule->lesson_time }}</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Lesson Course</td>
                                        <td>:</td>
                                        <td><strong>{{ $reportcard->lesson_course }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Lesson Material</td>
                                        <td>:</td>
                                        <td><strong>{{ $reportcard->lesson_material }}</strong></td>
                                    </tr>

                                    <tr valign="top">
                                        <td>Lesson Subject (Exercises)</td>
                                        <td>:</td>
                                        <td><strong>{{ $reportcard->lesson_subject }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Lesson Level</td>
                                        <td>:</td>
                                        <td><strong>{{ $reportcard->lesson_level }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>Understand</td>
                                        <td>:</td>
                                        <td>

                                            <input type="radio" name="grade" value="UNDERSTAND_86_100" @if (($reportcard->grade == 'UNDERSTAND_86_100')) {{ 'checked' }} @endif> understand 86-100 %<br>
                                            <input type="radio" name="grade" value="UNDERSTAND_65_85"  @if (($reportcard->grade == 'UNDERSTAND_65_85')) {{ 'checked' }} @endif> understand 65-85 %<br>
                                            <input type="radio" name="grade" value="UNDERSTAND_41_64" @if (($reportcard->grade == 'UNDERSTAND_41_64')) {{ 'checked' }} @endif> understand 41-64 %<br>
                                            <input type="radio" name="grade" value="UNDERSTAND_20_40" @if (($reportcard->grade == 'UNDERSTAND_20_40')) {{ 'checked' }} @endif> understand 20-40 %<br>
                                            <input type="radio" name="grade" value="UNDERSTAND_0_19" @if (($reportcard->grade == 'UNDERSTAND_0_19')) {{ 'checked' }} @endif> understand 0-19 %<br>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>

                                    <tr valign="top">
                                        <td>Tutor Comment</td>
                                        <td>:</td>
                                        <td>
                                            {{ $reportcard->comment }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

            </div>
        </div>


    </div>


</div>
</div>
@endsection
