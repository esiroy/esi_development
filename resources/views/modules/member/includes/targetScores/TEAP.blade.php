
<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TEAP" value="TEAP" class="main_option" {{ checkbox_ticker( $purpose['TEAP'] ?? old('TEAP') , 'TEAP') }}> TEAP
    <div class="TEAP ml-4 sub_options">
        <input type="checkbox" name="TEAP_option[]" value="Speaking" {{ checkbox_ticker($purpose_option['TEAP_Speaking'] ??  old('TEAP_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TEAP_speakingScore" name="TEAP_Speaking"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    @for($score = 20; $score <= 100; $score++) 
                        <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TEAP_Speaking']) && $target_score['TEAP_Speaking'] == $score) {{ ' selected ' }} @endif>{{ $score  }}</option>                     
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="TEAP_option[]" value="Writing" {{ checkbox_ticker($purpose_option['TEAP_Writing'] ??  old('TEAP_option'), 'Writing') }}> Writing <br/>                                     
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TEAP_writingScore" name="TEAP_Writing"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    @for($score = 20; $score <= 100; $score++) 
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TEAP_Writing']) && $target_score['TEAP_Writing'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->