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
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

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
                            <a href="{{ url('lessonrecord') }}"><button type="button" class="btn btn-warning text-white">受講履歴/添削履歴</button></a>
                            <a href="reservation"><button type="button" class="btn btn-primary">レッスンの予約</button></a>
                            <a href="JavaScript:newPopup('http://writing.mytutor-jpn.info/');"><button type="button" class="btn btn-success">添削くん</button></a>
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

                    <div class="card  mt-4" style="">
                        <div class="card-header esi-card-header text-center">
                            予約表
                            <small style="font-size:11px; color:#333">予約記録は最大５件まで表示されます</small>
                        </div>

                        <div class="card-body px-3">
                            初めての講師の場合、講師からSkype(ZOOM)コンタクトリクエストがあります。 レッスン時間の15分前にSkype(ZOOM)を立ち上げ承認してコールをお待ちください。

                            <table cellspacing="0" cellpadding="9" align="center" class="tblRegister mt-3" width="100%">
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
                                            {{  date('Y年 m月 d日 H:i', strtotime($reserve->lesson_time." + 1 hour ")) }}
                                            {{  date('H:i', strtotime($reserve->lesson_time." + 85 minutes ")) }}
                                        </td>
                                        <td style="text-align: center;" colspan="2">                                            
                                            @php
                                                $tutor = \App\Models\Tutor::where('user_id', $reserve->tutor_id)->first();
                                            @endphp
                                            <div id="{{ $tutor->user_id }}" class="tutor_name">{{ $tutor->user->firstname ?? " - " }} {{ $tutor->user->lastname ?? "" }}</div>

                                        </td>
                                        <td style="text-align: center;">
                                            <a href="javascript:void()" onclick="memoForm('{{$reserve->id}}')"><img src="images/iEmail.jpg" border="0" align="absmiddle"> 講師への連絡</a>
                                        </td>
                                        <td style="text-align: center;">
                                            
                                            <a href="javascript:void(0)" onClick="cancel('{{$reserve->id}}')"><img src="{{ url('images/btnBlue2.gif') }}" alt="欠席する" title="欠席する"></a>                                             
                                        </td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>


            </div>


        </div>


    </div>
</div>
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

</script>
@endsection
