<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="IELTS" value="IELTS" class="main_option" {{ checkbox_ticker( old('IELTS') , 'IELTS') }}> IELTS    
    <div class="IELTS ml-4 sub_options">
        <input type="checkbox" name="IELTS_option[]" value="Speaking" {{ checkbox_ticker( old('IELTS_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="IELTS_option[]" value="Writing" {{ checkbox_ticker( old('IELTS_option'), 'Writing') }}> Writing <br/>
        <input type="checkbox" name="IELTS_option[]" value="Reading" {{ checkbox_ticker( old('IELTS_option'), 'Reading') }}> Reading <br/>
        <input type="checkbox" name="IELTS_option[]" value="Listening" {{ checkbox_ticker( old('IELTS_option'), 'Listening') }}> Listening <br/>
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL" value="TOEFL" class="main_option" {{ checkbox_ticker( old('TOEFL') , 'TOEFL') }}> TOEFL
    <div class="TOEFL ml-4 sub_options">
        <input type="checkbox" name="TOEFL_option[]" value="Speaking" {{ checkbox_ticker( old('TOEFL_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="TOEFL_option[]" value="Writing" {{ checkbox_ticker( old('TOEFL_option'), 'Writing') }}> Writing <br/>
        <input type="checkbox" name="TOEFL_option[]" value="Reading" {{ checkbox_ticker( old('TOEFL_option'), 'Reading') }}> Reading <br/>
        <input type="checkbox" name="TOEFL_option[]" value="Listening" {{ checkbox_ticker( old('TOEFL_option'), 'Listening') }}> Listening <br/>                                        
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL_Primary" value="TOEFL Primary" {{ checkbox_ticker( old('TOEFL_Primary') , 'TOEFL Primary') }}> TOEFL Primary
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEIC" value="TOEIC" class="main_option" {{ checkbox_ticker( old('TOEIC') , 'TOEIC') }}> TOEIC
    <div class="TOEIC ml-4 sub_options">
        <input type="checkbox" name="TOEIC_option[]" value="Speaking" {{ checkbox_ticker( old('TOEIC_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="TOEIC_option[]" value="Writing" {{ checkbox_ticker( old('TOEIC_option'), 'Writing') }}> Writing <br/>
        <input type="checkbox" name="TOEIC_option[]" value="Reading" {{ checkbox_ticker( old('TOEIC_option'), 'Reading') }}> Reading <br/>
        <input type="checkbox" name="TOEIC_option[]" value="Listening" {{ checkbox_ticker( old('TOEIC_option'), 'Listening') }}> Listening <br/>                                       
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="EIKEN" value="EIKEN" class="main_option" {{ checkbox_ticker( old('EIKEN') , 'EIKEN') }}> EIKEN(英検）
    <div class="EIKEN ml-4 sub_options">
        <input type="checkbox" name="EIKEN_option[]" value="Grade 5" {{ checkbox_ticker( old('EIKEN_option'), 'Grade 5') }}> Grade 5 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade 4" {{ checkbox_ticker( old('EIKEN_option'), 'Grade 4') }}> Grade 4 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade 3" {{ checkbox_ticker( old('EIKEN_option'), 'Grade 3') }}> Grade 3 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 2" {{ checkbox_ticker( old('EIKEN_option'), 'Grade pre 2') }}> Grade pre 2 <br/>   
        <input type="checkbox" name="EIKEN_option[]" value="Grade 2" {{ checkbox_ticker( old('EIKEN_option'), 'Grade 2') }}> Grade 2 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 1" {{ checkbox_ticker( old('EIKEN_option'), 'Grade pre 1') }}> Grade pre 1 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade 1" {{ checkbox_ticker( old('EIKEN_option'), 'Grade 1') }}> Grade 1 <br/>
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TEAP" value="TEAP" class="main_option" {{ checkbox_ticker( old('TEAP') , 'TEAP') }}> TEAP
    <div class="TEAP ml-4 sub_options">
        <input type="checkbox" name="TEAP_option[]" value="Speaking" {{ checkbox_ticker( old('TEAP_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="TEAP_option[]" value="Writing" {{ checkbox_ticker( old('TEAP_option'), 'Writing') }}> Writing <br/>                                     
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS" value="BUSINESS" class="main_option" {{ checkbox_ticker( old('BUSINESS') , 'BUSINESS') }}> Business
    <div class="BUSINESS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_option[]" value="Basic" {{ checkbox_ticker( old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <input type="checkbox" name="BUSINESS_option[]" value="Intermediate" {{ checkbox_ticker( old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <input type="checkbox" name="BUSINESS_option[]" value="Advance" {{ checkbox_ticker( old('BUSINESS_option'), 'Advance') }}> Advance <br/>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS_CAREERS" value="BUSINESS CAREERS" class="main_option" {{ checkbox_ticker( old('BUSINESS_CAREERS') , 'BUSINESS CAREERS') }}> Business Careers(職業別英語）
    <div class="BUSINESS_CAREERS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Medicine" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Medicine') }}> Medicine <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Nursing" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Nursing') }}> Nursing <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Pharmaceutical" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Pharmaceutical') }}> Pharmaceutical <br/>        
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Accounting" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Accounting') }}> Accounting <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Legal Professionals" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Legal Professionals') }}> Legal Professionals <br/>        
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Finance" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Finance') }}> Finance <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Technology" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Technology') }}> Technology <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Commerce" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Commerce') }}> Commerce <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Tourism" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Tourism') }}> Tourism <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Cabin Crew" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Cabin Crew') }}> Cabin Crew <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Marketing and Advertising" {{ checkbox_ticker( old('BUSINESS_CAREERS_option'), 'Marketing and Advertising') }}> Marketing and Advertising <br/>                                
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="DAILY_CONVERSATION" value="DAILY CONVERSATION" class="main_option" {{ checkbox_ticker( old('DAILY_CONVERSATION') , 'DAILY CONVERSATION') }}> Daily Conversation
    <div class="DAILY_CONVERSATION ml-4 sub_options">
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Basic" {{ checkbox_ticker( old('DAILY_CONVERSATION_option'), 'Basic') }}> Basic <br/>
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Intermediate" {{ checkbox_ticker( old('DAILY_CONVERSATION_option'), 'Intermediate') }}> Intermediate <br/>
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Advance" {{ checkbox_ticker( old('DAILY_CONVERSATION_option'), 'Advance') }}> Advance <br/>
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="OTHERS" value="OTHERS" class="main_option" {{ checkbox_ticker( old('OTHERS') , 'OTHERS') }}> Others
    <div class="others ml-4 sub_options">
        <input type="text" name="OTHERS_value" value="{{ old('OTHERS_value') }}" class="col-md-8 form-control form-control-sm ">  
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

        $('.main_option').on('click', function()
        {
            if ($(this).is(':checked')) {
                $(this).next().show();
            } else {
                $(this).next().hide();
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