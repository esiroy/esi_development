<div class="table-responsive">
    <table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable dataTable no-footer">
        <thead>
            <tr>
                <th class="small text-center">&nbsp;</th>
                <th class="small text-center">ID</th>
                <th class="small text-center">Name</th>
                <th class="small text-center">Nickname</th>
                <th class="small text-center">Attribute</th>
                <th class="small text-center">Agent</th>
                <th class="small text-center">Email</th>
                <th class="small text-center">Skype /<br> Zoom</th>
                <th class="small text-center">Credit</th>
                <th class="small text-center">Class</th>
                <th class="small text-center">Main<br>Tutor</th>
                <th class="small text-center">History</th>
                <th class="small text-center">Report<br>Card</th>
                <th class="small text-center">Writing<br>Report</th>
                <th class="small text-center" style="width:90px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
            <tr>
                <td class="small"></td>
                <td class="small">{{$member->id}}</td>
                <td class="small">{{ucfirst($member->lastname)}},<br>  {{ucfirst($member->firstname)}}</td>
                <td class="small">{{$member->nickname}}</td>
                <td class="small">{{$member->attribute }}</td>
                <td class="small">{{$member->firstname }}</td>
                <td class="small">{{$member->email }}</td>
                <td class="small">
                    {{ $member->communication_app }}: <br> 
                    @if (strtolower($member->communication_app) == 'skype') {{ $member->skype_account }} @endif
                    @if (strtolower($member->communication_app) == 'zoom') {{ $member->zoom_account }} @endif
                </td>
                <td class="small">                
                    @php 
                        $agentTransaction = new App\Models\AgentTransaction();
                        $credits = $agentTransaction->getCredits($member->id)
                    @endphp
                    {{ number_format($credits, 2) }}
                </td>
                <td class="small text-center">
                    <a href="/admin/member/schedulelist/{{$member->id}}"><img src="{{ url('images/iClass.jpg')}}"></a>
                </td>

                <td class="small">該当なし</td>
                <td class="small text-center">
                    <a href="/admin/member/paymenthistory/{{$member->id}}"><img src="{{ url('images/iHistory.jpg')}}"></a>
                </td>
                <td class="small text-center">
                    <a href="/admin/reportcardlist/{{$member->id}}"><img src="{{ url('images/iReportCard.jpg')}}"></a>
                </td>

                <td class="toggleWriteReport small text-center">
                    <div id="monthly_report_card" class="text-center">
                        <a href="/admin/reportcarddatelist/{{$member->id}}" alt="List of Monthly Report Card" title="List of Monthly Report Card">
                            <img src="{{ url('images/iMonthlyRC.jpg')}}">
                        </a>
                    </div>
                    <!--@done: hide will be hidden on load, and will be displayed once it is hovered -->
                    @can('tutor_lesson_scheduler_access')                    
                    <div class="hide" style="background-color:#fff">
                        <a href="/admin/reportcarddate/{{$member->id}}" class="small red">Write Grade</a>
                    </div>
                    @endcan
                </td>


                <td class="small text-center">

                    @can('member_view')
                        <div class="action">
                            <a href="{{ route('admin.member.show', $member->id) }}" class="red">View</a>
                             @can('member_edit')
                                <span class="separator">|</span>
                             @endcan
                        </div>
                    @endcan

                    @can('member_edit')
                    <div class="action">
                         <a href="{{ route('admin.member.account', $member->id) }}" class="red">Account</a>
                        <span class="separator">|</span>
                    </div>
                    @endcan

                    @can('member_edit')
                    <div class="action">
                        <a href="{{ route('admin.member.edit', $member->id) }}" class="red">Edit</a>
                        <span class="separator">|</span>
                    </div>
                    @endcan

                    @can('member_delete')
                    <div class="action">
                        <a href="admin/member/{{$member->id}}" class="red" onclick="event.preventDefault();document.getElementById('delete_member_{{$member->id}}').submit();">Delete</a>
                        <span class="separator">|</span>
                    </div>

                    <form id="delete_member_{{$member->id}}" action="member/{{$member->id}}" method="POST" onsubmit="return confirm('are you sure you want to delete?');">
                        <input type="hidden" name="_method" value="delete" />
                        @csrf
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="float-right mt-4">
        <ul class="pagination pagination-sm">
            <small class="mr-4 pt-2">
                Page :</small>
            {{ $members->appends(request()->query())->links() }}
        </ul>
    </div>    

</div>