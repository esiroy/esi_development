@extends('layouts.esi-app')
@section('content')


<div class="container bg-light">
    <div class="esi-box mb-5 pb-4">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Page</li>
            </ol>
        </nav>


        <div class="container mb-4">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">講師スケジュール </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-2 mb-4">
                                <div>予約 = 予約可能です</div>
                                <div>済　= 本人で予約済みです</div>
                                <div>済他 ＝　講師キャンセル又は他の受講生が予約済みです</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="width:375px; float: right; padding: 8px 20px; border: 1px solid #dec96d; background: #fee67d; font-weight: bold;">
                                @php /*済他＝　講師キャンセル又は他の受講生が予約済みです*/ @endphp
                                <div>予約は30分前まで、キャンセルは3時間前まで可能</div>
                            </div>

                            <div class="mt-1" style="width:375px; float: right; padding: 8px 20px; border: 1px solid #dec96d; background: #fee67d; font-weight: bold;">
                                <div>予約数制限：　最大15コマまで予約可能です</div>
                            </div>
                            
                            
                            <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0519152155.html', 'お気に入り講師の設定方法', 900, 720);" style="color:#212529">                            
                                <div class="mt-1" style="width:375px; float: right; padding: 8px 20px; border: 1px solid #dec96d; background: #fee67d; font-weight: bold;">
                                    <div>お気に入り講師を最大５人まで上段に選べます</div>
                                </div>
                            </a>
                            
                        </div>
                    </div>

                    <!--[start] Update Date -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form name="dateForm" method="GET">
                                <table>
                                    <tr>
                                        <td style="width:40px">
                                            <label for="dateToday" class="pr-0">Date:</label>
                                        </td>                                        
                                        <td style="width:150px">
                                            <input type="date" id="dateToday" name="dateToday" value="{{ $dateToday }}" min="2000-01-01" data-date-format="YYYY年 M月 DD日" 
                                            class="inputDate hasDatepicker form-control form-control-sm col-12">
                                        </td>
                                        <td>
                                            <input type="submit" class="btn btn-primary btn-sm form-control form-control-sm d-inline col-12" value="Go">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                    <!--[end] Update Date -->


                    <div class="card-header esi-card-header-title text-center">
                        {{ date('Y', strtotime($dateToday)) }} 年 {{ date('m', strtotime($dateToday)) }}月 {{ date('d', strtotime($dateToday)) }}日
                    </div>

                    <!--[start] card-body -->
                    <div id="tutor_content_holder" class="card-body scrollable-x p-0 text-center" style="height:720px">
                        <table class="table table-bordered table-schedules">
                            <thead>
                                <tr>
                                    <th class="schedTime static">
                                        <div class="bordered">
                                            <div class="class-schedule-container">
                                                <span class="flag-jp" style="vertical-align: bottom;"></span>
                                            </div>
                                        </div>
                                    </th>
                                    @foreach($lessonSlots as $lessonSlot)
                                    <td class="schedTime">
                                        <div class="bordered">
                                            <div class="gen-small">{{ $lessonSlot['startTime'] }}</div>
                                            <div class="gen-small">{{ $lessonSlot['endTime'] }}</div>
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                            <tr class="tr_clone_container"></tr>

                            @foreach($tutors as $tutor)
                            <tr id="{{ $tutor->user_id }}" class="tr_clone tutor_row_id_{{ $tutor->user_id}}">
                                <!--[start] Tutor Information-->
                                <th id="{{ $tutor->id }}">
                                    <div id="{{ $tutor->user_id }}" style="width:110px"  class="hbordered">

                                        <div class="photo pt-1">
                                            @php 
                                                $userImage = new App\Models\UserImage();
                                                $image =  $userImage->getTutorPhotobyID($tutor->user_id);
                                            @endphp
                                            @if ($image == null)
                                                <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" width="60px">
                                            @else 
                                                <img src="{{ Storage::url("$image->original") }}" class="img-fluid border" alt="profile photo" width="60px">
                                            @endif
                                        </div>

                                        <div class="tutor-name">
                                            <a href="javascript:void(0);" onclick="window.open('viewtutor/{{ $tutor->user_id }}','家庭教師の詳細')" class="text-danger font-weight-normal">
                                                @if (isset($tutor->user->japanese_firstname)) {!! $tutor->user->japanese_firstname !!}@endif <br/>
                                                @if (isset($tutor->user->firstname)) {!! "(" . $tutor->user->firstname . ")" !!} @endif
                                           
                                            </a>                                            
                                            <form action="/favorite/store">
                                                <input type="checkbox" id="favorite_{{ $tutor->user_id }}" class="favorite" name="favorite_{{ $tutor->user_id }}" value="{{ $tutor->user_id }}">    
                                            </form>                                        
                                        </div>

                                    </div>
                                </th>

                                @foreach($lessonSlots as $lessonSlot)
                                <td>
                                    @php
                                        //adjust time for Japanese standar time
                                        $startTimePH = date('H:i', strtotime($lessonSlot['startTime'] ." - 1 hour "));
                                        if ($lessonSlot['startTime'] >= '24:00') {
                                            $date = $nextDay;
                                        } else {
                                            $date = $dateToday;
                                        }
                                    @endphp

                                    @if (isset($schedules[$tutor->user_id][$date][$startTimePH]))

                                        @php
                                            $scheduleID = $schedules[$tutor->user_id][$date][$startTimePH]['id'];
                                            $scheduleMemberID =  $schedules[$tutor->user_id][$date][$startTimePH]['member_id'];
                                            $status = $schedules[$tutor->user_id][$date][$startTimePH]['status'];  
                                        @endphp
                                      
                                        @if ($status == "TUTOR_SCHEDULED")

                                            <div class="button_{{ $scheduleID }}">

                                                <a class="bookTutorSchedule" onclick="book('{{ $scheduleID }}', '{{ Auth::user()->id }}', '{{ $tutor->user_id }}')" href="javascript:void(0)">予約</a>

                                                <div class="doneTutorSchedule gen-med" style="display:none">済他</div>     
                                                
                                                <div class="cancel" style="display:none">
                                                    <div id="{{ $scheduleID }}" style="float:right">
                                                        <a href="javascript:void(0)" onClick="cancel('{{ $scheduleID }}')"><img src="{{ url('/images/btnDelete.png') }}"></a>
                                                    </div>
                                                    <br />
                                                  

                                                    <div class="comment_tooltip">
                                                        <a href="javascript:void(0)">済</a>

                                                        <span class="comment_tooltiptext">
                                                            <a href="javascript:void(0)" onClick="addCommentModal('{{$scheduleID}}', '{{$tutor->user_id}}')">Comment</a>
                                                        </span>

                                                    </div>

                                                </div>
                                            </div>

                                        @elseif($status == 'CLIENT_RESERVED' || $status == 'CLIENT_RESERVED_B')                                            

                                            <div class="button_{{$scheduleID}}">

                                                <a class="bookTutorSchedule" onclick="book('{{$scheduleID}}','{{ Auth::user()->id }}')" href="javascript:void(0)" style="display:none">
                                                    予約                                                    
                                                </a>

                                                <div class="doneTutorSchedule gen-med" style="display:none">済他</div>                                     
                                             
                                                @if ($member->user_id == $scheduleMemberID)
                                                    <div class="cancel">

                                                        <div id="{{ $scheduleID }}" style="float:right">
                                                            <a href="javascript:void(0)" onClick="cancel('{{$scheduleID}}')"><img src="{{ url('/images/btnDelete.png') }}"></a>
                                                        </div>
                                                        <br />

                                                        <div class="comment_tooltip">
                                                            <a href="javascript:void(0)" onClick="cancel('{{$scheduleID}}')">済</a>

                                                            @php 
                                                                //@todo: check if there  is already a comment in questionnaire, if there is then view mode only
                                                                $questionnaire = App\Models\Questionnaire::where('schedule_item_id', $scheduleID)->first();
                                                            @endphp

                                                            @if ($questionnaire)
                                                              <!--@todo: viewer for submitted comment -->
                                                                <div class="comment_tooltiptext">
                                                                    <a href="javascript:void(0)" onClick="viewCommentModal('{{$scheduleID}}', '{{$tutor->user_id}}')">View</a>
                                                                </div>                                                              
                                                            @else
                                                                <div class="comment_tooltiptext">
                                                                    <a href="javascript:void(0)" onClick="addCommentModal('{{$scheduleID}}', '{{$tutor->user_id}}')">Comment</a>
                                                                </div>
                                                            @endif 

                                                        </div>

                                                    </div>
                                                @else 
                                                    <div id="scheduleID_{{$scheduleID}}" class="gen-med">済他</div>
                                                @endif

                                            </div>

                                        @elseif($status == 'SUPPRESSED_SCHEDULE' )
                                            <div id="scheduleID_{{$scheduleID}}" class="gen-med">{{'済他'}}</div>
                                        @elseif($status == 'CLIENT_NOT_AVAILABLE')
                                            <!-- client is absent : (text)  欠席 -->                                            
                                            <!-- (Change to: Done and others'済他' -->
                                            <div id="scheduleID_{{$scheduleID}}"  class="gen-med">{{'済他'}}</div>	
                                        @elseif($status == 'TUTOR_CANCELLED')
                                            <div id="scheduleID_{{$scheduleID}}"  class="gen-med">{{ "済他" }}</div>
                                        @elseif ($status == "COMPLETED")                                         
                                            <div id="scheduleID_{{$scheduleID}}" class="gen-med">{{ "済他" }}</div>
                                        @endif

                                    @endif


                                </td>
                                @endforeach

                            </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('modules.member.popup.questionnaire')
    @include('modules.member.popup.questionnaireReadOnly')
    @include('modules.member.popup.loading')
    @include('modules.member.popup.msgbox')


</div>
@endsection

@section('styles')
@parent
<style>

   #loadingModal {
        display: block;
        background-color: #0009;
    }

    
    input.inputDate {
        overflow: hidden;
    }

    input.inputDate:before {    
        content: attr(data-date);
    }

    input.inputDate::-webkit-datetime-edit,
    input.inputDate::-webkit-inner-spin-button,
    input.inputDate::-webkit-clear-button {
        display: none;
    }

    input.inputDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
 

    .modal-backdrop {
        background-color: #0009;
    }

    .table-schedules td a,  .table-schedules td a:hover {
        color: #c60000;
        font: 14px Arial;
        text-align: center;            
    }

    .table-schedules td {
        min-width: 44px;
        padding: 0px;
        width: 44px;
        padding-top:38px;
    }

    .table-schedules td .gen-small {
        font: bold 11px Arial;
    }


    .table-schedules td.schedTime {
        background: #d0e8f7;
        text-align: center;
        font: bold 12px Arial;
        vertical-align: top;
        width: 37px;
        height: 30px;
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        top: -1px;
        padding-top:0px;
        padding-left:0px;
        padding-right:0px;
        padding-bottom:0px;
        z-index: 99;
    }

    .table-schedules th.schedTime .bordered {
        background: #d0e8f7;
        height: 40px;
        padding: 0px;
        border-bottom: 3px solid #72add2;
        border-right: 3px solid #72add2;        
    }
    

    .table-schedules td .bordered {
        border-top: 0px;
        border-left: 0px;
        border-bottom: 3px solid #72add2;
        border-right: 1px solid #fff;
        width: 100%;
        padding-top: 5px;
        padding-bottom: 5px; 
        height: 40px;

    }

    .table-schedules thead th {
        height: 40px;
        /*border-bottom: 3px solid #72add2;*/
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        background-color: #fff;
        z-index: 999;
        top: -1px;
        padding:0px;
    }

    .table-schedules tbody th, .static {
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        left: -1px;
        background-color: #fff;
        border: 0px;
        padding: 0px;
        margin: 0px;
        height: 60px;        
        z-index: 100;
    }

    .table-schedules tbody th .hbordered {
        border-top: 0px;
        border-left: 0px;
        border-right: 3px solid #72add2;
        border-bottom: 1px solid #72add2;
        width: 100%;
        height: 100%;
        padding-left: 5px;
        padding-right: 5px;
    }

    .table-schedules td , .table-schedules td div.gen-med {
        vertical-align: top;
        height: 39px;
        width: 37px;
        font: 14px Arial;
        text-align: center;
    }

