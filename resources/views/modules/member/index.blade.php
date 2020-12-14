@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User</li>
            </ol>
        </nav>

        <div class="container">

            <div class="row">
                <!--sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div><!--[end sidebar]-->
                
                <div class="col-md-9">
                    <div class="blueBrokenLineBox">
                        <div class="text-center">
                            <p>只今、4月26日(日)まで講師スケジュールが公開されております。</p>
                            <p>講師スケジュールの更新は毎週月曜日を予定しております。</p>
                            <p>＜講師臨時休講のご案内＞</p>
                            <p>&nbsp;</p>
                            <span style="font-size: medium;"><a href="https://www.mytutor-jpn.com/info/2020/0317152413.html">一時的　在宅勤務講師のご案内　</a></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <a href="lessonrecord"><button type="button" class="btn btn-warning text-white">受講履歴/添削履歴</button></a>
                            <a href="reservation"><button type="button" class="btn btn-primary">レッスンの予約</button></a>
                            <a href="JavaScript:newPopup('http://writing.mytutor-jpn.info/');"><button type="button" class="btn btn-success">添削くん</button></a>
                        </div>
                    </div>

                </div>                
            </div>


        </div>


    </div>
</div>
@endsection