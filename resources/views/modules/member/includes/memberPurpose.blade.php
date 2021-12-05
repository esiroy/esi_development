<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="IELTS" value="IELTS" class="main_option" {{ checkbox_ticker( $purpose['IELTS'] ?? old('IELTS') , 'IELTS') }}> IELTS    
    <div class="IELTS ml-4 sub_options">
        <input type="checkbox" name="IELTS_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['IELTS_Speaking'] ?? old('IELTS_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="IELTS_option[]" value="Writing" {{ checkbox_ticker( $purpose_option['IELTS_Writing'] ?? old('IELTS_option'), 'Writing') }}> Writing <br/>
        <input type="checkbox" name="IELTS_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['IELTS_Reading'] ?? old('IELTS_option'), 'Reading') }}> Reading <br/>
        <input type="checkbox" name="IELTS_option[]" value="Listening" {{ checkbox_ticker( $purpose_option['IELTS_Listening'] ?? old('IELTS_option'), 'Listening') }}> Listening <br/>
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL" value="TOEFL" class="main_option" {{ checkbox_ticker( $purpose['TOEFL'] ?? old('TOEFL') , 'TOEFL') }}> TOEFL
    <div class="TOEFL ml-4 sub_options">
        <input type="checkbox" name="TOEFL_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['TOEFL_Speaking'] ?? old('TOEFL_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="TOEFL_option[]" value="Writing" {{ checkbox_ticker(  $purpose_option['TOEFL_Writing'] ??  old('TOEFL_option'), 'Writing') }}> Writing <br/>
        <input type="checkbox" name="TOEFL_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['TOEFL_Reading'] ?? old('TOEFL_option'), 'Reading') }}> Reading <br/>
        <input type="checkbox" name="TOEFL_option[]" value="Listening" {{ checkbox_ticker(  $purpose_option['TOEFL_Listening'] ?? old('TOEFL_option'), 'Listening') }}> Listening <br/>                                        
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEFL_Primary" value="TOEFL Primary" {{ checkbox_ticker( $purpose['TOEFL_Primary'] ?? old('TOEFL_Primary') , 'TOEFL Primary') }}> TOEFL Primary
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TOEIC" value="TOEIC" class="main_option" {{ checkbox_ticker( $purpose['TOEIC'] ?? old('TOEIC') , 'TOEIC') }}> TOEIC
    <div class="TOEIC ml-4 sub_options">
        <input type="checkbox" name="TOEIC_option[]" value="Speaking" {{ checkbox_ticker( $purpose_option['TOEIC_Speaking'] ??  old('TOEIC_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="TOEIC_option[]" value="Writing" {{ checkbox_ticker( $purpose_option['TOEIC_Writing'] ?? old('TOEIC_option'), 'Writing') }}> Writing <br/>
        <input type="checkbox" name="TOEIC_option[]" value="Reading" {{ checkbox_ticker( $purpose_option['TOEIC_Reading'] ?? old('TOEIC_option'), 'Reading') }}> Reading <br/>
        <input type="checkbox" name="TOEIC_option[]" value="Listening" {{ checkbox_ticker($purpose_option['TOEIC_Listening'] ??  old('TOEIC_option'), 'Listening') }}> Listening <br/>                                       
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="EIKEN" value="EIKEN" class="main_option" {{ checkbox_ticker( $purpose['EIKEN'] ?? old('EIKEN') , 'EIKEN') }}> EIKEN(英検）
    <div class="EIKEN ml-4 sub_options">
        <input type="checkbox" name="EIKEN_option[]" value="Grade 5" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_5'] ?? old('EIKEN_option'), 'Grade 5') }}> Grade 5 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade 4" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_4'] ?? old('EIKEN_option'), 'Grade 4') }}> Grade 4 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade 3" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_3'] ?? old('EIKEN_option'), 'Grade 3') }}> Grade 3 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 2" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_pre_2'] ?? old('EIKEN_option'), 'Grade pre 2') }}> Grade pre 2 <br/>   
        <input type="checkbox" name="EIKEN_option[]" value="Grade 2" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_2'] ?? old('EIKEN_option'), 'Grade 2') }}> Grade 2 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade pre 1" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_pre_1'] ?? old('EIKEN_option'), 'Grade pre 1') }}> Grade pre 1 <br/>
        <input type="checkbox" name="EIKEN_option[]" value="Grade 1" {{ checkbox_ticker( $purpose_option['EIKEN_Grade_1'] ?? old('EIKEN_option'), 'Grade 1') }}> Grade 1 <br/>
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="TEAP" value="TEAP" class="main_option" {{ checkbox_ticker( $purpose['TEAP'] ?? old('TEAP') , 'TEAP') }}> TEAP
    <div class="TEAP ml-4 sub_options">
        <input type="checkbox" name="TEAP_option[]" value="Speaking" {{ checkbox_ticker($purpose_option['TEAP_Speaking'] ??  old('TEAP_option'), 'Speaking') }}> Speaking <br/>
        <input type="checkbox" name="TEAP_option[]" value="Writing" {{ checkbox_ticker($purpose_option['TEAP_Writing'] ??  old('TEAP_option'), 'Writing') }}> Writing <br/>                                     
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS" value="BUSINESS" class="main_option" {{ checkbox_ticker( $purpose['BUSINESS'] ?? old('BUSINESS') , 'BUSINESS') }}> Business
    <div class="BUSINESS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_option[]" value="Basic" {{ checkbox_ticker($purpose_option['BUSINESS_Basic'] ?? old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <input type="checkbox" name="BUSINESS_option[]" value="Intermediate" {{ checkbox_ticker($purpose_option['BUSINESS_Intermediate'] ?? old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <input type="checkbox" name="BUSINESS_option[]" value="Advance" {{ checkbox_ticker($purpose_option['BUSINESS_Advance'] ?? old('BUSINESS_option'), 'Advance') }}> Advance <br/>

    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS_CAREERS" value="BUSINESS CAREERS" class="main_option" {{ checkbox_ticker( $purpose['BUSINESS_CAREERS'] ?? old('BUSINESS_CAREERS') , 'BUSINESS CAREERS') }}> Business Careers(職業別英語）
    <div class="BUSINESS_CAREERS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Medicine" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Medicine'] ?? old('BUSINESS_CAREERS_option'), 'Medicine') }}> Medicine <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Nursing" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Nursing'] ?? old('BUSINESS_CAREERS_option'), 'Nursing') }}> Nursing <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Pharmaceutical" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Pharmaceutical'] ?? old('BUSINESS_CAREERS_option'), 'Pharmaceutical') }}> Pharmaceutical <br/>        
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Accounting" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Accounting'] ?? old('BUSINESS_CAREERS_option'), 'Accounting') }}> Accounting <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Legal Professionals" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Legal_Professionals'] ?? old('BUSINESS_CAREERS_option'), 'Legal Professionals') }}> Legal Professionals <br/>        
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Finance" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Finance'] ?? old('BUSINESS_CAREERS_option'), 'Finance') }}> Finance <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Technology" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Technology'] ?? old('BUSINESS_CAREERS_option'), 'Technology') }}> Technology <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Commerce" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Commerce'] ?? old('BUSINESS_CAREERS_option'), 'Commerce') }}> Commerce <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Tourism" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Tourism'] ?? old('BUSINESS_CAREERS_option'), 'Tourism') }}> Tourism <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Cabin Crew" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Cabin_Crew'] ?? old('BUSINESS_CAREERS_option'), 'Cabin Crew') }}> Cabin Crew <br/>
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Marketing and Advertising" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Marketing_and_Advertising'] ?? old('BUSINESS_CAREERS_option'), 'Marketing and Advertising') }}> Marketing and Advertising <br/>                                
    </div>
