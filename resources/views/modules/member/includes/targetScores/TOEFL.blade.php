<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL" value="TOEFL" class="main_option" {{ checkbox_ticker( $purpose['TOEFL'] ?? old('TOEFL') , 'TOEFL') }}> TOEFL
    <div class="TOEFL ml-4 sub_options">

    
        <input type="checkbox" name="TOEFL_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['TOEFL_Speaking'] ?? old('TOEFL_option'), 'Speaking') }}> Speaking <br/>

        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEFL_speakingScore" name="TOEFL_Speaking"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Speaking Score</option>
                    @for($score = 0; $score <= 30; $score++) 
                        <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEFL_Speaking']) && $target_score['TOEFL_Speaking'] == $score) {{ ' selected ' }} @endif>{{ $score  }}</option>                     
                    @endfor
                </select>
            </div>
        </div> 


        <input type="checkbox" name="TOEFL_option[]" value="Writing" {{ checkbox_ticker($purpose_option['TOEFL_Writing'] ??  old('TOEFL_option'), 'Writing') }}> Writing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">

                <select id="TOEFL_writingScore" name="TOEFL_Writing"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Writing Score</option>
                    @for($score = 0; $score <= 30; $score++) 
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEFL_Writing']) && $target_score['TOEFL_Writing'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>

            </div>
        </div>        
        <input type="checkbox" name="TOEFL_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['TOEFL_Reading'] ?? old('TOEFL_option'), 'Reading') }}> Reading <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEFL_readingScore" name="TOEFL_Reading"  class="TOEFL_select form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Reading Score</option>
                    @for($score = 0; $score <= 30; $score++)                     
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEFL_Reading']) && $target_score['TOEFL_Reading'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>
            </div>
        </div>        
        <input type="checkbox" name="TOEFL_option[]" value="Listening" {{ checkbox_ticker(  $purpose_option['TOEFL_Listening'] ?? old('TOEFL_option'), 'Listening') }}> Listening <br/>                                        
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEFL_listeningScore" name="TOEFL_Listening" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Listening Score</option>
                    @for($score = 0; $score <= 30; $score++)                     
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEFL_Listening']) && $target_score['TOEFL_Listening'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>
            </div>
        </div>        
    </div>
</div>
<!--[end] Checkbox Group -->