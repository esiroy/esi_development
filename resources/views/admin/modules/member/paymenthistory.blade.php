@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/member') }}">Member</a>
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


                <div class="card-body esi-card-body">


                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="row py-1">
                                <div class="col-md-2">Member : </div>
                                <div class="col-md-8">{{ $member->user->last_name }}, {{ $member->user->first_name }}</div>
                            </div>
                            <div class="row py-1">
                                <div class="col-md-2">Agent : </div>
                                <div class="col-md-9">
                                    {{ $member->agent->name_en ?? " ~ " }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <table width="100%" id="agentTableList" class="tablesorter" cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <th>Date</th>
                                <th>Credits</th>
                                <th>Amount</th>
                            </tr>

                            @php 
                            $total = 0 
                            @endphp

                            @foreach($purchaseHistory as $history)
                            <tr>
                                <td>{{ $history->created_at }}</td>
                                <td>{{ $history->credits }}</td>
                                <td>¥ {{ $history->amount }}</td>

                                @php 
                                $total = $total + $history->amount 
                                @endphp
                            </tr>
                            @endforeach


                            <tr>
                                <td></td>
                                <td>Total</td>
                                <td>{{ $total }}</td>
                            </tr>
                    
                        </tbody>
                    </table>

                    
                </div>
            </div>




        </div>


    </div>

</div>

@endsection
