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

                <!--sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>

                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.customersupport')
                    </div>

                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

                <div class="col-md-9">

                    <h1 class="callout">お知らせ</h1>

                    <div class="blueBrokenLineBox text-center announcements px-4 py-4">                    
                        {!! html_entity_decode($announcement->body) ?? '' !!}
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-3">

                            <a href="{{ url('lessonrecord') }}">
                                <!--<button type="button" class="btn btn-warning text-dark rounded" style="background-color:#fcc120">受講履歴/添削履歴</button>-->
                                <button class="btn-rounded btn-yellow text-dark">受講履歴/添削履歴</button>
                            </a>

                            <a href="{{ url('memberschedule') }}">
                                <!--<button type="button" class="btn btn-primary  text-dark ">レッスンの予約</button>-->
                                <img src="{{ url('images/btnBlue.gif') }}">
                            </a>

                            <a href="JavaScript:newPopup('http://writing.mytutor-jpn.info/');" data-toggle="modal" data-target="#writingServiceModal" >
                                <!--<button type="button" class="btn btn-success  text-dark ">添削くん</button>-->
                                <img src="{{ url('images/newBtn3.png') }}">
                            </a>                      
                        </div>
                    </div>

                    <div class="grayBackgroundBox mt-4 pt-3 px-2">
                        <p>今日のレッスンはいかがでしたか？ 今後の円滑な運営と質の高いレッスン のご提供のため、何かお気づきの点がありましたら アンケートにお答え下さい！</p>
                        <p align="right">
                            <a href="{{ url('lessonrecord') }}">
                                <img src="images/btnRed2.gif" alt="Alternate Text Here" title="Title Text Here">
                            </a>
                        </p>
                    </div>



                    <div class="reservationTable mt-4">
                            <!--[start reservation table -->
                            <table width="100%" cellspacing="0" cellpadding="5" border="0" align="center">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="heading">担任講師による固定レッスン</th>
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
                                        <td class="text-center font-weight-normal">{{ $schedule->desired_time ?? '' }}</td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>


                    <!--
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
                    -->

                    <!--[start] lesson lists-->
                    <div class="card mt-4" style="">
                        <div class="card-header esi-card-header text-center">
                            予約表
                            <small style="font-size:11px; color:#333">予約記録は最大５件まで表示されます</small>
                        </div>

                        <div class="card-body px-3">
                            初めての講師の場合、講師からSkype(ZOOM)コンタクトリクエストがあります。 レッスン時間の15分前にSkype(ZOOM)を立ち上げ承認してコールをお待ちください。

                            <table cellspacing="0" cellpadding="9" class="tblRegister mt-3" width="100%">
                                <tbody>
                                    <tr>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;" colspan="2">Tutor</th>
                                        <th style="text-align: center;">講師への連絡</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                    <!-- COUNTER FOR PAGINATION -->
                                    @foreach ($reserves as $reserve)
                                    <tr class="row_reserve_{{$reserve->id}}">
                                        <td style="text-align: center;">
                                            {{  date('Y年 m月 d日 H:i', strtotime($reserve->lesson_time)) }}
                                            
                                            {{  date('H:i', strtotime($reserve->lesson_time." + 25 minutes ")) }}
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
                                            <a href="javascript:void()" data-toggle="modal" data-target="#tutorMemoModal" data-id="{{ $reserve->id }}">
                                                <img src="images/iEmail.jpg" border="0" align="absmiddle"> 講師への連絡
                                            </a>

                                            <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="{{ $reserve->id }}">Open modal for @mdo</button>-->

                                        </td>
                                        <td style="text-align: center;">
                                            
                                            <a href="javascript:void(0)" onClick="cancel('{{$reserve->id}}')"><img src="{{ url('images/btnBlue2.gif') }}" alt="欠席する" title="欠席する"></a>                                             
                                        </td>
                                    </tr>
                                    @endforeach


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
 @include('modules.member.popup.memo')


@endsection


@section('scripts')
@parent
<script type="text/javascript">
    var api_token = "{{ Auth::user()->api_token }}";

    function cancel(id)
    {
        if (confirm('このレッスンをキャンセル（欠席）されるとポイントは消化されます。キャンセル(欠席）しますか？')) 
        {
            $.ajax({
                type: 'POST', 
                url: 'api/cancelSchedule?api_token=' + api_token,
                data: {
                    id: id
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(data) {
                
                    $('.row_reserve_' + id ).hide();                
                }
            });
        }
    }

    function getMemo(scheduleID) {
        $.ajax({
            type: 'POST', 
            url: 'api/getMemo?api_token=' + api_token,
            data: {
                'scheduleID': scheduleID,
            }, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found getting memo: " + error);
            },            
            success: function(data) {            
                console.log("memo received....")            
                $('#message').val(data.memo)
            },
        });
    }

    function sendMemo(scheduleID, message) {
        $.ajax({
            type: 'POST', 
            url: 'api/sendMemo?api_token=' + api_token,
            data: {
                'scheduleID': scheduleID,
                'message': message
            }, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (data, error) {             
                alert("Error Found getting memo: " + error);    
            },
            success: function(data) {
            
                console.log("memo sent....");

                $('#tutorMemoModal').modal('hide')      
            }
        });
    }

    function closePopUp(url) {
        $('#writingServiceModal').modal('hide');    

        window.open(url,'popUpWindow','height=600,width=720,left=50,top=50,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes')

        return false;

    }

    window.addEventListener('load', function () 
    {
        var button;
        var id;
        var modal;

        $('#tutorMemoModal').on('show.bs.modal', function (event) 
        {
            button = $(event.relatedTarget) // Button that triggered the modal
            id = button.data('id') // Extract info from data-* attributes            
            modal = $(this); 
            getMemo(id)
        });

        $('#saveTutorMemo').on('click', function() 
        {           
            let scheduleID = id;
            let message = $('#message').val();
            console.log(message);
            sendMemo(scheduleID, message);
        });



    });

</script>
@endsection
