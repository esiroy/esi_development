<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="IELTS" value="IELTS" class="main_option" {{ checkbox_ticker( $purpose['IELTS'] ?? old('IELTS') , 'IELTS') }}> IELTS    

    <div class="IELTS ml-4 sub_options">

        <input type="checkbox" name="IELTS_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['IELTS_Speaking'] ?? old('IELTS_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score
              
            </div>
            <div class="col-md-8">
                <select id="speakingBandScore" name="IELTS_Speaking" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Speaking Score</option>
                    @for ($score = 3.0; $score <= 9.0; $score = $score+0.5)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['IELTS_Speaking']) && $target_score['IELTS_Speaking'] == $score) {{ ' selected ' }} @endif> {{ number_format($score, 1) }}</option>
                    @endfor
                    
                </select>
            </div>
        </div>

        <input type="checkbox" name="IELTS_option[]" value="Writing" {{ checkbox_ticker( $purpose_option['IELTS_Writing'] ?? old('IELTS_option'), 'Writing') }}> Writing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="writingBandScore" name="IELTS_Writing" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Writing Score</option>
                    @for ($score = 3.0; $score <= 9.0; $score = $score+0.5)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['IELTS_Writing']) && $target_score['IELTS_Writing'] == $score) {{ ' selected ' }} @endif> {{ number_format($score, 1) }}</option>
                    @endfor
                </select>            
            </div>
        </div>
            

        <input type="checkbox" name="IELTS_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['IELTS_Reading'] ?? old('IELTS_option'), 'Reading') }}> Reading <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="readingBandScore" name="IELTS_Reading" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Reading Score</option>
                    @for ($score = 3.0; $score <= 9.0; $score = $score+0.5)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['IELTS_Reading']) && $target_score['IELTS_Reading'] == $score) {{ ' selected ' }} @endif> {{ number_format($score, 1) }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="IELTS_option[]" value="Listening" {{ checkbox_ticker( $purpose_option['IELTS_Listening'] ?? old('IELTS_option'), 'Listening') }}> Listening <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="listeningBandScore" name="IELTS_Listening"class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Listening Score</option>
                    @for ($score = 3.0; $score <= 9.0; $score = $score+0.5)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['IELTS_Listening']) && $target_score['IELTS_Listening'] == $score) {{ ' selected ' }} @endif> {{ number_format($score, 1) }}</option>
                    @endfor
                </select>    
            </div>    
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->
