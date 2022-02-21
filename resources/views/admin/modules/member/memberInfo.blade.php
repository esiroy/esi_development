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

        <div class="container">
            <!--Member List -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                   Member Details 
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <table border="0" cellspacing="9" cellpadding="3" align="center" class="tblRegister" width="100%">
                                <tbody>
                                    <tr>
                                        <th colspan="13">Personal Information</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td width="200px">Agent</td>
                                        <td>:</td>
                                        <td>
                                            {{ $agentInfo->user->firstname ?? " - " }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td>Japanese LastName</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->japanese_lastname ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Japanese Firstname</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->japanese_firstname ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Last Name</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->lastname ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>First Name</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user->firstname ?? "-"}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Attribute</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $memberInfo->attribute ?? "-"}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Nickname</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $memberInfo->nickname ?? "-"}}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->gender ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>
                                            @if (strtolower($memberInfo->communication_app) == "skype") 
                                                @php $color = "red" @endphp
                                            @else 
                                                @php $color = "orange" @endphp                                                
                                            @endif
                                            <span style="font-size:20px; color: {{ $color }}"><strong>{{ $memberInfo->communication_app ?? "-" }}</strong></span>
                                        </td>
                                        <td>:</td>
                                        
                                        <td>
                                            @if (strtolower($memberInfo->communication_app) == "skype") 
                                                <span style="font-size:20px; color: {{ $color }}"><strong>{{ $memberInfo->skype_account ?? "-" }}</strong></span>
                                            @else
                                                <span style="font-size:20px; color: {{ $color }}"><strong>{{ $memberInfo->zoom_account ?? "-" }}</strong></span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Member Id</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->user_id ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Birthday</td>
                                        <td>:</td>
                                        <td>
                                            @if (isset($memberInfo->birthday))
                                            {{  date('F d, Y', strtotime($memberInfo->birthday)) ?? "-" }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td>Age</td>
                                        <td>:</td>
                                        <td>{{ $memberInfo->age ?? "-" }}</td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td valign="top">Hobby</td>
                                        <td valign="top">:</td>
                                        <td>{{ $memberInfo->hobby ?? "-" }} </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td colspan="9">
                                            {{ $memberInfo->english_level ?? "-" }}
                                        </td>
                                    </tr>



                                    <tr>
                                        <th colspan="13"> Recent Exam Score  </th>
                                    </tr>
                                     <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-12 pt-2 pb-2">Recent Exam Score </div>
                                            </div>

                                        </td>
                                        <td> : </td>
                                        <td >   
                                            <member-score-component 
                                                :memberinfo="{{ json_encode($memberInfo) }}" 
                                                api_token="{{ Auth::user()->api_token }}" 
                                                csrf_token="{{ csrf_token() }}"
                                            ></member-score-component>
                                        </td>                                                                                
                                    </tr>

                                    <tr>
                                        <th colspan="13">Purpose </th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>
                                            List of Purpose 
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <member-purpose-viewer-component
                                                :memberinfo="{{ json_encode($memberInfo) }}"                                                
                                                api_token="{{ Auth::user()->api_token }}" 
                                                csrf_token="{{ csrf_token() }}"                                             
                                             ></member-notes-component>

                                        </td>
                                    </tr>




                                   

                                    <tr>
                                        <th colspan="13">Lesson Details</th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Member Since</td>
                                        <td>:</td>
                                        <td colspan="9">
                                            {{  date('F d, Y', strtotime($memberInfo->member_since)) ?? "-" }}                                         
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Lesson Time</td>
                                        <td>:</td>
                                        <td colspan="9">25</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Main Tutor</td>
                                        <td>:</td>
                                        <td id="maintutorcontainer" colspan="9">
                                            @if (isset($tutorInfo->user->firstname))
                                                 {!! $tutorInfo->user->firstname !!}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="13">Current CEFR Member Level</th>
                                    </tr>

                                     <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td>
                                            {{ $currentMemberlevel->level ?? '-' }}

                                            {{ (isset($currentMemberlevel->description)) ? "(" . $currentMemberlevel->description .")" : ""  }}
                                        </td>
                                    </tr>                                    


                                    <tr>
                                        <th colspan="13">Latest Report Card</th>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Subject</td>
                                        <td>:</td>
                                        <td>
                                            {{ $latestReportCard->lesson_subject ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Course</td>
                                        <td>:</td>
                                        <td colspan="10">
                                            {{ $latestReportCard->lesson_course ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Latest Material</td>
                                        <td>:</td>
                                        <td colspan="10">
                                            {{ $latestReportCard->lesson_material ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Level</td>
                                        <td>:</td>
                                        <td>
                                             {{ $latestReportCard->lesson_level ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Grade</td>
                                        <td>:</td>
                                        <td>
                                            @if(isset($latestReportCard->grade))
                                                {{  formatGrade($latestReportCard->grade) ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>

                                  <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Homework</td>
                                        <td>:</td>
                                        <td>
                                          

                                           <table class="table table-sm border">
                                                <tr class="">
                                                    <th class="bg-darkblue small text-white text-center font-weight-bold">
                                                        Members Home Work 
                                                    </th>
                                                </tr>                                                                    
                                                <tr>
                                                    <td class="px-2">  

                                                        @if (isset($homework))
                                                        <div class="small">
                                                            File: <a href="{{ url( Storage::url($homework->original) ) }}" 
                                                            download="{{ url( Storage::url($homework->original) ) }}" >{{ $homework->filename }}</a>
                                                        </div>

                                                            <div class="small">
                                                            Instruction : {{ $homework->instruction ?? '' }}
                                                        </div>
                                                        @else
                                                            <div class="text-center">
                                                                <span class="small text-secondary">No homework found!</span>
                                                            </div>
                                                        @endif

                                                    </td>
                                                </tr>
                                            </table>                                        


                                        </td>
                                    </tr>



                                                         





                                    <tr>
                                        <th colspan="13"> Recent Notes  </th>
                                    </tr>
                                     <tr valign="top">
                                         <td class="red">&nbsp;</td>                                        
                                        <td>
                                            <div class="row">
                                                <div class="col-12 pt-2 pb-2">Notes</div>
                                            </div>
                                        </td>
                                        <td> : </td>
                                        <td class="red">
                                             <member-notes-component
                                                :tutorinfo="{{ json_encode(Auth::user()) }}"
                                                :memberinfo="{{ json_encode($memberInfo) }}"                                                
                                                api_token="{{ Auth::user()->api_token }}" 
                                                csrf_token="{{ csrf_token() }}"                                             
                                             ></member-notes-component>
                                        </td>
                                    </tr>
                                    



                                    <tr>
                                        <th colspan="13">Latest Writing Report Card</th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Date</td>
                                        <td>:</td>
                                        <td>
                                            @if (isset($latestWritingReport->lesson_date))
                                                {{  date('F d, Y', strtotime($latestWritingReport->lesson_date)) ?? "-" }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Subject</td>
                                        <td>:</td>
                                        <td> {{ $latestWritingReport->lesson_subject ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Course</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->lesson_course ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Latest Material</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->lesson_material ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Grade</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->grade ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Comment</td>
                                        <td>:</td>
                                        <td>{{ $latestWritingReport->comment ?? '-' }}</td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Uploaded File</td>
                                        <td>:</td>
                                        <td>
                                            @if(isset($latestWritingReport->file_path))
                                                <a href="{{ Storage::url('uploads/report_files/'. basename($latestWritingReport->file_path) ) }}" download> {{ $latestWritingReport->file_name ?? '-' }} </a>
                                            @else 
                                                - 
                                            @endif
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="examHistory">
    @include('modules.member.popup.showAllMemberExamScoreModal')  
</div>

@endsection


@section('scripts')
    @parent

    <script>
    window.addEventListener('load', function() 
    {           
        $('#viewAllExamScores').on('click', function() 
        {            
            getMemberExamScorePage(1);
        });

        $(document).on('click', '#examHistory .pagination a', function(event) {                    
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];                   
            let memberID = $('#memberExamUserID').val();
            getMemberExamScorePage(page, memberID);                    
            return false;
        });

    });

    function getMemberExamScorePage(page)
    {
        $.ajax({
            type: 'POST',
            url: '/api/getAllMemberExamScore?page='+ page +'&api_token=' + api_token,
            data: {
                limit: 5,
                memberID: "{{ Request::segment(3)  }}",
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() 
            {
               $('#showAllMemberExamScoreModal').css('visibility', 'hidden');
               $('#loadingModal').modal('show');
            },            
            complete: function(){
                $('#loadingModal').modal('hide');
                
                $('#showAllMemberExamScoreModal').css('visibility', 'visible');                
            },                                        
            success:function(data)
            {

                if (data.success) {
                    $('#showAllMemberExamScoreModal').modal('show');
                    $('#memberExamScores').html(data.scores); 
                }
                           
                return false;
            }
        });
    }


    </script>
@endsection