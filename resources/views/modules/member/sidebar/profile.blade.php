
    <div class="profile blueBox pt-0 px-0">
        <div class="col-md-12 bg-primary text-white pt-1 pb-1">
            マイページ
        </div>
        <div class="profile-image text-center mt-2">
            <img src="/images/samplePictureNoImage.jpg" width="145" height="145">
        </div>
        <div class="profile_settings text-center">
            <a href="{{ url('settings') }}" class="small">[click here]</a>
        </div>

        <div id="userDetails" class="row mx-2">
            <div class="col-md-12">
                <div class="text-secondary">Name:</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">
                    {{ Auth::user()->id }}
                    {{ ucfirst(Auth::user()->first_name) }}, {{ ucfirst(Auth::user()->last_name) }}
                </div>
            </div>


            <div class="col-md-12">
                <div class="text-secondary" title="point"> 現在の残ポイント: ポイント会員対象</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">
                    @if (isset(Auth::user()->memberInfo->credits))
                    {{ ucfirst(Auth::user()->memberInfo->credits) }}
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="text-secondary">Lecturer</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">
                    @if (isset($data['lecturer']))
                    担任講師は {{ $data['lecturer'] }} 先生です
                    @endif

                </div>
            </div>
            <div class="col-md-12">
                <div class="text-secondary">Skype ID:</div>
            </div>
            <div class="col-md-12">
                <div class="text-dark">
                    @if (isset($data['skypeID']))
                    スカイプ名 {{ $data['skypeID'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>


