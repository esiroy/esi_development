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
            <div class="card">
                <div class="card-header">
                    Report
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label for="inputDate" class="pr-3">Date:</label>
                            <input e="inputDate" id="inputDate" type="text" class="inputDate hasDatepicker form-control form-control-sm d-inline col-8" value="2020年 9月 7日">
                        </div>
                        <div class="col-2">
                            <select e="lessonshiftid" id="shiftValue" class="form-control form-control-sm">
                                <option value="4" selected="">25 mins</option>
                                <option value="5">40 mins</option>
                            </select>
                        </div>
                    </div>

                    <div class="legend bg-lightgray">
                        <table cellspacing="0" cellpadding="5">
                            <tbody>
                                <tr>
                                    <td>Legend</td>
                                    <td>:</td>

                                    <td><img src="{{ url('images/iNothing.gif') }}" alt="Nothing" title="Nothing" align="absmiddle"> Nothing</td>
                                    <td><img src="{{ url('images/iTutorScheduled.gif') }}" alt="Tutor Scheduled" title="Tutor Scheduled" align="absmiddle"> Tutor Scheduled</td>
                                    <td><img src="{{ url('images/iClientReserved.gif') }}" alt="Client Reserved" title="Client Reserved" align="absmiddle"> Client Reserved</td>
                                    <td><img src="{{ url('images/iSuppressed.gif') }}" alt="Suppressed Schedule" title="Suppressed Schedule" align="absmiddle"> Suppressed Schedule</td>
                                </tr>
                                <tr>

                                    <td></td>
                                    <td></td>
                                    <td><img src="{{ url('images/iCompleted.gif') }}" alt="Completed" title="Completed" align="absmiddle"> Completed</td>
                                    <td><img src="{{ url('images/iTutorCancelled.gif') }}" alt="Tutor Cancelled" title="Tutor Cancelled" align="absmiddle"> Tutor Cancelled</td>
                                    <td><img src="{{ url('images/iNotAvailable.gif') }}" alt="Not Available" title="Not Available" align="absmiddle"> Client Not Available</td>
                                    <td></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="card-text"></p>

                    <lesson-report-component />

                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection
