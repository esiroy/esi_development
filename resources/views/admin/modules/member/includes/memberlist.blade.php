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
                <th class="small text-center">Credit Expiration</th>
                <!--<th class="small text-center">Skype /<br> Zoom</th>-->
                <th class="small text-center">Credit</th>
                <th class="small text-center">Class</th>
                <th class="small text-center">Main<br>Tutor</th>
                <th class="small text-center">History</th>
                <th class="small text-center">Report<br>Card</th>
                <th class="small text-center">Writing<br>Report</th>
                <th class="small text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
            <tr>
              
                <td class="small"></td>
                <td class="small">{{$member->user_id}}</td>

                <td class="small" id="member-fullname" style="width:30px"> 

                    {{ucfirst($member->lastname) ?? "-" }},<br>  {{ucfirst($member->firstname) ?? "-" }} 
                    @if($member->is_activated)
                        <!--<img src="{{ url('images/iInactive.png')}}"></a>-->
                        <span style="color:green"><i class="fas fa-check-circle"></i></div>
                    @endif
                </td>

                <td class="small">{{$member->nickname ?? "-" }}</td>

                <td class="small">{{$member->attribute ?? "-" }}</td>

                <td class="small">
                    @php 
                        $agentObj = new App\Models\Agent();
                        $agentInfo = $agentObj->where('user_id', $member->agent_id)->first();
                    @endphp                
                    {{ $agentInfo->user->firstname ?? "" }} {{ $agentInfo->user->lastname ?? "" }}
                </td>

                <td id="member-emailaddress" class="small" style="width:60px">
                    {{$member->user->email ?? "-" }}
                </td>

                
                <td class="small">
                    @if (isset($member->credits_expiration))                    
                        <div>{{ date('m/d/y', strtotime($member->credits_expiration)) }}</div>
                        <div>{{ date('h:i:s a', strtotime($member->credits_expiration)) }}</div>                    
                    @endif
                </td>

                <!--communcation app
                <td class="small">
                    @if (isset($member->communication_app))
                        {{ $member->communication_app }} : {!! "<BR>" !!}
                    @endif

                    @if (strtolower($member->communication_app) == 'skype') {{ $member->skype_account }} @endif
                    @if (strtolower($member->communication_app) == 'zoom') {{ $member->zoom_account }} @endif
                </td>
                -->
                
                <td class="small">
                    @php 
                        $agentTransaction = new App\Models\AgentTransaction();
                        $credits = $agentTransaction->getCredits($member->user_id)
                    @endphp
                    {{ number_format($credits, 2) }}
                </td>
                <td class="small text-center">
                    <a href="/admin/member/schedulelist/{{$member->user_id}}"><img src="{{ url('images/iClass.jpg')}}"></a>
                </td>

                <td class="small">
                    <!-- Main Tutor-->
                    @php 
                        $tutorObj = new App\Models\Tutor();
                        $tutorInfo = $tutorObj->where('user_id', $member->tutor_id)->first();                     
                    @endphp                    

                   {{ $tutorInfo->user->firstname  ?? ' - ' }}

                </td>


                <td class="small text-center">
                    <a href="/admin/member/paymenthistory/{{$member->user_id}}"><img src="{{ url('images/iHistory.jpg')}}"></a>
                </td>
                <td class="small text-center">
                    <a href="/admin/reportcardlist/{{$member->user_id}}"><img src="{{ url('images/iReportCard.jpg')}}"></a>
                </td>

                <td class="toggleWriteReport small text-center">
                    <div id="monthly_report_card" class="text-center">
                        <a href="/admin/reportcarddatelist/{{$member->user_id}}" alt="List of Monthly Report Card" title="List of Monthly Report Card">
                            <img src="{{ url('images/iMonthlyRC.jpg')}}">
                        </a>
                    </div>
                    <!--@done: hide will be hidden on load, and will be displayed once it is hovered -->
                    @can('tutor_lesson_scheduler_access')                 
                    <div class="hide" style="background-color:#fff">
                        <a href="/admin/reportcarddate/{{$member->user_id}}" class="small red">Write Grade</a>
                    </div>
                    @endcan
                </td>


                <td class="small text-center">

                 
                    @can('member_view')                        
                        <div class="action">
                            <a href="{{ route('admin.member.show', $member->user_id) }}" class="red">View</a>
                            @can('member_edit')
                                <span class="separator">|</span>
                            @endcan
                        </div>
                    
                    @endcan
                   

                    @can('member_edit')
                    <div class="action">
                         <a href="{{ route('admin.member.account', $member->user_id) }}" class="red">Account</a>
                        <span class="separator">|</span>
                    </div>
                    @endcan

                    @can('member_edit')
                    <div class="action">
                        <a href="{{ route('admin.member.edit', $member->user_id) }}" class="red">Edit</a>
                        <span class="separator">|</span>
                    </div>
                    @endcan

                    @can('member_delete')
            
                        <form id="delete_member_{{$member->user_id}}" action="member/{{$member->user_id}}" method="POST" onsubmit="return confirm('are you sure you want to delete?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" style="border:none; color: #c60000; background-color: transparent; font: normal 12px Arial" value="{{ trans('global.delete') }}">
                        </form>

                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="float-right mt-4">
        <ul class="pagination pagination-sm">
            <small class="mr-4 pt-2"> Page :  {{ $members->currentPage() }} </small>          
            {{ $members->appends(request()->query())->links() }}           
        </ul>
    </div>    
</div>