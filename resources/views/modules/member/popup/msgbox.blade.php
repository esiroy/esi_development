<!--<div class="modal fade" id="msgboxModal" tabindex="-1" role="dialog" aria-labelledby="msgbox" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document" style="margin-top:100px">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">

                        <center style="margin-top:5%;">
                            <div id="msgboxMessage"></div>
                        </center>

                        <center style="margin-top:5%;">
                            <a class="btn btn-primary text-white" onclick="return closeModal('#msgboxModal');return false">Ok</a>
                        </center>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>-->

<div class="modal fade" id="msgboxModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document" style="margin-top:125px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">エラー</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-center" style="margin-top:5%;">
            <div id="msgboxMessage"></div>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
