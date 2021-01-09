<div class="table-responsive">
    <table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable dataTable no-footer">
        <thead>
            <tr>
                <th class="small text-center">&nbsp;</th>
                <th class="small text-center">Agent Name</th>
                <th class="small text-center">ID</th>
                <th class="small text-center">Member<br />List</th>
                <th class="small text-center">First Date of<br />Purchase</th>
                <th class="small text-center">Point Purchase<br />History</th>
                <th class="small text-center">Point<br />Balance</th>
                <th class="small text-center">Expire<br />Data</th>
                <th class="small text-center">Purchase<br />Amount</th>
                <th class="small text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($agents))
            @foreach ($agents as $agent)
                
            <tr data-entry-id="{{ $agent->id }}">
                <td class="small text-center">&nbsp;</td>
                <td class="small text-center">{{$agent->user->firstname ?? "" }} {{ $agent->user->lastname ?? ""}}</td>
                <td class="small text-center">{{$agent->agent_id }}</td>

                <td class="small text-center">
                    <!-- @note: memberlist -->
                    <a href="/admin/agent/memberlist/{{$agent->user_id}}"><img src="/images/iMemberList.jpg"></a>
                </td>

                <td class="small text-center">
                    <!-- @note: date of first transcation -->
                    @php $agentTransaction = new App\Models\AgentTransaction(); @endphp                                       
                    <div class="dateFirstTrans">{{  date('F d, Y', strtotime($agentTransaction->getAgentFirstDateOfPurchase($agent->agent_id))) ?? "-" }}</div>
                    <div class="timeFirstTrans">{{  date('h:i:s a', strtotime($agentTransaction->getAgentFirstDateOfPurchase($agent->agent_id))) ?? "-" }}</div>
                </td>
                <td class="small text-center">
                    <a href="/admin/agent/paymenthistory/{{$agent->user_id}}"><img src="{{ url('images/iHistory.jpg')}}"></a>
                </td>
                <td class="small text-center">
                    @php 
                        $credits = $agentTransaction->getAgentCredits($agent->user_id)
                    @endphp
                    {{ $credits }}
                </td>
                <td class="small text-center">
                    @if (isset($agent->credits_expiration))
                        <div class="dateExpires">{{  date('F d, Y', strtotime($agent->credits_expiration)) ?? "-" }}</div>
                        <div class="timeExpires">{{  date('h:i:s a', strtotime($agent->credits_expiration)) ?? "-" }}</div>
                    @else 
                        {{ "-" }}
                    @endif                    
                </td>
                <td class="small text-center">
                    {{ number_format($agentTransaction->getAgentPurchasedAmount($agent->user_id), 2) }} 
                </td>

                <td class="small text-center">
                    <a href="{{ route('admin.agent.account', $agent->user_id) }}" class="btn btn-sm btn-info">Account</a>|
                    <a href="{{ route('admin.agent.edit', $agent->user_id) }}" class="btn btn-sm btn-info">Edit</a> |
                    <form action="{{ route('admin.agent.destroy', $agent->user_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
