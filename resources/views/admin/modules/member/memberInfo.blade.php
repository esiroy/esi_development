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
                                            @if (strtolower($memberInfo->communication_app) == "mytutor") 
                                                <span style="font-size:20px; color: {{ $color }}"><strong> MyTutor (Web Chat) </strong></span>

                                            @elseif (strtolower($memberInfo->communication_app) == "skype") 
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
                                        <th colspan="13"> Attention Required For Teacher </th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td><strong>Attention</strong></td>
                                        <td>:</td>
                                        <td>

                                            <div style="max-height:350px; overflow-y:scroll">
                                            @foreach($lessonGoals as $key => $goals)
                                                @if(isset($goals->purpose))
                                                    @if (isset($goals->purposeDescription))
                                                        @if (strtolower($goals->purposeDescription) == "others")
                                                            {!! $goals->extra_detail ?? '' !!}
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                            </div>

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

                                            @if ($mergedAccount) 
                                                <member-score-component 
                                                    :memberinfo="{{ json_encode($mergedMemberInfo) }}" 
                                                     api_token="{{ Auth::user()->api_token }}"  
                                                    csrf_token="{{ csrf_token() }}"
                                                ></member-score-component>
                                            @else 
                                                <member-score-component 
                                                    :memberinfo="{{ json_encode($memberInfo) }}" 
                                                    api_token="{{ Auth::user()->api_token }}" 
                                                    csrf_token="{{ csrf_token() }}"
                                                ></member-score-component>
                                            @endif
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

                                            @if ($mergedAccount) 
                                                <member-purpose-viewer-component
                                                    :memberinfo="{{ json_encode($mergedMemberInfo) }}"                                                
                                                    api_token="{{ Auth::user()->api_token }}" 
                                                    csrf_token="{{ csrf_token() }}"                                             
                                                ></member-notes-component>
                                            @else 
                                                <member-purpose-viewer-component
                                                    :memberinfo="{{ json_encode($memberInfo) }}"                                                
                                                    api_token="{{ Auth::user()->api_token }}" 
                                                    csrf_token="{{ csrf_token() }}"                                             
                                                ></member-notes-component>
                                            @endif


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
                                        <td>
                                           <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0209160659.html','Level',900,820);">Level</a> :
                                        </td>
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
                                            
                                            <!-- added for incomplete lesson history -->                                            
                                            @if (isset($recentLessonHistory) && $recentLessonHistory->status == "INCOMPLETE") 
                                                <span class="text-danger font-weight-bold">
                                                / {{ $recentLessonHistory->status }} 
                                                </span>

                                                <span class="small ml-3">
                                                    Next Lesson : 
                                                </span>
                                                <span class="text-danger font-weight-bold">
                                                {{ "SLIDE "}} {{ $memberFeedback->next_lesson }}
                                                </span>
                                            @endif
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
                                                            Instruction : <span id="home-instruction">{!! $homework->instruction ?? '' !!}</span>
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

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td class="align-middle">Rating </td>
                                        <td class="align-middle">:</td>
                                        <td class="align-middle">

                                            <div class="rating">
                                                @if (isset($memberFeedbackDetails->value))
                                                    @for ($i = 5; $i >= 1; $i--)
                                                        @if ($i <= $memberFeedbackDetails->value )
                                                            <label class="highlighted" for="star{{ $i }}"></label>
                                                        @else 
                                                            <label for="star{{ $i }}"></label>
                                                        @endif
                                                    @endfor
                                                @else 
                                                     - 
                                                @endif
                                            </div>

                                        </td>
                                    </td>                                    

                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>
                                        <td>Feedback</td>
                                        <td>:</td>
                                        <td> {!! $memberFeedback->feedback ?? '-' !!} </td>
                                    </td>

                                    



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



                                    <tr>
                                        <td colspan="13">&nbsp;</th>
                                    </tr>

                                    <tr class="pt-4">
                                        <th colspan="13">Time Manager </th>
                                    </tr>
                                    <tr valign="top">
                                        <td class="red">&nbsp;</td>                                        
                                        <td>
                                            <div class="row">
                                                <div class="col-12 pt-2 pb-2">Time Manager </div>
                                            </div>
                                        </td>
                                        <td> &nbsp; </td>
                                        <td class="red">
                                        
                                            @if ($mergedType == 'secondary')
                                                <div class="card border-lightblue mt-1 mb-4">
                                                    <div class="card-header bg-darkblue text-white font-weight-bold">
                                                    Main Account {{ "1" . $mergedMemberInfo->user_id}} ({{$mergedMemberInfo->user_id}})
                                                    </div>
                                                    <div class="card-body p-0 m-0 b-0">
                                                        <table class="esi-table table table-bordered table-striped">
                                                            <thead>
                                                                    <td>Member ID</td>
                                                                    <td>Email</td>
                                                                    <!--
                                                                    <td>Action</td>                                                        
                                                                    -->
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td> 1{{ $mergedMemberInfo->user_id }}</td>
                                                                    <td>{{ $mergedMemberInfo->user->email ?? ''}}</td>

                                                                    <!--
                                                                    <td>
                                                                        <a href="#"><b-icon icon=" trash" aria-hidden="true"></b-icon></a>
                                                                    </td>
                                                                    -->
                                                                </tr>                        
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="card border-lightblue mt-1 mb-4">
                                                <div class="card-header bg-darkblue text-white font-weight-bold">
                                                    Time Manager - Member 1{{ $mergedMemberInfo->user_id }} ({{$mergedMemberInfo->user_id}})
                                                </div>
                                                <div class="card-body b-0">
                                                    <member-time-manager-viewer-component                                    
                                                        :memberinfo="{{ json_encode($mergedMemberInfo) }}"             
                                                        api_token="{{ Auth::user()->api_token }}"                                                         
                                                        csrf_token="{{ csrf_token() }}"
                                                    />
                                                </div>
                                            </div>


                                            @if ($mergedType == 'main')
                                                @if ( count($mergedAccounts) >= 1)
                                                    <div class="card border-lightblue mt-1 mb-4">
                                                        <div class="card-header bg-darkblue text-white font-weight-bold">
                                                            Merged Accounts
                                                        </div>
                                                        <div class="card-body p-0 m-0 b-0">
                                                            <table class="esi-table table table-bordered table-striped">
                                                                <thead>
                                                                        <td>Member ID</td>
                                                                        <td>Email</td>
                                                                        <!--<td>Action</td>                                                        -->
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($mergedAccounts as $mergedAccount)
                                                                    <tr>
                                                                        <td> 1{{ $mergedAccount->id }}</td>
                                                                        <td>{{ $mergedAccount->email ?? ''}}</td>
                                                                        <!--
                                                                        <td>
                                                                            <a href="#"><b-icon icon=" trash" aria-hidden="true"></b-icon></a>
                                                                        </td>-->
                                                                    </tr>    
                                                                    @endforeach                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                        </td>
                                          
                                    </tr>




                                    <tr>
                                        <td colspan="13">&nbsp;</th>
                                    </tr>

                                    <tr class="pt-4">
                                        <th colspan="13">Member Mini-Test</th>
                                    </tr>
                                    <tr valign="center">
                                        <td>&nbsp;</td>                                        
                                        <td>Results</td>
                                        <td>&nbsp;</td>
                                        <td class="py-3">

                                            <member-mini-test-viewer-component               
                                                    usertype="{{ Auth::user()->user_type }}"                     
                                                    :memberinfo="{{ json_encode($memberInfo) }}"             
                                                    api_token="{{ Auth::user()->api_token }}" 
                                                    csrf_token="{{ csrf_token() }}"
                                                />
                                                
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


@section ('styles')

<style>


.rating {
      display: inline-block;
      unicode-bidi: bidi-override;
      direction: rtl;
    }

.rating label {
    display: inline-block;
    padding: 3px;
    font-size: 20px;
    color: #ccc; /* Grayed out color for non-rated stars */
}

.rating label:before {
    content: "\2605";
}

.rating .highlighted {
    color: #f90; /* Highlight color for rated stars */
}


</style>

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