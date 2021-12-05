    <!--[start] TOEFL-iBT -->
    <div id="examination-score-TOEFL" class="section examScoreHolder">

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Speaking Score </div>             
            </div>
            <div class="col-8">            
                <select id="TOEFL_speakingScore" name="TOEFL_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    @for($item = 0; $item <= 30; $item++) 
                        <option value="{{ $item }}" class="mx-0 px-0">{{ $item  }}</option>                     
                    @endfor
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-12">                                       
                <div class="field-label"> <span class="text-danger">*</span> Writing Score </div>                
            </div>
            <div class="col-8">            
                <select id="TOEFL_writingScore" name="TOEFL_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    @for($item = 0; $item <= 30; $item++) 
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
                <select id="TOEFL_readingScore" name="TOEFL_examScore[]"  class="TOEFL_select form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    @for($item = 0; $item <= 30; $item++)                     
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-8">
                <select id="TOEFL_listeningScore" name="TOEFL_examScore[]" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    @for($item = 0; $item <= 30; $item++)                     
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
                <select id="TOEFL_total" name="TOEFL_examScore[]" disabled class="form-control form-control-sm pl-0 mb-2">
                    <option value="" class="mx-0 px-0" >Total Score</option>
                    @for($item = 0; $item <= 120; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>                     
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <!--[end]-->