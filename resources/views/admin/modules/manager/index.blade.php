@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/lesson') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link tfont-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manager</li>
            </ol>
        </nav>



        <div class="container">

            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Manager List
                </div>
                <div class="card-body">
                    <div class="row">
                        <form class="form-inline" style="width:100%">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Username:</label>
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
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-4" value="">
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
                            <button type="button" class="btn btn-primary btn-sm">Generate Manager List</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">

                            <manager-list-component />

                        </div>
                    </div>
                </div>
            </div>
            <!--[end] card-->

            <div class="card mt-4">
                <div class="card-header">Manager Form</div>

                <div class="card-body">
                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> E-Mail<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="email_id" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="password" class="form-control form-control-sm">
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
                                    <input type="text" name="name_en" class="form-control form-control-sm">
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
                                    <input type="text" name="name_jp" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_jp" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Is Japanese?<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="checkbox" name="is_japanese" class="mt-2">
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

            </div>




        </div>
    </div>

</div>
@endsection
