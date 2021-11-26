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
                    <div class="mt-2">
                        Examination Date 

                        <input type="date" id="examDate" name="examDate" value="{{ date('Y-m-d') }}" min="2000-01-01" data-date-format="YYYY年 M月 DD日" 
                                class="inputDate hasDatepicker form-control form-control-sm col-12" data-date="{{ date('Y年 m月 d日') }}">
                    </div>

                    <div class="mt-2">
                        Examination Type
                        <input type="text" id="examType" name="examType" value="" maxlength="100"required class="form-control form-control-sm">
                    </div>

                    <div class="mt-2">
                        Examination Score
                        <input type="text" id="examScore" name="examScore" value="" maxlength="200" required class="form-control form-control-sm">
                    </div>

                    <div class="mt-2">
                        <div class="float-right">
                            <input id="cancelAddScore" type="button"  value="Cancel" class="btn btn-sm btn-danger">
                            <input id="submitScore" type="submit" value="Submit Score" class="btn btn-sm  btn-primary">
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
</style>
@endsection