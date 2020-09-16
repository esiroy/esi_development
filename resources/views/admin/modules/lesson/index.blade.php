@extends('layouts.admin')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Page</li>
            </ol>
        </nav>



        <div class="container">

            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Lesson
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

                    <!--schedules
                    <div id="schedules">
                        <div class="card">
                            <div class="card-header text-center">
                                ( Date Here ) 2020年 9月 7日
                            </div>
                            <div class="card-body scrollable-x">
                                <table class="table table-bordered">
                                    <tr>
                                        <td></td>
                                        <td>
                                            10:30
                                        </td>
                                        <td>
                                            11:30
                                            12:00
                                        </td>
                                        <td>12:00</td>
                                        <td>12:30</td>
                                        <td>1:00</td>
                                        <td>1:30</td>
                                        <td>2:00</td>
                                        <td>2:30</td>
                                        <td>3:00</td>
                                        <td>3:30</td>
                                        <td>4:00</td>
                                        <td>4:30</td>
                                        <td>5:00</td>
                                        <td>5:30</td>
                                        <td>6:00</td>
                                        <td>6:30</td>
                                        <td>7:00</td>
                                        <td>7:30</td>
                                        <td>8:00</td>
                                        <td>8:30</td>
                                        <td>9:00</td>
                                        <td>9:30</td>
                                        <td>10:00</td>
                                        <td>10:30</td>
                                        <td>11:00</td>
                                        <td>11:30</td>
                                    </tr>
                                    <tr>
                                        <td>Tutor 1</td>
                                        <td> sched</td>
                                    </tr>
                                </table>


                            </div>
                        </div>
                    </div>
                    -->

                    <lesson-scheduler-component/>

                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection
