    <!--[start] TEAP -->
    <div id="examination-score-TEAP" class="section examScoreHolder">

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Speaking Score </div>             
            </div>
            <div class="col-8">            
                <select id="TEAP_speakingScore" name="TEAP_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    @for($item = 20; $item <= 100; $item++) 
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
                <select id="TEAP_writingScore" name="TEAP_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    @for($item = 20; $item <= 100; $item++) 
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
                <select id="TEAP_readingScore" name="TEAP_examScore[]"  class="TEAP_select form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    @for($item = 20; $item <= 100; $item++)                     
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
                <select id="TEAP_listeningScore" name="TEAP_examScore[]" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    @for($item = 20; $item <= 100; $item++)                     
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
                <select id="TEAP_totalScore" name="TEAP_examScore[]" disabled class="form-control form-control-sm pl-0 mb-2">
                    <option value="" class="mx-0 px-0" >Total Score</option>
                    @for($item = 80; $item <= 400; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>                     
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <!--[end]-->