</div>
<!--[end] Checkbox Group -->


<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="DAILY_CONVERSATION" value="DAILY CONVERSATION" class="main_option" {{ checkbox_ticker( $purpose['DAILY_CONVERSATION'] ?? old('DAILY_CONVERSATION') , 'DAILY CONVERSATION') }}> Daily Conversation
    <div class="DAILY_CONVERSATION ml-4 sub_options">
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Basic" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Basic'] ?? old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Intermediate" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Intermediate'] ?? old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Advance" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Advance'] ?? old('BUSINESS_option'), 'Advance') }}> Advance <br/>

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

        $(document).on("click",".main_option",function() {
            if ($(this).is(':checked')) {
                $(this).next().show();
            } else {
                let element = $(this).next();
                $(element).hide();
                $(element).find('input').prop('checked', false);
            }  
        });


        $(document).on("click",".main_option",function() {
            if ($(this).is(':checked')) {
                $(this).next().show();
            } else {
                let element = $(this).next();
                $(element).hide();
                $(element).find('input').prop('checked', false);
            }  
        });

        $(document).on("click",".sub_options input",function() 
        {
            if ($(this).prop('checked') == true)
            {
                console.log("test click sub options")
                let optionsCount = $('.sub_options').find('input').filter(':checked').length

                if (optionsCount > 3) 
                {                
                    alert ("You can only select 3 options");
                    $( this ).prop( "checked", false );
                    return false;                    
                }
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