<div class="modal fade" id="showAddMemberExamScoreModal" tabindex="-1" role="dialog" aria-labelledby="AddMemberExampScoreModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document" style="margin-top:100px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tutorMemoLabel">Add Member Score</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="submitFormExamScore" id="submitFormExamScore" method="POST" onsubmit="return false">
                    <div class="mt-2">
                        Examination Date 
                        <input type="date" id="examDate" name="examDate" value="{{ date('Y-m-d') }}" data-date-format="YYYY年 M月 DD日" class="inputDate hasDatepicker form-control form-control-sm col-12" data-date="2021年 11月 22日">
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

