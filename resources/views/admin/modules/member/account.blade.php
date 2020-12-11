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

            <div id="member-details" class="card esi-card">
                <div class="card-header esi-card-header">
                    Member Details
                </div>
                <div class="card-body esi-card-body">
                    <form name="add_credit_transaction_form" method="POST" action="{{ route('admin.member.update',  [$member->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-3">Member : </div>
                                    <div class="col-md-8"><strong>{{ $member->user->last_name }}, {{ $member->user->first_name }}</strong></div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Agent : </div>
                                    <div class="col-md-9">
                                        {{ $member->agent->name_en ?? " ~ " }}
                                    </div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Transaction : </div>
                                    <div class="col-md-9">
                                        <select name="transaction_type" id="transaction_type" class="form-control form-control-sm " onchange="checkSelected()" required>
                                            <option value="">-- Select Transaction Type --</option>
                                            <option value="DISTRIBUTE">Distribute</option>
                                            <option value="AGENT_SUBTRACT">Subtract (Agent)</option>
                                            <option value="CREDITS_EXPIRATION">Credits Expiration</option>
                                            <option value="FREE_CREDITS">Free Credits</option>
                                            <option value="MANUAL_ADD">Manual Add</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Credits : </div>
                                    <div class="col-md-9">
                                        <input type="text" size="4" name="credits" id="credits" class="form-control form-control-sm col-md-3" alt="credits">
                                    </div>
                                </div>


                                <div class="row py-2">
                                    <div class="col-md-3">Amount : </div>
                                    <div class="col-md-9">
                                        <input type="text" size="4" name="amount" id="amount" class="form-control form-control-sm col-md-3" alt="amount">
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

                                <div class="row py-2">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        <input type="submit" class="btn btn-primary btn-sm" value="Add Transaction">
                                    </div>
                                </div>

                            </div>

                            <!--[start right column]-->
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-4">Credits : </div>
                                    <div class="col-md-8"><strong>{{ $member->credits }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Credits Expiration : </div>
                                    <div class="col-md-8"><strong>{{ $member->credits_expiration }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Latest Purchase Date : </div>
                                    <div class="col-md-8"><strong>{{ $member->latest_purchase_date }}</strong></div>
                                </div>
                            </div>
                            <!--[end right column]-->




                        </div>
                        <!--[end] first row-->


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
                                <td>{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                                <td>{{ $transaction->credits }}</td>
                                <td>{{ $transaction->original_credit_expiration_date }}</td>
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
                                <th>Lesson Time</th>
                                <th>Amount</th>
                            </tr>

                            @foreach($purchaseHistory as $history)
                            <tr>
                                <td>{{ $history->created_at }}</td>
                                <td>{{ $history->credits }}</td>
                                <td>{{ $history->lesson_time_duration }}</td>
                                <td>¥ {{ $history->amount }}</td>
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

@section('scripts')
@parent
<script type="text/javascript">
    function checkSelected() {

        $('#expiry_date').prop('disabled', true);

        let type = $("#transaction_type option:selected").val()

        if (type !== 'CREDITS_EXPIRATION' || type == null) {
            $('#expiry_date').prop('disabled', true);
        } else {
            $('#expiry_date').prop('disabled', false);
        }
    }

    window.addEventListener('load', function() {
        checkSelected();
    });

</script>
@endsection
