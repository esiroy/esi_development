
    <div class="profile blueBox pt-0 px-0">
        <div class="col-md-12 bg-blue text-white pt-1 pb-1 text-center">
            <img src="{{ url('images/userMale.png') }}" align="absmiddle"> マイページ
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
                    <div class="text-secondary pt-1">Skype ID:</div>
                </div>
                <div class="col-md-12">
                    <div class="text-dark">                 
                        {{ "スカイプ名 (". $memberInfo->getSkype() .")"}}
                    </div>
                </div>
            @elseif (strtolower($memberInfo->communication_app) == "zoom")
                <div class="col-md-12">
                    <div class="text-secondary pt-1">Zoom ID:</div>
                </div>
                <div class="col-md-12">
                    <div class="text-dark pt-1">                 
                        {{  $memberInfo->getZoom() }}
                    </div>
                </div>
            @endif


        </div>
    </div>