</style>
@endsection


@section('scripts')
@parent

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>


<script type="text/javascript">

    var api_token = "{{ Auth::user()->api_token }}";

    window.addEventListener('load', function() 
    {   
        //activate loading modals
        jQuery.ajaxSetup({
            beforeSend: function() 
            {
                //hide questionnaire modals first
                $('#questionnaireModal').modal('hide');
                $('#questionnaireReadOnlyModal').modal('hide');   
                $('#loadingModal').modal('show');
            },
            complete: function(){
                $('#loadingModal').modal('hide');
                $('#loadingModal').hide(); 
            },
            success: function() {
                $('#loadingModal').modal('hide');
                $('#loadingModal').hide();                 
            }
        });

 

        //Initiate Get Favorite Tutors
        this.getFavoriteTutors()

        //add onclick action on tutor
        $(".tr_clone input.favorite").on('click', function() 
        {
            let clonedCount = $('.tr_cloned').length;  
            let tutorID = $(this).parent().parent().parent().parent().parent().attr('id'); 

            //save to DB - [favorite_tutor] Table
            if (confirm('お気に入りに追加しますか？')) { 

                if (clonedCount >= 5) {
                    alert ("お気に入りの上限を超えています。")
                    return false;
                } else {
                    saveFavoriteTutor(tutorID, {{ Auth::user()->id }});
                    highlightFavoriteTeacher(tutorID);
                }

            } else {
                return false;
            }           
            
        });

    });
    

    function saveFavoriteTutor(tutorID, memberID) 
    {
            
        $.ajax({
            type: 'POST',
            url: 'api/saveFavoriteTutor?api_token=' + api_token,
            data: {               
                tutorID: tutorID,
                memberID: memberID
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) 
            {
                if (data.success == true) 
                {   
                    $("#tutor_content_holder").animate({
                        scrollTop: 1
                    }, 1500);                   
                    
                } else {
                    alert(data.message);
                }

            }
        });
      
    }

    function removeFavoriteTutor(tutorID, memberID) {
        $.ajax({
            type: 'POST',
            url: 'api/removeFavoriteTutor?api_token=' + api_token,
            data: {               
                tutorID: tutorID,
                memberID: memberID
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) 
            {
                if (data.success == true) {
                //@todo: add message here for members to inform the tutor was removed from favorite list
                } else {
                    alert(data.message);
                }

            }
        });
      
    }


    function getFavoriteTutors() {
        $.ajax({
            type: 'POST',
            url: 'api/getFavoriteTutors?api_token=' + api_token,
            data: {               
                memberID: {{ Auth::user()->id }}
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) 
            {
                if (data.success == true) 
                {
                    let favoriteTutors = data.favoriteTutors;

                    favoriteTutors.forEach(function(favoriteTutor) {
                        console.log(favoriteTutor.tutor_id);

                        highlightFavoriteTeacher(favoriteTutor.tutor_id);
                    });
                    
                   
     

                } else {
                    alert(data.message);
                }

            }
        });
    }

    function highlightFavoriteTeacher(tutorID) {
        var $tr    = $('#'+tutorID).closest('.tr_clone');
        var $cloned = $tr.clone();
        $tr.hide();
        $('.tr_clone #favorite_'+tutorID).closest(':checkbox').prop('checked', false);
        

        //cloned row settings
        $cloned.insertBefore(".tr_clone_container");
        $cloned.find('td').css('background', 'none');
        $cloned.find('td').css('background-color', '#EAF0DD')
        //$cloned.find('td').css('background-color', 'yellow')
        
        $cloned.removeClass('tr_clone');
        $cloned.addClass('tr_cloned');    

        $('.tr_cloned #favorite_'+tutorID).closest(':checkbox').prop('checked', true);        

        $cloned.find(':checkbox').on('click', function() 
        {
            if (confirm('お気に入りを解除しますか？')) {            
                let favoriteTutorID = $(this).parent().parent().parent().parent().parent().attr('id');                  
                $(this).parent().parent().parent().parent().parent().remove();             
                $('.tutor_cloned_row_id_favorite_'+favoriteTutorID).hide();
                $('.tutor_row_id_'+favoriteTutorID).show();
                removeFavoriteTutor(favoriteTutorID, {{ Auth::user()->id }});
            } else {
                return false;
            }
        });

    }


    function disablePreviousDates() {
        var input = document.getElementById("dateToday");
        var today = new Date();
        var day = today.getDate();

        // Set month to string to add leading 0
        var mon = new String(today.getMonth()+1); //January is 0!
        var yr = today.getFullYear();
        if(mon.length < 2) { 
            mon = "0" + mon;
        }
        var date = new String( yr + '-' + mon + '-' + day );
        input.disabled = false; 
        input.setAttribute('min', date);
    }

    function book(scheduleID, memberID, tutorID) {
        
        $.ajax({
            type: 'POST',
            url: 'api/getTotalTutorDailyReserved?api_token=' + api_token,
            data: {                    
                memberID: memberID,
                tutorID, tutorID,
                date: "{{ $dateToday }}"
            },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#loadingModal').modal('show');
                $('#loadingModal').show();  
            },
            success: function(data) {  
                $('#loadingModal').modal('hide');
                $('#loadingModal').hide(); 
                if (data.success == true) {
                    if (data.totalTutorDailyReserved < 2) {
                        setTimeout(() => {
                            if (confirm('予約してもいいですか？')) {
                                SaveMemberSchedule(scheduleID, memberID, tutorID)
                            }
                        }, 100); 
                    } else {
                        setTimeout(() => {
                            if (confirm('同日、同講師の予約上限2コマを超えています。こちらの予約はキャンセルができませんがよろしいでしょうか？')) {
                                SaveMemberSchedule(scheduleID, memberID, tutorID)
                            }                        
                        }, 100);
                    }
                } else {
                    $('#msgboxModal').modal('show');
                    $('#msgboxMessage').text(data.message);
                }           
            }
        });

    }


    function SaveMemberSchedule(scheduleID, memberID, tutorID) 
    {
        let response = "";
        $.ajax({
            type: 'POST',
            url: 'api/book?api_token=' + api_token,
            data: {
                scheduleID: scheduleID,
                memberID: memberID,
                tutorID: tutorID
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                //$('#loadingModal').modal('hide');
                //$('#loadingModal').hide();  
            },
            success: function(data) {                    
                if (data.success == true) {
                    console.log(scheduleID);

                    $('.button_' + scheduleID + ' .bookTutorSchedule').hide();
                    $('.button_' + scheduleID + ' .cancel').show();
                    $('#total_credits').text(data.credits);

                } else {
                    
                    $('#loadingModal').modal('hide');
                    $('#loadingModal').hide();  
                                            
                    $('#msgboxModal').modal('show');
                    $('#msgboxMessage').text(data.message)
                    
                }
            },
            complete: function(data) {              
                $('#loadingModal').modal('hide');
                $('#loadingModal').hide();                     
            }          
        });
    }

    function closeModal(id) {
        $(id).modal('hide');
    }


    /*
    function cancel($id) {
        //get status if schedule A or B
        
    }
    */

    
    function cancel(id) {

        if (confirm('このレッスンをキャンセルしてもいいですか？')) {

            $.ajax({
                type: 'POST',
                url: 'api/cancelSchedule?api_token=' + api_token,
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) 
                {
                    if (data.bookable == true) {
                        $('.button_' + id + ' .bookTutorSchedule').show();
                        $('.button_' + id + ' .cancel').hide();
                    } else if (data.bookable == false) {
                        $('.button_' + id + ' .doneTutorSchedule').show();
                        $('.button_' + id + ' .cancel').hide();                        
                    }

                    //total credits
                    $('#total_credits').text(data.credits)        
                }
            });
        } else {
            return false;
        }
    }

    //Start Comments for Questionnaire
    function addCommentModal(scheduleID, tutorid) 
    {
        $('#scheduleitemid').val(scheduleID)
        $('#tutorid').val(tutorid)
        $('#questionnaireModal').modal('show');
    }

    function viewCommentModal(scheduleitemid, tutorid) 
    {   

        $.ajax({
            type: 'POST',
            url: 'api/viewComment?api_token=' + api_token,
            data: {
                scheduleitemid: scheduleitemid
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {

                if (data.success == true) 
                {   
                    $("#questionnaireReadOnlyModal .modal-body #comment_1").text(data.comment.questionnaireItem1);
                    $("#questionnaireReadOnlyModal .modal-body #comment_2").text(data.comment.questionnaireItem2);
                    $("#questionnaireReadOnlyModal .modal-body #comment_3").text(data.comment.questionnaireItem3);
                    $("#questionnaireReadOnlyModal .modal-body #comment_4").text(data.comment.questionnaireItem4);
                    $("#questionnaireReadOnlyModal .modal-body #remarks").text(data.comment.remarks);
                    $('#questionnaireReadOnlyModal').modal('show');
                } else {
                    alert(data.message);
                }
            }
        });

    }

    function postComment() {

        let scheduleitemid = $('#scheduleitemid').val();
        let questionnaireid = $('#questionnaireid').val();

        let tutor_id = $('#tutorid').val();
        let remarks = $('#remarks').val();


        let QUESTION_1grade = $("input[name='QUESTION_1grade']:checked").val();
        let QUESTION_2grade = $("input[name='QUESTION_2grade']:checked").val();
        let QUESTION_3grade = $("input[name='QUESTION_3grade']:checked").val();
        let QUESTION_4grade = $("input[name='QUESTION_4grade']:checked").val();

        $.ajax({
            type: 'POST',
            url: 'api/postComment?api_token=' + api_token,
            data: {
                scheduleitemid: scheduleitemid,
                tutor_id: tutor_id,
                remarks: remarks,
                QUESTION_1grade: QUESTION_1grade,
                QUESTION_2grade: QUESTION_2grade,
                QUESTION_3grade: QUESTION_3grade,
                QUESTION_4grade: QUESTION_4grade,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {

                if (data.success == true) {
                    alert(data.message)
                    $('#questionnaireModal').modal('hide');
                } else {
                    alert(data.message);
                }
            }
        });
    }

    function wait(ms){
        var start = new Date().getTime();
        var end = start;
        while(end < start + ms) {
        end = new Date().getTime();
        }
    }    

    window.addEventListener('load', function() {
        disablePreviousDates();

        jQuery(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")

        jQuery(".inputDateIcon").on("click", function(){
            jQuery(".inputDate").trigger("change");
        })

        $('#loadingModal').modal('hide');

        $('#loadingModal').hide();
    });




</script>
@endsection