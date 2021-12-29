<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="IELTS" value="IELTS" class="main_option" {{ checkbox_ticker( $purpose['IELTS'] ?? old('IELTS') , 'IELTS') }}> IELTS    

    <div class="IELTS ml-4 sub_options">

        <input type="checkbox" name="IELTS_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['IELTS_Speaking'] ?? old('IELTS_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="speakingBandScore" name="IELTS_speaking" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Speaking Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div>

        <input type="checkbox" name="IELTS_option[]" value="Writing" {{ checkbox_ticker( $purpose_option['IELTS_Writing'] ?? old('IELTS_option'), 'Writing') }}> Writing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="writingBandScore" name="IELTS_Writing" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Writing Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>            
            </div>
        </div>
            

        <input type="checkbox" name="IELTS_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['IELTS_Reading'] ?? old('IELTS_option'), 'Reading') }}> Reading <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="readingBandScore" name="IELTS_Reading" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Reading Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div>

        <input type="checkbox" name="IELTS_option[]" value="Listening" {{ checkbox_ticker( $purpose_option['IELTS_Listening'] ?? old('IELTS_option'), 'Listening') }}> Listening <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="listeningBandScore" name="IELTS_Listening"class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Listening Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>    
            </div>    
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL" value="TOEFL" class="main_option" {{ checkbox_ticker( $purpose['TOEFL'] ?? old('TOEFL') , 'TOEFL') }}> TOEFL
    <div class="TOEFL ml-4 sub_options">
        <input type="checkbox" name="TOEFL_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['TOEFL_Speaking'] ?? old('TOEFL_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">

                <select id="TOEFL_speakingScore" name="TOEFL_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Speaking Score</option>
                    @for($item = 0; $item <= 30; $item++) 
                        <option value="{{ $item }}" class="mx-0 px-0">{{ $item  }}</option>                     
                    @endfor
                </select>

            </div>
        </div>        
        <input type="checkbox" name="TOEFL_option[]" value="Writing" {{ checkbox_ticker(  $purpose_option['TOEFL_Writing'] ??  old('TOEFL_option'), 'Writing') }}> Writing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">

                <select id="TOEFL_writingScore" name="TOEFL_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Writing Score</option>
                    @for($item = 0; $item <= 30; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>

            </div>
        </div>        
        <input type="checkbox" name="TOEFL_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['TOEFL_Reading'] ?? old('TOEFL_option'), 'Reading') }}> Reading <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEFL_readingScore" name="TOEFL_examScore[]"  class="TOEFL_select form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Reading Score</option>
                    @for($item = 0; $item <= 30; $item++)                     
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>        
        <input type="checkbox" name="TOEFL_option[]" value="Listening" {{ checkbox_ticker(  $purpose_option['TOEFL_Listening'] ?? old('TOEFL_option'), 'Listening') }}> Listening <br/>                                        
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEFL_listeningScore" name="TOEFL_examScore[]" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Target Listening Score</option>
                    @for($item = 0; $item <= 30; $item++)                     
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>        
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL_Primary" value="TOEFL Primary" class="main_option"  {{ checkbox_ticker( $purpose['TOEFL_Primary'] ?? old('TOEFL_Primary') , 'TOEFL Primary') }}> TOEFL Primary
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEIC" value="TOEIC" class="main_option" {{ checkbox_ticker( $purpose['TOEIC'] ?? old('TOEIC') , 'TOEIC') }}> TOEIC
    <div class="TOEIC ml-4 sub_options">
        <input type="checkbox" name="TOEIC_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['TOEIC_Speaking'] ??  old('TOEIC_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">

                <select id="TOEIC_speakingScore" name="speakingScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select speaking Score</option>
                    @for($item = 0; $item <= 200; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>

            </div>
        </div>        
        <input type="checkbox" name="TOEIC_option[]" value="Writing" {{ checkbox_ticker( $purpose_option['TOEIC_Writing'] ?? old('TOEIC_option'), 'Writing') }}> Writing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_Listening_and_Reading_writingScore" name="readingScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    @for($item = 5; $item <= 495; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>

            </div>
        </div>

        <input type="checkbox" name="TOEIC_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['TOEIC_Reading'] ?? old('TOEIC_option'), 'Reading') }}> Reading <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_Listening_and_Reading_readingScore" name="readingScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    @for($item = 5; $item <= 495; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="TOEIC_option[]" value="Listening" {{ checkbox_ticker($purpose_option['TOEIC_Listening'] ??  old('TOEIC_option'), 'Listening') }}> Listening <br/>                                       
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TOEIC_listeningScore" name="listeningScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    @for($item = 5; $item <= 495; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="EIKEN" value="EIKEN" class="main_option" {{ checkbox_ticker( $purpose['EIKEN'] ?? old('EIKEN') , 'EIKEN') }}> EIKEN(英検）
    <div class="EIKEN ml-4 sub_options">
        <input type="checkbox" name="EIKEN_option[]" value="Grade 5" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_5'] ?? old('EIKEN_option'), 'Grade 5') }}> Grade 5 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_5" name="EIKEN_grade_5"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 5 Score</option>
                    @for($item = 0; $item <= 850; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 4" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_4'] ?? old('EIKEN_option'), 'Grade 4') }}> Grade 4 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_4" name="EIKEN_grade_4" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 4 Score</option>
                    @for($item = 0; $item <= 1000; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 3" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_3'] ?? old('EIKEN_option'), 'Grade 3') }}> Grade 3 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_3_1st_stage" name="EIKEN_grade_3_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 3 1st Stage Score</option>
                    @for($item = 0; $item <= 1650; $item++)                     
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 2" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_pre_2'] ?? old('EIKEN_option'), 'Grade pre 2') }}> Grade pre 2 <br/>   
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_pre_2_1st_stage" name="EIKEN_grade_pre_2_1st_stage" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 2 1st Stage Score</option>
                    @for($item = 0; $item <= 1800; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 2" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_2'] ?? old('EIKEN_option'), 'Grade 2') }}> Grade 2 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_2_1st_stage" name="EIKEN_grade_2_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 2 1st Stage Score</option>
                    @for($item = 0; $item <= 1950; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 1" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_pre_1'] ?? old('EIKEN_option'), 'Grade pre 1') }}> Grade pre 1 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_pre_1_1st_stage" name="EIKEN_grade_pre_1_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 1 1st Stage Score</option>
                    @for($item = 0; $item <= 2250; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="EIKEN_option[]" value="Grade 1" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_1'] ?? old('EIKEN_option'), 'Grade 1') }}> Grade 1 <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="EIKEN_grade_1_1st_stage" name="EIKEN_grade_1_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 1 1st Stage Score</option>
                    @for($item = 0; $item <= 2250; $item++)                    
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TEAP" value="TEAP" class="main_option" {{ checkbox_ticker( $purpose['TEAP'] ?? old('TEAP') , 'TEAP') }}> TEAP
    <div class="TEAP ml-4 sub_options">
        <input type="checkbox" name="TEAP_option[]" value="Speaking" {{ checkbox_ticker($purpose_option['TEAP_Speaking'] ??  old('TEAP_option'), 'Speaking') }}> Speaking <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TEAP_speakingScore" name="TEAP_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    @for($item = 20; $item <= 100; $item++) 
                        <option value="{{ $item }}" class="mx-0 px-0">{{ $item  }}</option>                     
                    @endfor
                </select>            
            </div>
        </div>

        <input type="checkbox" name="TEAP_option[]" value="Writing" {{ checkbox_ticker($purpose_option['TEAP_Writing'] ??  old('TEAP_option'), 'Writing') }}> Writing <br/>                                     
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="TEAP_writingScore" name="TEAP_examScore[]"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    @for($item = 20; $item <= 100; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>            
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS" value="BUSINESS" class="main_option" {{ checkbox_ticker( $purpose['BUSINESS'] ?? old('BUSINESS') , 'BUSINESS') }}> Business
    <div class="BUSINESS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_option[]" value="Basic" {{ checkbox_ticker($purpose_option['BUSINESS_Basic'] ?? old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_BasicScore" name="BUSINESS_basicScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Basic Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>            
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_option[]" value="Intermediate" {{ checkbox_ticker($purpose_option['BUSINESS_Intermediate'] ?? old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_IntermediateScore" name="BUSINESS_intermediateScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Intermediate Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_option[]" value="Advance" {{ checkbox_ticker($purpose_option['BUSINESS_Advance'] ?? old('BUSINESS_option'), 'Advance') }}> Advance <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_advanceScore" name="BUSINESS_AdvanceScore" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Advance Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>              
            </div>
        </div>


    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS_CAREERS" value="BUSINESS CAREERS" class="main_option" {{ checkbox_ticker( $purpose['BUSINESS_CAREERS'] ?? old('BUSINESS_CAREERS') , 'BUSINESS CAREERS') }}> Business Careers(職業別英語）
    <div class="BUSINESS_CAREERS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Medicine" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Medicine'] ?? old('BUSINESS_CAREERS_option'), 'Medicine') }}> Medicine <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Medicine" name="BUSINESS_Medicine" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Medicine Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>                
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Nursing" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Nursing'] ?? old('BUSINESS_CAREERS_option'), 'Nursing') }}> Nursing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Nursing" name="BUSINESS_Nursing" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Nursing Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Pharmaceutical" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Pharmaceutical'] ?? old('BUSINESS_CAREERS_option'), 'Pharmaceutical') }}> Pharmaceutical <br/>        
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Pharmaceutical" name="BUSINESS_Pharmaceutical" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Pharmaceutical Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Accounting" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Accounting'] ?? old('BUSINESS_CAREERS_option'), 'Accounting') }}> Accounting <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Accounting Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Accounting" name="BUSINESS_Accounting" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Accounting Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>    

            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Legal Professionals" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Legal_Professionals'] ?? old('BUSINESS_CAREERS_option'), 'Legal Professionals') }}> Legal Professionals <br/>        
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Legal_Professionals" name="BUSINESS_CAREERS_Legal_Professionals" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Legal Professionals Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Finance" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Finance'] ?? old('BUSINESS_CAREERS_option'), 'Finance') }}> Finance <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Finance" name="BUSINESS_CAREERS_Finance" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Finance Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Technology" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Technology'] ?? old('BUSINESS_CAREERS_option'), 'Technology') }}> Technology <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Technology" name="BUSINESS_CAREERS_Technology" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Technology Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Commerce" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Commerce'] ?? old('BUSINESS_CAREERS_option'), 'Commerce') }}> Commerce <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Commerce" name="BUSINESS_CAREERS_Commerce" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Commerce Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Tourism" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Tourism'] ?? old('BUSINESS_CAREERS_option'), 'Tourism') }}> Tourism <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Tourism" name="BUSINESS_CAREERS_Tourism" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Tourism Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>
                        
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Cabin Crew" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Cabin_Crew'] ?? old('BUSINESS_CAREERS_option'), 'Cabin Crew') }}> Cabin Crew <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Cabin_Crew" name="BUSINESS_CAREERS_Cabin_Crew" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Cabin Crew  Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Marketing and Advertising" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Marketing_and_Advertising'] ?? old('BUSINESS_CAREERS_option'), 'Marketing and Advertising') }}> Marketing and Advertising <br/>                                
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Marketing_and_Advertising" name="BUSINESS_CAREERS_Marketing_and_Advertising" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Marketing and Advertising Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="DAILY_CONVERSATION" value="DAILY CONVERSATION" class="main_option" {{ checkbox_ticker( $purpose['DAILY_CONVERSATION'] ?? old('DAILY_CONVERSATION') , 'DAILY CONVERSATION') }}> Daily Conversation
    <div class="DAILY_CONVERSATION ml-4 sub_options">
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Basic" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Basic'] ?? old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="DAILY_CONVERSATION_Basic" name="DAILY_CONVERSATION_Basic" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Daily Conversation Basic Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Intermediate" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Intermediate'] ?? old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="DAILY_CONVERSATION_Intermediate" name="DAILY_CONVERSATION_Intermediate" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Daily Conversation Intermediate Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>                
            </div>
        </div>

        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Advance" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Advance'] ?? old('BUSINESS_option'), 'Advance') }}> Advance <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="DAILY_CONVERSATION_Advance" name="DAILY_CONVERSATION_Advance" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Daily Conversation Advance Score</option>
                    <option value="Beginner" class="mx-0 px-0">Beginner</option>
                    <option value="Intermediate" class="mx-0 px-0">Intermediate</option>
                    <option value="Advance" class="mx-0 px-0">Advance</option>  
                </select>               
            </div>
        </div>


    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="OTHERS" value="OTHERS" class="main_option" {{ checkbox_ticker( $purpose['OTHERS'] ?? old('OTHERS') , 'OTHERS') }}> Others
    <div class="others ml-4 sub_options">
        <input type="text" name="OTHERS_value" value="{{ $purpose_option['OTHERS'] ??  old('OTHERS_value') }}" class="col-md-8 form-control form-control-sm ">  
       
    </div>
</div>



@section('scripts')
@parent
<script type="text/javascript">    
    window.addEventListener('load', function () 
    {
        $('.main_option').each(function(i, obj) {
            if ($(this).is(':checked')) {                
                $(this).next().show();
            } else {
                $(this).next().hide();
            }  
        });    


        $(document).on("click",".main_option",function() 
        {
            if ($(this).is(':checked')) 
            {
                $(this).next().show();                

               let optionsCount = $('.main_option').filter(':checked').length
                if (optionsCount > 3) 
                {                
                    alert ("You can only select 3 purpose");
                    $( this ).prop( "checked", false );
                    let element = $(this).next();
                    $(element).hide();
                    $(element).find('input').prop('checked', false);                            
                }
            } else {
                let element = $(this).next();
                $(element).hide();
                $(element).find('input').prop('checked', false);
            }  
        });

        $(document).on("click",".sub_options input",function() 
        {
            if ($(this).is(':checked')) 
            {            
                $(this).next().next().show();
            } else {
                $(this).next().next().hide();
            }
        });



        $('.sub_options input').each(function(i, obj) {
            //console.log( $(this).val() + " " + $(this)[0].checked);

            if ($(this).is(':checked'))             
            {

                $(this).next().next().show();
            } else {
                $(this).next().next().hide();
            }
        });


    });
</script>
@endsection

@section('styles')
@parent
<style type="text/css">    
    .sub_options {
        display: none;
    }
</style>
@endsection