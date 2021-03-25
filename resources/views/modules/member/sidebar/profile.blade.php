
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
                <span>
                    @php
                    $transaction = new \App\Models\AgentTransaction();
                    echo $transaction->getCredits(Auth::user()->id);
                    @endphp
                </span>

                @elseif($memberInfo->membership == 'Monthly')

                <span class="text-secondary" title="point">Class: 月額会員対象</span>


                <span>毎月 {{ $memberInfo->getLessonLimit() }} 回クラス (あと　残り {{ $memberInfo->getMonthlyLessonsLeft() }} 回)</span>


                @else
                <div class="text-secondary" title="point"> 現在の残ポイント: ポイント会員対象 :
                    @php
                    $transaction = new \App\Models\AgentTransaction();
                    echo $transaction->getCredits(Auth::user()->id);
                    @endphp
                </div>

                @endif
            </div>



            <div class="col-md-12">
                <div class="text-dark">

                </div>
            </div>

            <div class="col-md-12">
                <div class="text-secondary">Lecturer</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">                 
                    {{ "担任講師は " . $member->getTutorName() ." 先生です " }}               
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-secondary">Skype ID:</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">                 
                    {{ "スカイプ名 ". $member->getSkype() }}                     
                </div>
            </div>
        </div>
    </div>


