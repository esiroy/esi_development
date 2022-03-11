    <!--[start] TOEFL_Junior_ -->
    <div id="examination-score-TOEFL_Junior" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-8">
                <select id="TOEFL_Junior_listeningScore" name="listeningScore"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    @for($item = 200; $item <= 300; $item++)      
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-12">                                       
                <div class="field-label"> <span class="text-danger">*</span> Language Form & Meaning</div>                
            </div>
            <div class="col-8">
                <select id="TOEFL_Junior_languageFormAndMeaningScore" name="languageFormAndMeaningScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-">Language Form & Meaning Score</option>
                    @for($item = 200; $item <= 300; $item++)      
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
                <select id="TOEFL_Junior_readingScore" name="readingScore"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    @for($item = 200; $item <= 300; $item++)      
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
                <select id="TOEFL_Junior_totalScore" name="total" disabled class="form-control form-control-sm pl-0 mb-2">
                    <option value="" class="mx-0 px-0">Select Total Score</option>
                    @for($item = 600; $item <= 900; $item++)
                    <option value="{{ $item}}" class="mx-0 px-0">{{ $item  }}</option>
                    @endfor                    
                </select>
            </div>
        </div>

    </div>
    <!--[end]-->