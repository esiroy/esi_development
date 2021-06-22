@extends('layouts.esi-public-activation')

@section('content')
<!-- note:

  MEMBER OR USER IS ACTIVATED!
 
 -->

 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 py-2">

            <div class="card">
                <div class="text-center">
                    <a href="{{ url('/') }}">
                    <img title="My Tutor" alt="My Tutor" src="{{ url('images/title_full.png') }}" style="vertical-align: middle;margin-top: 27px; margin-right: 11px;height: 64px; "></a> &nbsp; 
                    <span style="font-weight: bolder;position: relative;top: 20px;">無料会員登録</span> 
                </div>

                <hr style="background-color: rgb(0,175,239);height: 3px;border: none;">

                <div class="row mb-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">  
                        <div id="steps_nav">
                            <div class="arrow">STEP 1 <br> 登録情報のご入力</div>
                            <div class="arrow">STEP 2 <br> 内容のご確認</div>						
                            <div class="arrow">STEP 3 <br> メールのご確認</div>						
                            <div class="arrow-active">STEP 4 <br> 登録完了</div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>



                <div class="row justify-content-center  mx-2">
                    <div class="col-md-12">
                        <div class="card esi-card my-5">
                            <div class="card-header esi-card-header">{{ __('ご登録ありがとうございました') }}</div>
                            <div class="card-body py-3">

                                <div id="confirmation-message" class="mb-5">
                                    <p>{{ $user->lastname ?? '' }} , {{ $user->firstname  ?? ''}}</p>

                                    <div>
                                        <p>
                                            会員マイページ（体験レッスン予約ページ）のご用意ができました。<br>
                                            Net 英会話教室にご登録いただきまして、誠にありがとうございます。<br>
                                        </p>
                                    </div>
                                    
                                    <p>
                                        レッスン予約管理ページ（マイページ）へログインできます。
                                        <a href="{{ url('login') }}">ログイン画面</a>
                                        よりどうぞ。
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

           

           
        </div>
    </div>
</div>
@endsection
