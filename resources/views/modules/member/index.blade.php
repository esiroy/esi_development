@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User</li>
            </ol>
        </nav>

        <div class="container pb-5">
            <div class="row">

                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->

                <!-- ANNOUNCEMENTS -->
                <div class="col-md-9">

                    @if (isset($announcement->body))
                        <h1 class="callout">お知らせ</h1>                        
                        <div class="blueBrokenLineBox announcements px-4 py-4">                    
                            {!! html_entity_decode($announcement->body) ?? '' !!}
                        </div>
                    @else 
                        
                    @endif

                    <div class="row">
                        <div class="col-md-12 mt-3">

                            <a href="{{ url('lessonrecord') }}">
                                <img src="{{ url('images/btnYellow3.png') }}" alt="受講履歴/添削履歴" title="受講履歴/添削履歴">
                            </a>

                            <a href="{{ url('memberschedule') }}">                                
                                <img src="{{ url('images/btnBlue.gif') }}" alt="レッスンの予約" title="レッスンの予約">
                            </a>

                            @php 
                            /*
                            <a href="JavaScript:newPopup('http://writing.mytutor-jpn.info/');" data-toggle="modal" data-target="#writingServiceModal" >
                                <img src="{{ url('images/newBtn3.png') }}" alt="添削くん" title="添削くん">
                            </a> 
                            

                            <a href="JavaScript:PopupCenter('{{ url('/writing') }}','講師への連絡　チャット」とは',900,820);">
                                <img src="{{ url('images/newBtn3.png') }}" alt="添削くん" title="添削くん">
                            </a>      
                            */
                            @endphp     

                           <a href="{{ url('/writing') }}">
                                <img src="{{ url('images/newBtn3.png') }}" alt="添削くん" title="添削くん">
                            </a>  
                            
                        </div>
                    </div>

                    <div class="grayBackgroundBox mt-4 pt-3 px-2">
                        <p>今日のレッスンはいかがでしたか？ 今後の円滑な運営と質の高いレッスン のご提供のため、何かお気づきの点がありましたら アンケートにお答え下さい！</p>
                        <p class="text-right">
                            <a href="{{ url('lessonrecord?display=none') }}">
                                <img src="images/btnRed2.gif" alt="受講履歴　評価e" title="受講履歴　評価">
                            </a>
                        </p>
                    </div>



                    <div class="reservationTable mt-4">
                            <!--[start reservation table -->
                            <table width="100%" cellspacing="0" cellpadding="5" border="0" align="center">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="heading">
                                            <a href="https://www.mytutor-jpn.com/info/2020/0526183735.html" target="_blank">担任講師による固定レッスン</a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($desiredSchedules as $schedule)
                                    <tr class="border-bottom">
                                        <td class="text-center font-weight-normal">{{ $schedule->day ?? '' }}</td>
                                        <td class="text-center font-weight-normal">
                                            @if (date('H', strtotime($schedule->desired_time)) == 00)
                                                {{ date('24:i', strtotime($schedule->desired_time)) }} 
                                            @else
                                                {{ date('H:i', strtotime($schedule->desired_time)) }} 
                                            @endif                                            
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>


                    @php                     
                    /*
                    <div id="member-lesson-schedules" class="card esi-card mt-3">                        
                        <div class="card-header esi-card-header text-center">                            
                                レッスンの予約
                        </div>
                        <div class="card-body">   
                            <div id="member-scheduler" class="mt-3">
                                <p>
                                    固定レッスン以外はこちらから予約して下さい
                                    <img src="images/btnHand.jpg">
                                    <a href="memberschedule"><img src="images/btnRed4.gif"></a>
                                </p>
                                <p>30 分前まで予約可能です</p>
                            </div>
                        </div>
                    </div>
                    */
                    @endphp

                    <!--[start] lesson lists-->
                    <a name="reservation_section">

                    <div class="card mt-4" style="">
                        <div class="card-header esi-card-header text-center">
                            予約表
                            <small style="font-size:11px; color:#333">予約は最大15コマまでとなります</small>
                        </div>

                        <div class="card-body px-3">
                            初めての講師の場合、講師からSkype(ZOOM)コンタクトリクエストがあります。 レッスン時間の15分前にSkype(ZOOM)を立ち上げ承認してコールをお待ちください。

                            <br/>
                            講師からコールがない場合は、<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0717162035.html','講師への連絡　チャット」とは',900,820);">「講師への連絡」</a>からご連絡ください。

                            <table id="memberReservations" cellspacing="0" cellpadding="9" class="tblRegister mt-3" width="100%">
                                <tbody>
                                    <tr>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;" colspan="2">Tutor</th>
                                        <th style="text-align: center;">
                                            <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0717162035.html','講師への連絡　チャット」とは',900,820);">講師への連絡</a>
                                        </th>
                                        <th style="text-align: center;">
                                            
                                            <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0728183333.html','Action',900,820);">Action</a>
                                            
                                        </th>
                                    </tr>
                                    <!-- COUNTER FOR PAGINATION -->
                                    @foreach ($reserves as $reserve)

                                    <tr class="row_reserve_{{$reserve->id}} reserved_items">
                                        <td style="text-align: center;">

                                            @if (date('H', strtotime($reserve->lesson_time)) == '00') 
                                                {{  date('Y年 m月 d日 24:i', strtotime($reserve->lesson_time ." - 1 day")) }} - {{  date('24:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
                                            @else 
                                                {{  date('Y年 m月 d日 H:i', strtotime($reserve->lesson_time)) }} - {{  date('H:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
                                            @endif
                                            
                                        </td>
                                        <td style="text-align: center;" colspan="2">                                            
                                            @php
                                                $tutor = \App\Models\Tutor::where('user_id', $reserve->tutor_id)->first();
                                            @endphp

                                            @if (isset($tutor->user_id))
                                                <div id="{{ $tutor->user_id }}" class="tutor_name">{{ $tutor->user->firstname ?? " - " }} {{ $tutor->user->lastname ?? "" }}</div>
                                            @endif

                                        </td>
                                        <td style="text-align: center;">
                                            <!--<a href="javascript:void(0)" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $reserve->id }}">-->

                                            <a href="javascript:void(0)" onClick="openMemo('{{ $reserve->id }}')" data-toggle="modal" data-target="tutorMemoReplyModal" data-id="{{ $reserve->id }}">
                                                <img src="images/iEmail.jpg" border="0" align="absmiddle"> 講師への連絡
                                            </a>
                                        </td>
                                        <td style="text-align: center;">

                                            @php                                                
                                                $date_now =  date("Y-m-d H:i:s");
                                                $valid_time = date("Y-m-d H:i:s", strtotime($date_now ." + 3 hours"));
                                                $lessonTime = date("Y-m-d H:i:s", strtotime($reserve->lesson_time));
                                            @endphp

                                            
                                            
                                            @if ($reserve->schedule_status == "CLIENT_RESERVED_B")
                                                <a href="javascript:void(0)" onClick="cancelSchedule('{{$reserve->id}}')"><img src="{{ url('images/btnBlue2.gif') }}" alt="欠席する" title="欠席する"></a>                                                
                                            @else 
                                                @if ($valid_time <= $lessonTime) 
                                                    <!--valid time here since it is greater that 3 hours) -->
                                                    <a href="javascript:void(0)" onClick="deleteSchedule('{{$reserve->id}}')"><img src="{{ url('images/btnRed3.gif') }}" alt="取り消し" title="取り消し"></a>
                                                @else 
                                                    <a href="javascript:void(0)" onClick="cancelSchedule('{{$reserve->id}}')"><img src="{{ url('images/btnBlue2.gif') }}" alt="欠席する" title="欠席する"></a>                                                
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="5">
                                            <div class="float-right">
                                                {{ $reserves->links() }}

                                              
                                            </div>
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                        </div><!--[end] card body-->

                    </div><!--[end] card -->

                </div><!--[end] column-->
            </div><!--[end] row -->
        </div><!--[end] container -->
    </div> <!--[end] esi-box -->
</div>

@include('modules.member.popup.content')
@include('modules.member.popup.loading')
@include('modules.member.popup.memoSent')
@include('modules.member.popup.msgboxSuccess')      

@endsection