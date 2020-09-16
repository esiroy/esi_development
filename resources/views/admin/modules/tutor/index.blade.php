@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/lesson') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tutor</li>
            </ol>
        </nav>



        <div class="container">

            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Tutor List
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
                                    <label for="email" class="small col-2">Email:</label>
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
                            <button type="button" class="btn btn-primary btn-sm">Generate Tutor List</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">

                            <tutor-list-component />

                        </div>
                    </div>
                </div>
            </div>
            <!--[end] tutor list card-->

            <div class="card mt-4">
                <div class="card-header">Member Form</div>
                <div class="card-body">
                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Email <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="email" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="password" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Sort <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="password" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="salary_rate" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Salary Rate <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="salary_rate" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 small pr-0">
                                    <label for="grade" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Grade<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" name="grade" value="STANDARD" checked=""> Standard
                                    <input type="radio" name="grade" value="UPGRADE" class="ml-2"> Upgrade
                                    <input type="radio" name="grade" value="PLATINUM" class="ml-2"> Platinum
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="skype_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Skype Name <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="skype_name" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="skype_id" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Skype ID <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="skype_id" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_en" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name (English)<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="name_en" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_jp" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name (Japanese)<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="name_jp" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_jp" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Gender <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input type="radio" name="gender" value="MALE" class="form-check-input" checked=""> 男 (Male)
                                        <input type="radio" name="gender" value="FEMALE" class="ml-2"> 女 (Female)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="hobby" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Hobby<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="hobby" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="birthday" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Birthday<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="birthday" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="major" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Major in<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="major" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="introduction" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Introduction By Host <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <textarea name="introduction" class="form-control"></textarea>
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
                                    <label for="fluency" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Japanese <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <select name="fluency" class="form-control form-control-sm">
                                        <option value="">-- Select --</option>
                                        <option value="FLUENTLY">流暢に話す (Fluently)</option>
                                        <option value="DAILY_CONVERSATION">日常会話程度 (Daily Conversation)</option>
                                        <option value="LITTLE">少し話せる (Little)</option>
                                        <option value="CANT_SPEAK">話せない (Can't Speak)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="shift" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Shift <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <select name="shift" class="form-control form-control-sm">
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
                                    <label for="shift" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Default Main Tutor <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <div class="mt-2">
                                        <input type="checkbox" name="isDefaultMainTutor" value="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="is_terminated" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Is Terminated <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <div class="mt-2">
                                        <input type="checkbox" name="is_terminated" value="true">
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
            </div><!--[emd] member form-->




        </div>
    </div>

</div>
@endsection
