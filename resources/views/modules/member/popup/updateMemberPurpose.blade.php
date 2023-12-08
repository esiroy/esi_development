<div class="modal fade" id="showUpdateMemberPurposeModal" tabindex="-1" role="dialog" aria-labelledby="UpdateMemberPurposeModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Member Purpose</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="submitFormMemberPurpose" id="submitFormMemberPurpose" method="POST" onsubmit="return false">

                    <div class="container" style="height:550px; overflow-y: scroll">
                        @include('modules/member/includes/memberPurpose') 
                    </div>

                    <div class="mt-2">
                        <div class="float-right">
                            <input id="cancelUpdatePurpose" type="button" value="Cancel" class="btn btn-sm btn-danger">
                            <input id="updatePurpose" type="submit" value="Update Purpose" class="btn btn-sm  btn-primary">
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

