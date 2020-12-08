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
                <div class="col-md-3">
                    @include('modules.member.sidebar.profile')                    
                </div>
                
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
                </div>

                <div class="col-md-9">

                </div>

                
            </div>


        </div>


    </div>
</div>
@endsection