@extends('layouts.esi-app')
@section('content')
<div class="container bg-light">

    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Page</li>
            </ol>
        </nav>


        <div class="container">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">講師スケジュール </div>
                <div class="card-body">

                    <div class="mt-2 mb-4">
                        <div>予約= 予約可能です</div>
                        <div>済　= 本人で予約済みです</div>
                    </div>

                    <!--[start] Update Date -->
                    <form name="dateForm" method="GET">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-3">
                                <label for="dateToday" class="pr-0">Date:</label>
                                <input type="date" id="dateToday" name="dateToday" value="{{ $dateToday }}" min="2000-01-01" class="inputDate hasDatepicker form-control form-control-sm d-inline col-7">
                                <input type="submit" class="btn btn-primary btn-sm form-control form-control-sm d-inline col-3" value="Go">
                            </div>
                        </div>
                    </form>
                    <!--[end] Update Date -->

                    <div class="card-header esi-card-header-title text-center">
                        {{ date('Y', strtotime($dateToday)) }} 年 {{ date('m', strtotime($dateToday)) }}月 {{ date('d', strtotime($dateToday)) }}日
                    </div>

                    <div class="card-body scrollable-x p-0">
                        <table class="table table-bordered table-schedules">
                            <tr>
                                <td class="schedTime"></td>
                                @foreach($lessonSlots as $lessonSlot)
                                <td class="schedTime">
                                    <div>
                                        <div class="text-small">{{ $lessonSlot['startTime'] }}</div>
                                        <div class="text-small">{{ $lessonSlot['endTime'] }}</div>
                                    </div>
                                </td>
                                @endforeach
                            </tr>


                            @foreach($tutors as $tutor)
                            <tr>
                                <!--[start] Tutor Information-->
                                <td>
                                    <div style="width:125px">
                                        @if (isset($tutor->user->firstname))
                                            <small>{{ $tutor->user->firstname }}</small>
                                        @endif
                                    </div>
                                </td>

                                @foreach($lessonSlots as $lessonSlot)
                                <td>
                                
                                    @php
                                    $startTimePH = date('H:i', strtotime($lessonSlot['startTime'] ." - 1 hour "));
                                    @endphp


                                   @if ($schedules[$tutor->user_id][])
                                    @if ( $startTimePH == date("H:i", strtotime($schedule->lesson_time)))
                                        @foreach($schedules[$tutor->user_id]) {

                                        @endif
                                   @endif
                                </td>
                                @endforeach

                            </tr>
                            @endforeach

                        </table>
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

    function book(id) {
        $.ajax({
            type: 'POST'
            , url: 'api/book?api_token=' + api_token
            , data: {
                id: id
            }
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(data) {
                $("#msg").html(data.msg);
                $('.button_' + id + ' .book').hide();
                $('.button_' + id + ' .cancel').show();
            }
        });
    }


    function cancel(id) {
        $.ajax({
            type: 'POST'
            , url: 'api/cancelSchedule?api_token=' + api_token
            , data: {
                id: id
            }
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(data) {
                $("#msg").html(data.msg);
                $('.cancel_button_' + id + ' .book').show();
                $('.cancel_button_' + id + ' .cancel').hide();
            }
        });
    }

    window.addEventListener('load', function() {

        console.log('loaded')

        $('.book').on('click', function() {
            /*
                @todo: open popup if pressed.
                @question: 予約してもいいですか？
                @yes: はい
                @no: いいえ
            */

            if (confirm('予約してもいいですか？')) {
                // Save it!
                console.log('Thing was saved to the database.');
            } else {
                // Do nothing!
                //console.log('Thing was not saved to the database.');
            }
        })
    });

</script>
@endsection
