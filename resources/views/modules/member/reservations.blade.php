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
                <!--[start] sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div><!--[end sidebar]-->


                <!--content-->
                <div class="col-md-9">
                    <div id="member-lesson-schedules" class="card esi-card">
                        <div class="card-header esi-card-header">
                            レッスンの予約
                        </div>

                        <div class="card-body">

                            <div class="reservationTable">
                                <!--[start reservation table -->
                                <table width="100%" cellspacing="0" cellpadding="5" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="heading">担任講師による固定レッスン</th>
                                        </tr>
                                        <tr>
                                            <th>Day</th>
                                            <th>Time</th>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>
                            <!--[end reservation table] -->

                            <div id="member-scheduler" class="mt-3">
                                <p>
                                    固定レッスン以外はこちらから予約して下さい
                                    <img src="images/btnHand.jpg">
                                    <a href="memberschedule"><img src="images/btnRed4.gif"></a>
                                </p>
                                <p>30分前まで予約可能です</p>
                            </div>

                        </div>
                    </div>


                </div>
            </div>


        </div>


    </div>
</div>
@endsection
