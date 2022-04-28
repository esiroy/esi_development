
    <div class="profile blueBox pt-0 px-0">
    
        <div class="text-center col-md-12 bg-blue text-white pt-1 pb-1 text-center">

            <!--
            <span id="linked-account" class="d-none float-left pt-1">
                <i class="fa fa-link" aria-hidden="true"></i>
            </span>
            -->

            <img src="{{ url('images/userMale.png') }}" align="absmiddle"> マイページ

            <span id="linked-account-help" class="pl-2 float-right pt-1">
                <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/','Merged Account',900,820);" class="text-white">
                    <i class="fa fa-question" aria-hidden="true"></i>
                </a>
            </span>


            <span class="pl-3 float-right">
                <member-account-merger-component 
                    :memberinfo="{{  json_encode(Auth::user()->memberInfo) }}" 
                    api_token="{{ Auth::user()->api_token }}" 
                    csrf_token="{{ csrf_token() }}">
                </member-account-merger-component>
            </span>


        </div>
        
        <div class="profile-image text-center mt-2">
            @php                
                //get photo
                $member = new \App\Models\Member;
                $memberInfo = $member->where('user_id', Auth::user()->id)->first();

                $userImageObj = new \App\Models\UserImage;
                $userImage = $userImageObj->getMemberPhoto($memberInfo); 
            @endphp

            @if ($userImage == null)
                <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" >
            @else 
                <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
            @endif
        </div>


        <div class="profile_settings text-center">
            <a href="{{ url('settings') }}" class="small text-danger">[click here]</a>
        </div>

        <div id="userDetails" class="row mx-2">

            <div class="col-md-12">
                <span class="text-secondary">Member ID:</span> 1{{ Auth::user()->id }}

                <span id="linked-account-text" class="d-none small">
                   (main account)
                </span>

            </div>

            <div id="merged-accounts">
                @if (!$mergedAccount) 
                    @php 
                        $mergedAccounts = Auth::user()->mergedAccounts 
                    @endphp

                    @if (count($mergedAccounts) >= 1) 
                        <div class="col-md-12">
                            <span class="text-secondary">Merged ID(s):</span> 
                            <span id="mergeAccountIDs">
                                @foreach($mergedAccounts as $mergedAccountIndex => $mergeAccount){{'1'.$mergeAccount->merged_member_id}}@if(($mergedAccountIndex + 1) < count($mergedAccounts)){{','}}@endif @endforeach
                            </span>
                        </div>
                    @else 

                        <div id="mergeAccountsContainer" class="d-none col-md-12" >
                            <span class="text-secondary">Merged ID(s):</span> 
                            <span id="mergeAccountIDs">

                            </span>
                        </div>                    

                    @endif

                @else 
                    <div class="col-md-12">
                        <span class="text-secondary">{{ "Main Account Member ID" }} :</span>
                        <span>{{ '1'. $mainAccount->id ?? '' }}</span>
                    </div>
                    <div class="col-md-12">
                        <span class="text-secondary">{{ "Main Account E-Mail" }} :</span>
                        <span>{{ $mainAccount->email ?? ''  }}</span>
                    </div>                
                @endif
            </div>

            <div class="col-md-12">
                <div class="text-secondary">Name:</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">                    
                    {{ ucfirst(Auth::user()->firstname ) }}, {{ ucfirst(Auth::user()->lastname) }}
                </div>
            </div>

            
            <div class="col-md-12">
                @if ($memberInfo->membership == 'Point Balance')

                    <!-- Current remaining points: For point members: -->
                    <span class="text-secondary" title="point"> 現在の残ポイント: ポイント会員対象 : </span>

                 
                        @if ($memberInfo->isMemberCreditExpired(Auth::user()->id)) 
                            <span class="text-danger">{{ "0" }}</span>
                        @else 
                            <span>
                                @php
                                $transaction = new \App\Models\AgentTransaction();
                                echo $transaction->getCredits(Auth::user()->id);
                                @endphp
                            </span>
                        @endif


                    <div class="pt-2">


                        <span class="text-secondary" title="expiry">有効期限: ポイント会員対象 : </span>
                        <span>
                            {{ date("Y年 m月 d日", strtotime($memberInfo->credits_expiration)) }}                    
                        </span>
                    </div>

                @elseif($memberInfo->membership == 'Monthly')

                    @php                    
                         $scheduleItemObj = new \App\Models\ScheduleItem();        
                    @endphp
                    <!--(test total reserved current month: ) {{ $scheduleItemObj->getTotalReservedForCurrentMonth($memberInfo->user_id) }}-->

                    <div class="text-secondary" title="lessonLimit">Class: 月額会員対象</div>
                    <span>毎月 {{ $memberInfo->getLessonLimit() }} 回クラス (あと　残り {{ $memberInfo->getMonthlyLessonsLeft() }} 回)</span>

                @else

                    <div class="point">

                        <div class="pt-1">
                            <span class="text-secondary" title="point"> 現在の残ポイント: ポイント会員対象 :</span>                            
                            <span>
                                    
                                @if ($memberInfo->isMemberCreditExpired(Auth::user()->id)) 
                                    <span class="text-danger">{{ "0" }}</span>
                                @else 
                                    @php
                                    $transaction = new \App\Models\AgentTransaction();
                                    echo $transaction->getCredits(Auth::user()->id);
                                    @endphp
                                @endif
                            </span>                            
                        </div>                       

                        <div class="pt-2">
                            <span class="text-secondary" title="expiry">有効期限: ポイント会員対象 : </span>
                            <span>
                                {{ date("Y年 m月 d日", strtotime($memberInfo->credits_expiration)) }}                    
                            </span>           
                        </div>             
                    </div>

                    <div class="monthly pt-2">
                        <div class="text-secondary" title="lessonLimit">Class: 月額会員対象</div>
                        <span>毎月 {{ $memberInfo->getLessonLimit() }} 回クラス (あと　残り {{ $memberInfo->getMonthlyLessonsLeft() }} 回)</span>                    
                    </div>

                @endif
            </div>

            <div class="col-md-12">
                <div class="text-dark">

                </div>
            </div>

            <div class="col-md-12">
                <div class="text-secondary pt-1">Lecturer:</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">                 
                    {{ "担任講師は " . $memberInfo->getTutorName() ." 先生です " }}               
                </div>
            </div>

            @if (strtolower($memberInfo->communication_app) == "skype")
                <div class="col-md-12">
                    <div class="text-secondary pt-1">
                        
                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">
                            Skype ID:
                        </a>
                        
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-dark">                 
                        {{ "スカイプ名 (". $memberInfo->getSkype() .")"}}
                    </div>
                </div>
            @elseif (strtolower($memberInfo->communication_app) == "zoom")
                <div class="col-md-12">
                    <div class="text-secondary pt-1">
                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">
                            Zoom ID:
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-dark pt-1">                 
                        {{  $memberInfo->getZoom() }}
                    </div>
                </div>
            @endif


        </div>
    </div>


