@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">


    @include('admin.modules.member.includes.menu')

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
                    <form name="add_credit_transaction_form" method="POST" action="{{ route('admin.member.update',  [$member->user_id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-3">Member : </div>
                                    <div class="col-md-8"><strong>{{ $member->user->lastname }}, {{ $member->user->firstname }}</strong></div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Agent : </div>
                                    <div class="col-md-9">
                                        @php 
                                            $agent = new App\Models\Agent();
                                            $agentInfo = $agent->where('user_id', $member->agent_id)->first();                                          
                                        @endphp
                                        {!! $agentInfo->user->firstname ?? '' !!}                                         
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
                                        <input type="date" name="expiry_date" id="expiry_date" value="{{  date("Y-m-d") }}" class="form-control form-control-sm col-md-5" alt="expiry_date">
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

                                <!-- INFO AREA -->
                                <div class="row py-1">
                                    <div class="col-md-4">Credits : </div>
                                    <div class="col-md-8"><strong>{{ $credits }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Credits Expiration : </div>
                                    <div class="col-md-8">
                                        @if (isset($member->credits_expiration))
                                        <strong>
                                            {{ date('m/d/y h:i:s a', strtotime($member->credits_expiration)) }}
                                        </strong>
                                        @endif
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Latest Purchase Date : </div>
                                    <div class="col-md-8">
                                        <strong>                            
                                            @if (isset($latestDateOfPurchase))                
                                                {{ date('m/d/y h:i:s a', strtotime($latestDateOfPurchase)) }}
                                            @else 
                                                {{ "-" }}
                                            @endif
                                        </strong>
                                    </div>
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
                    <table width="100%" id="agentTableList" class="tablesorter  table-striped" cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <th>Transaction Date</th>
                                <th>Transaction</th>
                                <th>Lesson Date</th>
                                <th>Lesson Status</th>
                                <th>Name</th>
                                <th>Points</th>
                                <th>Original Credit Expiration Date</th>
                                <th>Remarks</th>
                            </tr>

                            @foreach($transactions as $transaction)
                            @php 
                                $scheduleItem = \App\Models\ScheduleItem::where('id', $transaction->schedule_item_id)->first();
                            @endphp
                            <tr>
                                <td class="small">
                                    {{ date('F d, Y', strtotime($transaction->created_at)) }} 
                                    <br/>
                                    {{ date('h:i:s a', strtotime($transaction->created_at)) }}                                    
                                </td>

                                <td class="small">
                                    {{ str_replace("_", " ", ucwords(strtolower($transaction->transaction_type))) }}
                                </td>


                                <td class="lesson_date small">
                                    @if (isset($scheduleItem->lesson_time)) 
                                        {{ date('F d, Y', strtotime($scheduleItem->lesson_time )) }} 
                                        <br/>
                                        {{ date('h:i:s a', strtotime($scheduleItem->lesson_time )) }}
                                    @endif
                                </td>


                                <td class="lesson_status small">
                                    @if (isset($scheduleItem->schedule_status)) 
                                        @if ($scheduleItem->schedule_status == "CLIENT_RESERVED_B") 
                                            <span class="text-danger">{{ formatStatus($scheduleItem->schedule_status) ?? '-' }}</span>
                                        @else                                         
                                            {{ formatStatus($scheduleItem->schedule_status) ?? '-' }}
                                        @endif
                                    @else 
                                        {{ "-" }}
                                    @endif                                    
                                </td>

                                <td class="small">
                                    <!-- @note: get member name -->
                                    @php 
                                        $user = \App\Models\User::where('id', $transaction->created_by_id)->first();
                                    @endphp

                                    {{ $user->firstname ?? "-"  }}  {{ $user->lastname ?? ""  }}
                                </td>


                                <td class="small">
                                    <!-- points -->
                                    @if ($transaction->transaction_type == "AGENT_SUBTRACT" || $transaction->transaction_type == "LESSON" || $transaction->transaction_type == "EXPIRED" )
                                        {{ "-" }} {{ $transaction->amount }}
                                    @elseif ($transaction->transaction_type ==  "MANUAL_ADD" || $transaction->transaction_type == "CANCEL_LESSON" || $transaction->transaction_type == "DISTRIBUTE" || $transaction->transaction_type == "FREE_CREDITS")
                                        {{ "+" }} {{ $transaction->amount }}
                                    @else 
                                        {{ " " }}
                                    @endif
                                </td>


                                <td>
                                    <!-- original credit expiration -->
                                    @if (isset($transaction->old_credits_expiration))
                                        {{ date('m/d/y h:i:s a', strtotime($transaction->old_credits_expiration)) }}
                                    @endif
                                </td>


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
                                <td>
                                    {{ date('F d, Y h:i:s a', strtotime($history->created_at)) }}
                                </td>                         
                                <td>{{ $history->amount }}</td>
                                <th>
                                    @php 
                                        $shift = \App\Models\Shift::where('id', $history->lesson_shift_id)->first();
                                    @endphp

                                    {{ $shift->name ?? "-"}}
                                </th>
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

@section('scripts')
@parent
<script type="text/javascript">
    function checkSelected() {

        $('#expiry_date').prop('disabled', true);

        let type = $("#transaction_type option:selected").val()

        if (type !== 'CREDITS_EXPIRATION' || type == null) {
            $('#expiry_date').prop('disabled', true);
            $('#credits').prop('disabled', false);
            $('#amount').prop('disabled', false);

        } else {

            $('#credits').prop('disabled', true);
            $('#amount').prop('disabled', true);
            $('#expiry_date').prop('disabled', false);


        }
    }

    window.addEventListener('load', function() {
        checkSelected();
    });

</script>
@endsection
