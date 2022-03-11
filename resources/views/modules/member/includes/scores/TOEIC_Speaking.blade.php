    <!--[start] TOEIC-Speaking- -->
    <div id="examination-score-TOEIC_Speaking" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-12">
                <div class="field-label"> <span class="text-danger">*</span> Speaking Score </div>                
            </div>
            <div class="col-8">
                <select id="TOEIC_Speaking_speakingScore" name="speakingScore"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select speaking Score</option>
                    @for($item = 0; $item <= 200; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>

    </div>
    <!--[end]-->
