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
                <li class="breadcrumb-item active" aria-current="page">Agent</li>
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

            <div id="member-details" class="card esi-card">
                <div class="card-header esi-card-header">
                    Member Details
                </div>
                <div class="card-body esi-card-body">
                    <form name="add_credit_transaction_form" method="POST" action="{{ route('admin.agent.update',  [$agent->user_id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-3">Agent : </div>
                                    <div class="col-md-8"><strong>{{ $agent->user->firstname }}</strong></div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Transaction : </div>
                                    <div class="col-md-9">
                                        <select name="transaction_type" id="transaction_type" class="form-control form-control-sm " onchange="checkSelected()" required>
                                            <option value="">-- Select Transaction Type --</option>
                                            <option value="ADD">ADD</option>
                                            <option value="AGENT_SUBTRACT">Subtract</option>                                            
                                            <option value="MANUAL_ADD">Manual Add</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Credits : </div>
                                    <div class="col-md-9">
                                        <input type="number" size="4" name="credits" id="credits" class="form-control form-control-sm col-md-3" alt="credits" required>
                                    </div>
                                </div>


                                <div class="row py-2">
                                    <div class="col-md-3">Amount : </div>
                                    <div class="col-md-9">
                                        <input type="number" size="4" name="amount" id="amount" class="form-control form-control-sm col-md-3" alt="amount" required>
                                    </div>
                                </div>

                                <div class="row py-2">
                                    <div class="col-md-3">Expiry Date : </div>
                                    <div class="col-md-9">
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control form-control-sm col-md-5" alt="expiry_date">
                                    </div>
                                </div>

                                <div class="row py-2">
                                    <div class="col-md-3">Remarks : </div>
                                    <div class="col-md-9">
                                        <textarea name="remarks" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>

                            </div>

                            <!--[start right column]-->
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-4">Credits : </div>
                                    <div class="col-md-8"><strong>{{ $credits }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Credits Expiration : </div>
                                    <div class="col-md-8"><strong>{{ $agent->credits_expiration }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Latest Purchase Date : </div>
                                    <div class="col-md-8"><strong>{{ $latestDateOfPurchase }}</strong></div>
                                </div>
                            </div>
                            <!--[end right column]-->
                        </div>
                        <!--[end] first row-->

                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary btn-sm" value="Add Transaction">
                            </div>
                        </div>
                    </form>
                </div>
                <!--[end] card body-->

            </div>


            <div id="member-transaction" class="card esi-card mt-4">
                <div class="card-header esi-card-header">
                    Member Transaction
                </div>
                <div class="card-body esi-card-body">
                    <table width="100%" id="agentTableList" class="tablesorter" cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <th>Date</th>
                                <th>Transaction</th>
                                <th>Name</th>
                                <th>Points</th>
                                <th>Original Credit Expiration Date</th>
                                <th>Remarks</th>
                            </tr>

                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at }}</td>
                                <td>{{ $transaction->transaction_type }}</td>
                                <td>{{ $transaction->agent_id  }}</td>
                                <td >
                                    @if ($transaction->transaction_type == "AGENT_SUBTRACT")
                                        {{ "-" }}
                                    @endif

                                    {{ $transaction->amount }}
                                </td>
                                <td>{{ $transaction->credits_expiration }}</td>
                                <td>{{ $transaction->remarks }}</td>
                                
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>


            <div id="member-transaction" class="card esi-card mt-4">
                <div class="card-header esi-card-header">
                    Point Purchase History
                </div>
                <div class="card-body esi-card-body">
                    <table width="100%" id="agentTableList" class="tablesorter" cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <th>Date</th>
                                <th>Credits</th>
                                <th>Amount</th>
                            </tr>

                            @foreach($purchaseHistory as $history)
                            <tr>
                                <td>{{ $history->created_at }}</td>                         
                                <td>{{ $history->amount }}</td>
                                <td>¥ {{ $history->price ?? "0" }}</td>                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>




        </div>


    </div>

</div>

@endsection
