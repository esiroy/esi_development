@extends('layouts.esi-public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 py-2">

            <div class="card">

                <div class="text-center">
                    <a href="{{ url('/') }}">
                    <img title="My Tutor" alt="My Tutor" src="images/title_full.png" style="vertical-align: middle;margin-top: 27px; margin-right: 11px;height: 64px; "></a> &nbsp; 
                    <span style="font-weight: bolder;position: relative;top: 20px;">無料会員登録</span> 
                </div>

                <hr style="background-color: rgb(0,175,239);height: 3px;border: none;">

                <div class="row mb-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">  
                        <div id="steps_nav">
                            <div class="arrow">STEP 1 <br> 登録情報のご入力</div>
                            <div class="arrow">STEP 2 <br> 内容のご確認</div>						
                            <div class="arrow-active">STEP 3 <br> メールのご確認</div>						
                            <div class="arrow">STEP 4 <br> 登録完了</div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>




            
                <form method="POST" action="{{ route('createMember') }}">
                    @csrf
                    <div class="row m-2 blueSolidBorderBox">
                        {!! $message !!}
                    </div>


            </div>

        </div>
    </div>
</div>
@endsection
