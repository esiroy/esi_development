    <!--[start] TOEIC-Listening-And-Reading- -->
    <div id="examination-score-TOEIC_Listening_and_Reading" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-8">
                <select id="TOEIC_Listening_and_Reading_listeningScore" name="listeningScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    @for($item = 5; $item <= 495; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>



        <div class="row pt-2">
            <div class="col-12">                                       
                <div class="field-label"> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div class="col-8">            
                <select id="TOEIC_Listening_and_Reading_readingScore" name="readingScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    @for($item = 5; $item <= 495; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-12">
                <div class="field-label"> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div class="col-8">
                <select id="TOEIC_Listening_and_Reading_totalScore" name="totalScore" disabled class="form-control form-control-sm pl-0 mb-2">
                    <option value="" class="mx-0 px-0">Select Total Score</option>
                    @for($item = 10; $item <= 990; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <!--[end]-->