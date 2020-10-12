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

            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Member List
                </div>
                <div class="card-body">
                    <div class="row">
                        <form class="form-inline" style="width:100%">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Nickname:</label>
                                    <input id="nickname" name="nickname" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Name:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="small col-2">Email:</label>
                                    <input id="email" name="name" type="text" class="form-control form-control-sm col-4" value="">

                                    <select id="filterLessonShift" name="filterLessonShift" class="form-control form-control-sm col-3 ml-1">
                                        <option value="">-- Select --</option>
                                        <option value="4">25 mins</option>
                                        <option value="5">40 mins</option>
                                    </select>
                                    <button type="button" class="btn btn-primary btn-sm col-1 ml-1">Go</button>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            <button type="button" class="btn btn-primary btn-sm">Generate Member List</button>
                            <button type="button" class="btn btn-primary btn-sm">Sort Soon to Expire</button>
                            <button type="button" class="btn btn-primary btn-sm">Sort Expired</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">

                            <member-list-component />

                            <!--
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="small text-center">ID</th>
                                        <th class="small text-center">Name</th>
                                        <th class="small text-center">Nickname</th>
                                        <th class="small text-center">Attribute</th>
                                        <th class="small text-center">Agent</th>
                                        <th class="small text-center">Email</th>
                                        <th class="small text-center">Skype / Zoom</th>
                                        <th class="small text-center">Credit</th>
                                        <th class="small text-center">Class</th>
                                        <th class="small text-center">Main Tutor</th>
                                        <th class="small text-center">History</th>
                                        <th class="small text-center">Report<br>Card</th>
                                        <th class="small text-center">Writing<br>Report</th>
                                        <th class="small text-center">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="small">1</td>
                                        <td class="small">Roy</td>
                                        <td class="small">Programmer</td>
                                        <td class="small">Member</td>
                                        <td class="small">Mr. Smith</td>
                                        <td class="small">mrsmith@gmail.com</td>
                                        <td class="small">mrsmith@gmail.com</td>
                                        <td class="small">99.0</td>
                                        <td class="small text-center"><img src="{{ url('images/iClass.jpg')}}"></td>
                                        <td class="small">該当なし</td>
                                        <td class="small text-center"><img src="{{ url('images/iHistory.jpg')}}"></td>
                                        <td class="small text-center"><img src="{{ url('images/iReportCard.jpg')}}"></td>
                                        <td class="small text-center"><img src="{{ url('images/iMonthlyRC.jpg')}}"></td>
                                        <td class="small text-center">Account | Edit  | Delete </td>                                   
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
            <!--[end] card-->

            <create-member-component/>

            
            <!--
            <div class="card mt-4">
                <div class="card-header">Member Form</div>
                <div class="card-body">
                    <div id="member-persional-information" class="section">
                        <div class="card-title bg-gray p-1">
                            <div class="pl-2 font-weight-bold small">Personal Information</div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="agent" class="px-0 pl-2 col-md-12 col-form-label"><span>&nbsp;</span>Agent <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="agent" class="form-control  form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label">Agent ID<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="agent_id" class="form-control  form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Last Name <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="last_name" class="form-control  form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="first_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> First Name<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="first_name" class="form-control  form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Attribue <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <select id="attribute" name="memberattribute" class="form-control form-control-sm">
                                            <option value="">-- Select --</option>
                                            <option value="TRIAL">Trial</option>
                                            <option value="MEMBER">Member</option>
                                            <option value="WITHDRAW">Withdraw</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="nickname" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Nickname <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="nickname" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Gender <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group my-0 pt-2">
                                            <input type="radio" name="gender" value="MALE" checked="checked" class="col-1">
                                            <label for="gender" class="small col-2 px-0">Male:</label>
                                            <input type="radio" name="gender" value="FEMALE" class="col-1">
                                            <label for="gender" class="small col-3 px-0">Female:</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="communication_app" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Communication App <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-8">
                                        <div class="row my-0">
                                            <div class="col-5">
                                                <select id="communication_app_choices" name="communication_app_choices" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                    <option value="skype">Skype</option>
                                                    <option value="zoom">Zoom</option>
                                                </select>
                                            </div>
                                            <div class="col-6 px-0">
                                                <input type="text" name="communication_app" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="id" class="px-0 col-md-12 col-form-label"><span>&nbsp;</span> Member ID <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="id" class="form-control form-control-sm bg-white" value="Auto Generated" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> E-Mail Adress (Username) <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="email" class="form-control form-control-sm" placeholder="E-mail Address">
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="password" class="form-control form-control-sm" placeholder="Password">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="birthday" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Birthday <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-4">
                                        <input type="text" name="birthday" class="form-control form-control-sm" placeholder="Birthday">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="birthday" class="px-0 col-md-12 col-form-label"><span class="text-danger"> &nbsp;</span> Age <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" name="birthday" class="form-control form-control-sm" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="birthday" class="px-0 col-md-12 col-form-label"><span class="text-danger"> &nbsp;</span> Membership <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-8">
                                        <select name="membership" class="form-control form-control-sm">
                                            <option value="">-- Select --</option>
                                            <option value="Point Balance">Point Balance</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Both">Both</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div id="member-persional-information" class="section">
                        <div class="card-title bg-gray p-1 mt-4">
                            <div class="pl-2 font-weight-bold small">Preferred Tutor</div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                        <label for="purpose" class="p-0 col-md-12 col-form-label"><span class="text-danger">*</span> Purpose <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-8">
                                        <ul class="checkbox-options">
                                            <li>
                                                <input type="checkbox" name="purposes" id="bilingual" value="BILINGUAL"> Take part in Bilingual training course
                                                <input type="hidden" name="extraDetails"></li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="conversation" value="CONVERSATION">
                                                Get conversation(communication) skill
                                                <ul id="goalList" class="checkbox-options">
                                                    <li><input type="radio" name="goal" value="BEGINNER" checked=""> Beginner- easy daily conversation level</li>
                                                    <li><input type="radio" name="goal" value="INTERMEDIATE"> Intermediate- Daily conversation level</li>
                                                    <li><input type="radio" name="goal" value="ADVANCE"> Advance - Social, Environment, Business English</li>
                                                    <li><input type="radio" name="goal" value="NATIVE"> Be native level</li>
                                                </ul>
                                                <input type="hidden" name="extraDetails" value="BEGINNER">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="antieken" value="ANTI_EIKEN">
                                                English certification exam in Japan</span>
                                                <input type="text" name="extraDetails">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="antiexam" value="ANTI_EXAM">
                                                Enter school</span>
                                                <ul id="examLevel" style="list-style-type: none; display: none;">
                                                    <li><input type="radio" name="antiExamLevel" value="JUNIOR_HIGH" checked=""> Junior High</li>
                                                    <li><input type="radio" name="antiExamLevel" value="HIGHSCHOOL"> High school</li>
                                                    <li><input type="radio" name="antiExamLevel" value="UNIVERSITY"> University</li>
                                                </ul>
                                                <input type="hidden" name="extraDetails" value="UNIVERSITY">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="toefl" value="TOEFL"> TOEFL(目標スコアー 点)
                                                <input type="text" name="extraDetails">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="toeic" value="TOEIC"> TOEIC(目標スコアー 点)
                                                <input type="text" name="extraDetails">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="studyabroad" value="STUDY_ABROAD"> Study Abroad
                                                <ul id="abroadLevel" style="list-style-type: none; display: none;">
                                                    <li><input type="radio" name="studyAbroadLevel" value="JUNIOR_HIGH" checked=""> Junior High</li>
                                                    <li><input type="radio" name="studyAbroadLevel" value="HIGHSCHOOL"> High school</li>
                                                    <li><input type="radio" name="studyAbroadLevel" value="UNIVERSITY"> University</li>
                                                </ul>
                                                <input type="hidden" name="extraDetails" value="JUNIOR_HIGH" style="display: none;">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="business" value="BUSINESS"> Business English
                                                <input type="hidden" name="extraDetails">
                                            </li>
                                            <li>
                                                <input type="checkbox" name="purposes" id="others" value="OTHERS"> Others
                                                <textarea name="extraDetails" rows="2" cols="20" style="vertical-align: top; display: none;"></textarea>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div id="member-lesson-details" class="section">
                        <div class="card-title bg-gray p-1 mt-4">
                            <div class="pl-2 font-weight-bold small">Lesson Details</div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Member Since<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="agent_id" class="form-control  form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Lesson Time<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <select id="lessonshiftid" name="lessonshiftid" class="form-control form-control-sm">
                                            <option value="">-- Select --</option>
                                            <option value="4">25 mins</option>
                                            <option value="5">40 mins</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Main Tutor<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <select id="lessonshiftid" name="lessonshiftid" class="form-control form-control-sm">
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div id="member-exam-records" class="section">
                        <div class="card-title bg-gray p-1 mt-4">
                            <div class="pl-2 font-weight-bold small">Exam Record</div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> TOEIC<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-10">

                                        <div class="row">
                                            <div class="col-2 pr-0">
                                                <div class="text-center">Year</div>
                                                <select id="year" name="year" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">
                                                <div class="text-center">Month</div>
                                                <select id="month" name="month" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">
                                                <div class="text-center">Grade</div>
                                                <input type="text" name="agent_id" class="form-control  form-control-sm d-inline-block">
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm d-inline-block mt-4">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> EIKEN <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-10">

                                        <div class="row">
                                            <div class="col-2 pr-0">

                                                <select id="year" name="year" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">

                                                <select id="month" name="month" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">
                                                <input type="text" name="agent_id" class="form-control  form-control-sm d-inline-block">
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm d-inline-block">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="report-requirement" class="section">
                        <div class="card-title bg-gray p-1 mt-4">
                            <div class="pl-2 font-weight-bold small">Report Requirement</div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Report Card<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-2 pr-0">
                                                <div class="text-center">Member</div>
                                                <select id="year" name="year" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">
                                                <div class="text-center">Agent</div>
                                                <select id="month" name="month" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Report Card<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-2 pr-0">
                                                <select id="year" name="year" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">
                                                <select id="month" name="month" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="point-purchase" class="section">
                        <div class="card-title bg-gray p-1 mt-4">
                            <div class="pl-2 font-weight-bold small">Point Purchase Type</div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                        <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Point Purchase<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-2 pr-0">
                                                <select id="pointpurchase" name="pointpurchase" class="form-control form-control-sm">
                                                    <option value="">-- Select --</option>
                                                    <option value="AGENT">Agent</option>
                                                    <option value="DIRECT">Direct</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="point-purchase" class="section">
                        <div class="card-title bg-gray p-1 mt-4">
                            <div class="pl-2 font-weight-bold small">Desired Schedule </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2 small pr-0">
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-2 pr-0">
                                                <select id="selectDay" class="form-control form-control-sm d-inline-block">
                                                    <option value="">-- Select --</option>
                                                    <option value="MONDAY">Monday</option>
                                                    <option value="TUESDAY">Tuesday</option>
                                                    <option value="WEDNESDAY">Wednesday</option>
                                                    <option value="THURSDAY">Thursday</option>
                                                    <option value="FRIDAY">Friday</option>
                                                    <option value="SATURDAY">Saturday</option>
                                                    <option value="SUNDAY">Sunday</option>
                                                </select>
                                            </div>
                                            <div class="col-2 pr-0">
                                                <input type="text" name="agent_id" class="form-control form-control-sm d-inline-block">
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm d-inline-block">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row py-4">
                    <div class="col-2"></div>
                    <div class="col-3 text-left">
                        <button type="button" class="btn btn-primary btn-sm">Save</button>
                        <button type="button" class="btn btn-primary btn-sm">Cancel</button>
                    </div>
                </div>
            </div>
            -->


        </div>




    </div>
</div>

</div>
@endsection
