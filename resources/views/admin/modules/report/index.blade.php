@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/lessons') }}">Lessons</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/salary') }}">Salary</a>

            </nav>
        </div>
    </div>

    <div class="esi-box">


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lessons</li>
            </ol>
        </nav>



        <div class="container">

            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header h5">
                    Lesson List
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12 pt-2">
                            <label for="inputDate">From:</label>
                            <input id="dateFrom" type="text" class="inputDate hasDatepicker form-control form-control-sm  d-inline-block col-xl-9 col-lg-8 col-md-7 col-sm-12 col-xs-12" value="2020年 9月 7日">
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12 pt-2">
                            <label for="inputDate">To:</label>
                            <input id="dateTo" type="text" class="inputDate hasDatepicker form-control form-control-sm col-xl-9 col-lg-8 col-md-7 col-sm-12 col-xs-12  d-inline-block" value="2020年 9月 7日">
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12 pt-2">
                            <div class="form-group">
                                <label for="shift">Shift:</label>
                                <select id="shift" class="form-control form-control-sm  d-inline-block col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <option value="4" selected="">25 mins</option>
                                    <option value="5">40 mins</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12 pt-2">
                            <label for="status">Status:</label>
                            <select e="status" id="shiftValue" class="form-control form-control-sm  d-inline-block col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
                                <option value="4" selected="">25 mins</option>
                                <option value="5">40 mins</option>
                            </select>
                        </div>
                    </div>

                    <div id="legend-chart" class="legend table-responsive bg-lightgray mt-2">
                        <table cellspacing="0" cellpadding="5" >
                            <tbody>
                                <tr>
                                    <td>Legend</td>
                                    <td>:</td>
                                    <td><img src="{{ url('images/iNothing.gif') }}" alt="Nothing" title="Nothing" align="absmiddle"> Nothing</td>
                                    <td><img src="{{ url('images/iTutorScheduled.gif') }}" alt="Tutor Scheduled" title="Tutor Scheduled" align="absmiddle"> Tutor Scheduled</td>
                                    <td><img src="{{ url('images/iClientReserved.gif') }}" alt="Client Reserved" title="Client Reserved" align="absmiddle"> Client Reserved</td>
                                    <td><img src="{{ url('images/iSuppressed.gif') }}" alt="Suppressed Schedule" title="Suppressed Schedule" align="absmiddle"> Suppressed Schedule</td>
                                    <td class="w-25"></td>
                                </tr>
                                <tr>

                                    <td></td>
                                    <td></td>
                                    <td><img src="{{ url('images/iCompleted.gif') }}" alt="Completed" title="Completed" align="absmiddle"> Completed</td>
                                    <td><img src="{{ url('images/iTutorCancelled.gif') }}" alt="Tutor Cancelled" title="Tutor Cancelled" align="absmiddle"> Tutor Cancelled</td>
                                    <td><img src="{{ url('images/iNotAvailable.gif') }}" alt="Not Available" title="Not Available" align="absmiddle"> Client Not Available</td>
                                    <td></td>
                                    <td class="w-25"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <div id="schedule-report" class="card mt-3">
                        <div class="card-header text-center bg-darkblue text-white h5 font-weight-bold">
                            2020年 9月 16日 - 2020年 9月 17日
                        </div>
                        <div class="card-body p-0 m-0 b-0">
                            <div class="table-responsive">
                                <table class="table esi-table table-bordered table-striped  ">
                                    <thead>
                                        <td>I.D.</td>
                                        <td>Date</td>
                                        <td>Time</td>
                                        <td>Status</td>
                                        <td>Shift</td>
                                        <td>Agent</td>
                                        <td>Tutor</td>
                                        <td>Member</td>
                                    </thead>

                                    <tr>
                                        <td>809472</td>
                                        <td>09/17/2020</td>
                                        <td>24:30</td>
                                        <td>Client Reserved</td>
                                        <td>25 mins</td>
                                        <td>mytutor-4C</td>
                                        <td>Pam</td>
                                        <td>kakiuchi, IZUMI</td>
                                    </tr>

                                    <tr>
                                        <td>809473</td>
                                        <td>09/17/2020</td>
                                        <td>24:30</td>
                                        <td>Client Reserved</td>
                                        <td>25 mins</td>
                                        <td>mytutor-4C</td>
                                        <td>Pam</td>
                                        <td>kakiuchi, IZUMI</td>
                                    </tr>

                                    <tr>
                                        <td>809472</td>
                                        <td>09/17/2020</td>
                                        <td>24:30</td>
                                        <td>Client Reserved</td>
                                        <td>25 mins</td>
                                        <td>mytutor-4C</td>
                                        <td>Pam</td>
                                        <td>kakiuchi, IZUMI</td>
                                    </tr>

                                    <tr>
                                        <td>809472</td>
                                        <td>09/17/2020</td>
                                        <td>24:30</td>
                                        <td>Client Reserved</td>
                                        <td>25 mins</td>
                                        <td>mytutor-4C</td>
                                        <td>Pam</td>
                                        <td>kakiuchi, IZUMI</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection
