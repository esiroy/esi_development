<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="EIKEN" value="EIKEN" class="main_option" {{ checkbox_ticker( $purpose['EIKEN'] ?? old('EIKEN') , 'EIKEN') }}> EIKEN(英検）
    <div class="EIKEN ml-4 sub_options">
        <input type="checkbox" name="EIKEN_option[]" value="Grade 5" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_5'] ?? old('EIKEN_option'), 'Grade 5') }}> Grade 5 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_5" name="EIKEN_Grade_5"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 5 Score</option>
                    @for($score = 0; $score <= 850; $score++) 
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_5']) && $target_score['EIKEN_Grade_5'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 4" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_4'] ?? old('EIKEN_option'), 'Grade 4') }}> Grade 4 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_4" name="EIKEN_Grade_4" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 4 Score</option>
                    @for($score = 0; $score <= 1000; $score++) 
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_4']) && $target_score['EIKEN_Grade_4'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 3" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_3'] ?? old('EIKEN_option'), 'Grade 3') }}> Grade 3 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_3_1st_stage" name="EIKEN_Grade_3_1st_Stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 3 1st Stage Score</option>
                    @for($score = 0; $score <= 1650; $score++)                     
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_3_1st_Stage']) && $target_score['EIKEN_Grade_3_1st_Stage'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 2" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_pre_2'] ?? old('EIKEN_option'), 'Grade pre 2') }}> Grade pre 2 <br/>   
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_pre_2_1st_stage" name="EIKEN_Grade_Pre_2_1st_Stage" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 2 1st Stage Score</option>
                    @for($score = 0; $score <= 1800; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_Pre_2_1st_Stage']) && $target_score['EIKEN_Grade_Pre_2_1st_Stage'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 2" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_2'] ?? old('EIKEN_option'), 'Grade 2') }}> Grade 2 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_2_1st_stage" name="EIKEN_Grade_2_1st_Stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 2 1st Stage Score</option>
                    @for($score = 0; $score <= 1950; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_2_1st_Stage']) && $target_score['EIKEN_Grade_2_1st_Stage'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 1" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_pre_1'] ?? old('EIKEN_option'), 'Grade pre 1') }}> Grade pre 1 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_pre_1_1st_stage" name="EIKEN_Grade_Pre_1_1st_Stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 1 1st Stage Score</option>
                    @for($score = 0; $score <= 2250; $score++)
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_Pre_1_1st_Stage']) && $target_score['EIKEN_Grade_Pre_1_1st_Stage'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 1" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_1'] ?? old('EIKEN_option'), 'Grade 1') }}> Grade 1 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_1_1st_stage" name="EIKEN_Grade_1_1st_Stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 1 1st Stage Score</option>
                    @for($score = 0; $score <= 2250; $score++)                    
                    <option value="{{ $score }}" class="mx-0 px-0" @if( isset($target_score['EIKEN_Grade_1_1st_Stage']) && $target_score['EIKEN_Grade_1_1st_Stage'] == $score) {{ ' selected ' }} @endif>{{ $score }}</option>
                    @endfor
                </select>            
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->
