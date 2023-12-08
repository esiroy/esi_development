<div class="modal fade" id="questionnaireReadOnlyModal" tabindex="-1" role="dialog" aria-labelledby="questionnaireReadOnly" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" id="questionnaireForm" name="questionnaireForm">
                    @csrf

                    <input type="hidden" id="scheduleitemid" name="scheduleitemid" value="">
                    <input type="hidden" id="questionnaireid" name="questionnaireid" value="">
                    <input type="hidden" id="tutorid" name="tutorid" value="">


                    <table cellspacing="3" cellpadding="3">
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    通信状態が安定していた<br>（通信断、エコー、ノイズなどの雑音が無く安定していた。）
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4">
                                     <div id="comment_1"></div>
                                </td>                                

                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>


                            <tr>
                                <td colspan="4">
                                    講師の音声がきれいに聞こえた<br>（音量が安定し、他の講師の声などが気にならなかった）
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                     <div id="comment_2"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    講師の英語能力が高い
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                     <div id="comment_3"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="4">
                                    レッスン内容が良い<br>（生徒様のレベルと目的に合ったスピードと内容で、継続することで<br>英語能力が高くなるような気がした
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                     <div id="comment_4"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                   <div id="remarks"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>

        </div>



    </div>
</div>
