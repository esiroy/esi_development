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
                            <table border="0" cellspacing="9" cellpadding="0" align="center" class="tblRegister" width="100%">
                                <tbody>
                                    <tr>
                                        <th colspan="13">Personal Information</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td width="120px">Agent</td>
                                        <td>:</td>
                                        <td>
                                            {{ \App\Models\Tutor::find($member->main_tutor_id)['name_en'] }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td>Japanese LastName</td>
                                        <td>:</td>
                                        <td>{{ $member->user->last_name_jp }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Japanese Firstname</td>
                                        <td>:</td>
                                        <td>{{ $member->user->first_name_jp }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Last Name</td>
                                        <td>:</td>
                                        <td>{{ $member->user->last_name }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>First Name</td>
                                        <td>:</td>
                                        <td>{{ $member->user->first_name }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Attribute</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $member->attribute }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Nickname</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $member->nickname}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td>{{ $member->user->gender }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>


                                        <td>Skype Id</td>
                                        <td>:</td>
                                        <td>{{ $member->communication_app_username }}</td>





                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Member Id</td>
                                        <td>:</td>
                                        <td>126</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Birthday</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>

                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td>Age</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td valign="top">Hobby</td>
                                        <td valign="top">:</td>
                                        <td></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td colspan="9"></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Purpose</td>
                                        <td>:</td>
                                        <td>
                                            <ul>
                                                <li></li>
                                            </ul>
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
                                        <td colspan="9">2012年 6月 9日</td>
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
                                        <td id="maintutorcontainer" colspan="9">該当なし</td>
                                    </tr>



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




                                    <tr>
                                        <th colspan="13">Latest Report Card</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Subject</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Course</td>
                                        <td>:</td>
                                        <td colspan="10"></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Latest Material</td>
                                        <td>:</td>
                                        <td colspan="10"></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Grade</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th colspan="13">Latest Writing Report Card</th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Date</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Subject</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Course</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Latest Material</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Grade</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Comment</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Uploaded File</td>
                                        <td>:</td>
                                        <td>



                                            NONE


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
