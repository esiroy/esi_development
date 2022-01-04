@php 
    $levels = ['Beginner', 'Intermediate', 'Advance'];
@endphp

@include('modules/member/includes/targetScores/IELTS')
@include('modules/member/includes/targetScores/TOEFL')
@include('modules/member/includes/targetScores/TOEFL_Primary')
@include('modules/member/includes/targetScores/TOEIC')
@include('modules/member/includes/targetScores/EIKEN')
@include('modules/member/includes/targetScores/TEAP')
@include('modules/member/includes/targetScores/BUSINESS')
@include('modules/member/includes/targetScores/BUSINESS_Careers')
@include('modules/member/includes/targetScores/DAILY_Conversation')

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
                
                //FORCE RESET TARGET SCORE
                $(element).find('select > option').removeAttr("selected");
                $(element).find('select').val(''); //force reset!
                $(element).find('select').parent().parent().hide();                

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