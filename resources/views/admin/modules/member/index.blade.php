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

                           <member-list-component-test
                               
                                :members="{{ json_encode($members) }}"
                                api_token="{{ Auth::user()->api_token }}"
                                csrf_token="{{ csrf_token() }}"                            
                            />
                            
                            

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

            <create-member-component
                api_token="{{ Auth::user()->api_token }}"
                csrf_token="{{ csrf_token() }}"             
            />

            
 


        </div>




    </div>
</div>

</div>
@endsection
