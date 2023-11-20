@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/agent') }}">Agent</a>
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

        <div class="container bg-light">

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif


            <div id="member-transaction" class="card esi-card mt-4">
                <div class="card-header esi-card-header">
                    Payment History List
                </div>


                <div class="card-body esi-card">

                    <div class="member mt-3">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2">Name</div>
                                <div class="col-md-9">
                                    {{ $member->firstname  ?? ' - ' }}
                                </div>
                            </div>

                            <!--
                            <div class="row">
                                <div class="col-md-2">Agent</div>
                                <div class="col-md-9">
                                    {{ $agentInfo->user->firstname  ?? ' - ' }}
                                </div>
                            </div>
                            -->

                            <!--
                            <div class="row">
                                <div class="col-md-2">Tutor</div>
                                <div class="col-md-9">

                                    {{ $tutorInfo->user->japanese_firstname  ?? ' - ' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">Lesson Class</div>
                                <div class="col-md-9">
                                    毎月 {{$memberAttribute->lesson_limit ?? '0'}} 回クラス (あと　残り {{$memberAttribute->lesson_limit ?? '0'}}回)
                                </div>
                            </div>
                            -->
                        </div>
                    </div>

                    <div class="card-body esi-card-body">
                        <div class="table-responsive">
                            <table class="table table esi-table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Credits</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0
                                    @endphp
                                    
                                    @foreach($paymentHistory as $history)
                                    <tr>
                                        <td>                                           
                                            {{ date('F d, Y H:i', strtotime($history->created_at)) }}
                                        </td>
                                        <td>{{ $history->amount }}</td>

                                        <td class="text-center">

                                            <div style="width:80px; margin:auto">
                                                <div class="text-right">
                                                    ¥ {{ number_format($history->price, 2) }}
                                                </div>
                                            </div>

                                        </td>

                                        @php
                                        $total = $total + $history->price
                                        @endphp
                                    </tr>
                                    @endforeach


                                    <tr>
                                        <td></td>
                                        <td>Total</td>
                                        <td class="text-center">
                                        
                                            <div style="width:80px; margin:auto">
                                                <div class="text-right">
                                                    ¥ {{ number_format($total, 2) }}
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="float-right">
                            {{ $paymentHistory->links() ?? "" }}
                        </div>

                    </div>
                </div>



            </div>




        </div>


    </div>

</div>

@endsection
