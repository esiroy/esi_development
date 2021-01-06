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
            <!--Member List -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                   Member Details
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <table border="0" cellspacing="9" cellpadding="3" align="center" class="tblRegister" width="100%">
                                <tbody>
                                    <tr>
                                        <th colspan="13">Personal Information</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td width="200px">Agent</td>
                                        <td>:</td>
                                        <td>
                                            {{ $agentInfo->firstname ?? " - " }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td>Japanese LastName</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->japanese_lastname ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Japanese Firstname</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->japanese_firstname ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Last Name</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->lastname ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>First Name</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->firstname ?? "-"}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Attribute</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $memberInfo->attribute ?? "-"}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Nickname</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $memberInfo->nickname ?? "-"}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->gender ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>{{ $memberInfo->communication_app ?? "-" }}</td>
                                        <td>:</td>
                                        
                                        <td>
                                            @if (strtolower($memberInfo->communication_app) == "skype") 
                                                {{ $memberInfo->skype_account ?? "-" }}
                                            @else
                                                {{ $memberInfo->zoom_account ?? "-" }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Member Id</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user_id ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Birthday</td>
                                        <td>:</td>
                                        <td>
                                            @if (isset($memberInfo->birthday))
                                            {{  date('F d, Y', strtotime($memberInfo->birthday)) ?? "-" }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td>Age</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->age ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td valign="top">Hobby</td>
                                        <td valign="top">:</td>
                                        <td>{{ $memberInfo->hobby ?? "-" }} </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td colspan="9">
                                            {{ $memberInfo->english_level ?? "-" }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Purpose</td>
                                        <td>:</td>
                                        <td>
                                            @foreach($lessonGoals as $goals)
                                            <ul class="mb-0">
                                                @if(isset($goals->purpose))
                                                    <li>{{ $goals->purposeDescription ?? '' }}</li>
                                                    <!--goal description-->
                                                    @if (isset($goals->goalDescription)) 
                                                    <ul class="mb-0">
                                                        <li>{{ $goals->goalDescription ?? '' }}</li>
                                                    </ul>
                                                    @endif
                                                    @if ($goals->extra_detail != "") 
                                                    <ul class="mb-0">
                                                        <li>{{ $goals->extra_detail ?? '' }}</li>
                                                    </ul>
                                                    @endif
                                                @endif
                                            </ul>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="13">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td colspan="13">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <th colspan="13">Lesson Details</th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Member Since</td>
                                        <td>:</td>
                                        <td colspan="9">
                                            {{  date('F d, Y', strtotime($memberInfo->member_since)) ?? "-" }}                                         
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Lesson Time</td>
                                        <td>:</td>
                                        <td colspan="9">25</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Main Tutor</td>
                                        <td>:</td>
                                        <td id="maintutorcontainer" colspan="9">
                                            @if (isset($tutorInfo->user->firstname))
                                                 {!! $tutorInfo->user->firstname !!}
                                            @endif
                                        </td>
                                    </tr>


                                    <!--
                                    <tr>
                                        <th colspan="13">Exam Record</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>TOEIC</td>
                                        <td>:</td>
                                        <td colspan="9">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Year</td>
                                                        <td>Month</td>
                                                        <td>Grade</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>EIKEN</td>
                                        <td>:</td>
                                        <td colspan="9">
                                            <table>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    -->



                                    <tr>
                                        <th colspan="13">Latest Report Card</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Subject</td>
                                        <td>:</td>
                                        <td>
                                            {{ $latestReportCard->lesson_subject ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Course</td>
                                        <td>:</td>
                                        <td colspan="10">
                                            {{ $latestReportCard->lesson_course ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Latest Material</td>
                                        <td>:</td>
                                        <td colspan="10">
                                            {{ $latestReportCard->lesson_material ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td>
                                             {{ $latestReportCard->lesson_level ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Grade</td>
                                        <td>:</td>
                                        <td>
                                            @if(isset($latestReportCard->grade))
                                                {{  formatGrade($latestReportCard->grade) ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>


                                    <tr>
                                        <th colspan="13">Latest Writing Report Card</th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Date</td>
                                        <td>:</td>
                                        <td>
                                            @if (isset($latestWritingReport->lesson_date))
                                                {{  date('F d, Y', strtotime($latestWritingReport->lesson_date)) ?? "-" }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Subject</td>
                                        <td>:</td>
                                        <td> {{ $latestWritingReport->lesson_subject ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Course</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->lesson_course ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Latest Material</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->lesson_material ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Grade</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->grade ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Comment</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->comment ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Uploaded File</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->file_name ?? '-' }}
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

@endsection
