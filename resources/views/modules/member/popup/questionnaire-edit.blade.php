<div class="modal fade" id="questionnaireModal" tabindex="-1" role="dialog" aria-labelledby="questionnaire" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document" style="margin-top:100px">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="{{ route('questionnaire.store', ['id' => $reportcard->schedule_item_id  ]) }}">

                    @csrf
                    <table cellspacing="3" cellpadding="3">
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    通信状態が安定していた<br>（通信断、エコー、ノイズなどの雑音が無く安定していた。）
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="radio" name="QUESTION_1grade" id="QUESTION_1GOOD" alt="" value="GOOD" @if (isset($questionnaireItem1->grade) && $questionnaireItem1->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_1grade" id="QUESTION_1AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem1->grade) && $questionnaireItem1->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_1grade" id="QUESTION_1BAD" alt="" value="BAD" @if (isset($questionnaireItem1->grade) && $questionnaireItem1->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
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
                                    <input type="radio" name="QUESTION_2grade" id="QUESTION_2GOOD" alt="" value="GOOD" @if (isset($questionnaireItem2->grade) && $questionnaireItem2->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_2grade" id="QUESTION_2AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem2->grade) && $questionnaireItem2->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_2grade" id="QUESTION_2BAD" alt="" value="BAD" @if (isset($questionnaireItem2->grade) && $questionnaireItem2->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
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
                                    <input type="radio" name="QUESTION_3grade" id="QUESTION_3GOOD" alt="" value="GOOD" @if (isset($questionnaireItem3->grade) && $questionnaireItem3->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_3grade" id="QUESTION_3AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem3->grade) && $questionnaireItem3->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_3grade" id="QUESTION_3BAD" alt="" value="BAD" @if (isset($questionnaireItem3->grade) && $questionnaireItem3->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
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
                                    <input type="radio" name="QUESTION_4grade" id="QUESTION_4GOOD" alt="" value="GOOD" @if (isset($questionnaireItem4->grade) && $questionnaireItem4->grade == "GOOD") {{ 'checked="checked"' }} @endif > 良かった
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_4grade" id="QUESTION_4AVERAGE" alt="" value="AVERAGE" @if (isset($questionnaireItem4->grade) && $questionnaireItem4->grade == "AVERAGE") {{ 'checked="checked"' }} @endif > まあまあ
                                </td>
                                <td>
                                    <input type="radio" name="QUESTION_4grade" id="QUESTION_4BAD" alt="" value="BAD" @if (isset($questionnaireItem4->grade) && $questionnaireItem4->grade == "BAD") {{ 'checked="checked"' }} @endif > 良くなかった
                                </td>

                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>


                            <tr>
                                <td colspan="4">
                                    <textarea name="remarks" id="remarks" cols="45" rows="4">{{$questionnaire->remarks ?? ''}}</textarea>
                                </td>
                            </tr>


                            <tr>
                                <td colspan="4">
                                    <input type="submit" class="btn btn-pink" value="提出する">
                                </td>
                            </tr>


                        </tbody>



                    </table>
                </form>

            </div>
        </div>
    </div>
</div>