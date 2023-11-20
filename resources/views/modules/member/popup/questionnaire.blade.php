<div class="modal fade" id="questionnaireModal" tabindex="-1" role="dialog" aria-labelledby="questionnaire" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                                <td>
                                    <input type="radio" name="QUESTION_1grade" id="QUESTION_1GOOD" alt="" value="GOOD"> 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_1grade" id="QUESTION_1AVERAGE" alt="" value="AVERAGE"> まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_1grade" id="QUESTION_1BAD" alt="" value="BAD"> 良くなかった
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

                                <td>
                                    <input type="radio" name="QUESTION_2grade" id="QUESTION_2GOOD" alt="" value="GOOD"> 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_2grade" id="QUESTION_2AVERAGE" alt="" value="AVERAGE"> まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_2grade" id="QUESTION_2BAD" alt="" value="BAD"> 良くなかった
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

                                <td>
                                    <input type="radio" name="QUESTION_3grade" id="QUESTION_3GOOD" alt="" value="GOOD"> 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_3grade" id="QUESTION_3AVERAGE" alt="" value="AVERAGE"> まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_3grade" id="QUESTION_3BAD" alt="" value="BAD"> 良くなかった
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



                                <td>
                                    <input type="radio" name="QUESTION_4grade" id="QUESTION_4GOOD" alt="" value="GOOD"> 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_4grade" id="QUESTION_4AVERAGE" alt="" value="AVERAGE"> まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_4grade" id="QUESTION_4BAD" alt="" value="BAD"> 良くなかった
                                </td>

                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>


                            <tr>
                                <td colspan="4">
                                    <textarea name="remarks" id="remarks" cols="45" rows="3" style="min-height:75px">{{$questionnaire->remarks ?? ''}}</textarea>
                                </td>
                            </tr>



                        </tbody>



                    </table>
                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="postComment('{{ $scheduleID ?? '' }}')">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>

        </div>



    </div>
</div>
