<div class="modal fade" id="showAddMemberExamScoreModal" tabindex="-1" role="dialog" aria-labelledby="AddMemberExampScoreModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document" style="margin-top:100px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Member Score</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="submitFormExamScore" id="submitFormExamScore" method="POST" onsubmit="return false">

                    <div class="examMenu">

                        <div class="row">
                            <div class="col-md-8">                            
                                <span class="text-danger">*</span>  Examination Date 
                                <input type="date" id="examDate" name="examDate" value="{{ date('Y-m-d') }}" min="2000-01-01" data-date-format="YYYY年 M月 DD日" 
                                    class="inputDate hasDatepicker form-control form-control-sm col-md-12" data-date="{{ date('Y年 m月 d日') }}">
                                
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-md-8">
                                <span class="text-danger">*</span>  Examination Type
                                <select id="examType" name="examType" class="form-control form-control-sm pl-0 col-md-12" required>
                                    <option value="" class="mx-0 px-0">Select Examination Type</option>
                                    <option value="IELTS" class="mx-0 px-0">IELTS</option>
                                    <option value="TOEFL">TOEFL iBT</option>
                                    <option value="TOEFL_Junior">TOEFL Junior</option>
                                    <option value="TOEFL_Primary_Step_1">TOEFL Primary Step 1</option>
                                    <option value="TOEFL_Primary_Step_2">TOEFL Primary Step 2</option>                                    
                                    <option value="TOEIC_Listening_and_Reading">TOEIC Listening & Reading</option>                            
                                    <option value="TOEIC_Speaking">TOEIC Speaking</option>
                                    <option value="EIKEN">EIKEN(英検）</option>
                                    <option value="TEAP">TEAP</option>
                                    <option value="Other_Test">Other Test</option>
                                </select>                          
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @include('modules.member.includes.scores.IELTS')
                            @include('modules.member.includes.scores.TOEFL')
                            @include('modules.member.includes.scores.TOEFL_Junior')
                            @include('modules.member.includes.scores.TOEFL_Primary_Step_1')
                            @include('modules.member.includes.scores.TOEFL_Primary_Step_2')
                            @include('modules.member.includes.scores.TOEIC_Listening_and_Reading')
                            @include('modules.member.includes.scores.EIKEN')
                            @include('modules.member.includes.scores.TOEIC_Speaking')
                            @include('modules.member.includes.scores.TEAP')
                            @include('modules.member.includes.scores.OTHERS')

                        </div>

                    </div>


                    <div class="mt-2">
                        <div class="float-right">
                            <input id="cancelAddScore" type="button"  value="Cancel" class="btn btn-sm btn-danger">
                            <input id="submitScore" type="submit" value="Submit Score" class="btn btn-sm  btn-primary">
                        </div>
                    </div>

                    </div>

                </form>
            </div>

           
            
        </div>
    </div>
</div>


@section('styles')
@parent
<style>

   
   #loadingModal {
        display: block;
        background-color: #0009;
    }
  

    .table-schedules tbody th {
        height: 130px !important;
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

    .examScoreHolder {
        display: none;
    }

    
</style>
@endsection