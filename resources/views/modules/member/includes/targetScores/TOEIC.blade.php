<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEIC" value="TOEIC" class="main_option" {{ checkbox_ticker( $purpose['TOEIC'] ?? old('TOEIC') , 'TOEIC') }}> TOEIC
    <div class="TOEIC ml-4 sub_options">
        <input type="checkbox" name="TOEIC_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['TOEIC_Speaking'] ??  old('TOEIC_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_speakingScore" name="TOEIC_Speaking" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select speaking Score</option>
                    @for($score = 0; $score <= 200; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEIC_Speaking']) && $target_score['TOEIC_Speaking'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>
            </div>
        </div>


        <input type="checkbox" name="TOEIC_option[]" value="Writing" {{ checkbox_ticker( $purpose_option['TOEIC_Writing'] ?? old('TOEIC_option'), 'Writing') }}> Writing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_Listening_and_Reading_writingScore" name="TOEIC_Writing" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    @for($score = 5; $score <= 495; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEIC_Writing']) && $target_score['TOEIC_Writing'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>

            </div>
        </div>


        <input type="checkbox" name="TOEIC_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['TOEIC_Reading'] ?? old('TOEIC_option'), 'Reading') }}> Reading <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_Listening_and_Reading_readingScore" name="TOEIC_Reading" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    @for($score = 5; $score <= 495; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['TOEIC_Reading']) && $target_score['TOEIC_Reading'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="TOEIC_option[]" value="Listening" {{ checkbox_ticker($purpose_option['TOEIC_Listening'] ??  old('TOEIC_option'), 'Listening') }}> Listening <br/>                                       
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_listeningScore" name="TOEIC_Listening" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    @for($score = 5; $score <= 495; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0"  @if( isset($target_score['TOEIC_Listening']) && $target_score['TOEIC_Listening'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->
