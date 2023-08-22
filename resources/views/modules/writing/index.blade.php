@extends('layouts.esi-app')

@section('content')

@php
    $attribute = Auth::user()->memberInfo->attribute;
@endphp

<div class="container bg-light">
    <div class="esi-box mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Writing</li>
            </ol>
        </nav>

        <div class="container pb-5">
            <div class="row">
            
                <!--{{ "mail address" . Config::get('mail.from.address') }}-->

                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->           

            

                @if (strtolower($attribute) == 'trial') 

                    <div class="col-md-9">
                        <div class="row">

   							@include('modules.member.includes.ecommerce.buycredits')

                        </div>
                    </div>

                @else 
                    <div class="col-md-9">
                        <div class="row">

                            <div class="col-12  message-container">
                                @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                                @elseif (session('error_message'))
                                <div class="alert alert-danger">
                                    {{ session('error_message') }}
                                </div>
                                @endif
                            </div>
                            
                                    
                            <div class="col-12">
                                <div class="card esi-card mb-3">
                                    <div class="card-header esi-card-header py-2">
                                        Writing Service
                                    
                                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/tensaku.html', 'tensaku', 980, 720);" class="small ml-4">「添削くん」ご利用方法 </a>
                                    </div>
                                    <div class="card-body">
                                        
                                        <form id="writing-form" method="POST" enctype="multipart/form-data" action="{{ route('writingSaveEntry.store', ['form_id' => $form_id  ]) }}" class="form-horizontal" style="display:none">
                                            @csrf
                                            @method('POST')


                                            @foreach($pages as $page) 
                                                <h2>{{ $page->page_id }}</h2>
                                                <section data-step="{{ $page->page_id }}">
                                                    @if(isset($formFieldChildrenHTML[$page->page_id]))
                                                        @foreach($formFieldChildrenHTML[$page->page_id] as $formFieldChildHTML) 
                                                            {!! $formFieldChildHTML !!}
                                                        @endforeach
                                                    @endif
                                                    @if( $page->page_id == 1 )
                                                        @foreach($formFieldHTML as $HTML) 
                                                            {!! $HTML !!}
                                                        @endforeach
                                                    @endif
                                                </section>
                                            @endforeach

                                            <div class="warnings">
                                                <div class="alert alert-danger mx-4" role="alert">
                                                    You already consumed all your credits
                                                </div>
                                            </div>

                                            <textarea id="data" name="data"></textarea>
                                            <input id="send" type="submit">
                                         
                                        </form>                              
                                    </div>
                                </div>                  
                            </div>
                            
                        </div>
                    </div>
                @endif
            </div>
        </div>  


    </div>
</div>
@endsection

@section('styles')
    @parent
      
    <link rel="stylesheet" href="{{ asset('css/steps/steps.css') }}">
    <style>

        
        .cke_top, .cke_bottom {
            display: none !important;
        }

        .wizard>.content>.body {
            width: 100%
        }

        .wrapper {
            margin: 50px auto;
            max-width: 750px;
        }
        .writing-header {
            margin-bottom: 30px;
            font-size: 25px;
            border-top: 3px solid #00AFEF;
            margin-top: 20px;
            padding-top: 20px;
        }


        .wizard>.content>.body label.error {
            font-weight: bold;    
            margin-left: 0px !important;    
        }

        .steps {
            display: none !important
        }
        .label-error {
            color: #8a1f11 !important;
            font-weight: bold
        }

        #writing-form img {
            width: 100%
        }        
    </style>
@endsection

@section('scripts')
    @parent
    <script src="{{ url('js/steps/jquery.steps.min.js') }}" defer></script>
    <script src="{{ url('js/validation/jquery.validation.min.js') }}" defer></script>
     <script src="{{ url('js/ckeditor/ckeditor.js')  }}"></script>
    <script>

        //enumate fields that needs checking
        let fieldsArray = new Array();

        window.addEventListener('load', function() 
        {

            getMemberCredit();

            $('#writing-form').show();

            $('.message-container').find('.alert').delay(5000).fadeOut('slow');
            

            $(document).on("change paste keypress keyup keydown",".paragraphText",function() 
            {
                var fieldID = $(this).attr('id');                            
                var isWordLimiterEnabled = $("#"+ fieldID + "_enableWordLimit").val();
                var wordlimit = $('#'+fieldID+"_wordLimit").val();
                
                var words = $("#"+ $(this).attr('id')).val()

                
                if (isWordLimiterEnabled == true ) {
                    let wordcount = countWords(words);
                    $("#"+ fieldID +"_total_word_count").text(wordcount);                   
                    if (wordcount > wordlimit) 
                    {                       
                        $('.'+fieldID+"_field_content").find('.error2').remove();
                        $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 bg-danger text-white p-1 float-right" for="'+fieldID+'" >You have exceeded the maximum word limit.</label>');
                    } else {
                         $('.'+fieldID+"_field_content").find('.error2').remove();
                    }
                }                             
            }).on("paste", function (element) {

                setTimeout(function () {
                    let fieldID = element.target.id;
                    var isWordLimiterEnabled = $("#"+ fieldID + "_enableWordLimit").val();
                    var wordlimit = $('#'+fieldID+"_wordLimit").val();

                    let words = element.target.value

                    if (isWordLimiterEnabled == true ) {
                        let wordcount = countWords(words);
                        $("#"+ fieldID +"_total_word_count").text(wordcount);                   
                        if (wordcount > wordlimit) 
                        {                       
                            $('.'+fieldID+"_field_content").find('.error2').remove();
                            $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 bg-danger text-white p-1 float-right" for="'+fieldID+'" >You have exceeded the maximum word limit.</label>');
                        } else {
                            $('.'+fieldID+"_field_content").find('.error2').remove();
                        }
                    }
                }, 100);
              
            });
  
           

            // IMPORTANT: You must call .steps() before calling .formValidation()
            $('#writing-form')
                .steps({
                    headerTag: 'h2',
                    bodyTag: 'section',
                    onStepChanged: function(e, currentIndex, priorIndex) {
                        // You don't need to care about it
                        // It is for the specific demo
                        //alert ("test page" + currentIndex + " - prior Index " + priorIndex)
                        adjustIframeHeight();
                        return true;
                    },
                    // Triggered when clicking the Previous/Next buttons
                    onStepChanging: function(e, currentIndex, newIndex) 
                    {
                        //Validate only when next step, you are free to go back anytime
                        if (currentIndex < newIndex)
                        {
                            //alert(currentIndex + " " + newIndex)                            
                            let isValid = validateFields(currentIndex);
                            return isValid;                            
                        } else {
                            //console.log("User")
                            console.log("clicked previous")

                            //checked if 1 so we can reset
                            if (currentIndex == 1) {
                                console.log("we need to reset");

                                //$('.cfLogic').hide(); (bug this will hide when clicking previos)
                            }

                            return true;
                        }

                    },
                    // Triggered when clicking the Finish button
                    onFinishing: function(e, currentIndex) 
                    {
                        let checkID = null;

                        let requiredFieldsArr = new Array();

                        let isValid = validateFields(currentIndex);
                        if (isValid === "true" || isValid === false || isValid === null ) 
                        {                          
                            //console.log("not valid field detected");

                        } else {         

                            encodeData(); 

                            let inputs = $("#writing-form").find('.form-control');                          
                            let loopcounter = 0;

                            let isMemberPointEnabled = false;
                            let tutorSelectFieldID =  $("[name='appoint_teacher_field_id']").val();


                            Array.from(inputs).forEach(field => 
                            {
                                loopcounter = loopcounter + 1;
                                let fieldID =  $(field).attr('id');
                                highlightFieldRow(fieldID);
                              

                                if ($('#'+fieldID).hasClass('paragraphText')) 
                                {
                                    var memberPointCheckerEnabled = $('#'+fieldID+"_memberPointChecker").val();   

                                    if (memberPointCheckerEnabled == true) 
                                    {
                                        //is point checker found
                                        if (isMemberPointEnabled == false) {
                                            isMemberPointEnabled = true;
                                        }

                                        if( $("#"+ fieldID +"_field_row").css('display') == 'none') {
                                            // do not do anything,
                                        } else {
                                            checkID = fieldID;
                                        }
                                    } 
                                }                              
                                

                               //if there point enabled when loop counter is finished looping through all inputs?
                                if (inputs.length == loopcounter) {                                
                                    if (isMemberPointEnabled === false) {   
                                        //$('#writing-form').find('[type="submit"]').trigger('click');
                                        submitFromEntry();
                                    } else {
                                        if  (checkID === false) {
                                            //$('#writing-form').find('[type="submit"]').trigger('click'); 
                                            submitFormEntry();
                                        } else {
                                            checkCredits(checkID)
                                        }
                                    }
                                }
                            });
                        }                        
                    },
                    onFinished: function(e, currentIndex) {                     
                        // Uncomment the following line to submit the form using the defaultSubmit() method
                        // $('#writing-form').formValidation('defaultSubmit');

                        console.log("finished")
                    }
                });

            @php
                $cfLogic = new \App\Models\ConditionalFieldLogic;
                $items = $cfLogic->where('form_id', $form_id)->groupby(['selected_option_id'])->get();
            @endphp

            @foreach ($items as $item)

                //reset on the first page when hitting options
                $('#writing-form-p-0 {{ '#' . $item->selected_option_id }}').on('change', function() {
                     $('.cfLogic').hide();
                });


                $('{{ '#' . $item->selected_option_id }}').on('change keyup blur', function() {
                    @php 
                        $showFields = $cfLogic->where('form_id', $form_id)->where('selected_option_id', $item->selected_option_id)->groupby(['field_id'])->get();
                    @endphp

                     @foreach ($showFields as $field)
                        @php 
                            $writingModel = new \App\Models\WritingFields;
                            $writingField = $writingModel->where('form_id', $form_id)->where('id', $field->field_id)->first();
                            try {
                                $displayMeta = json_decode($writingField->display_meta, true);
                                $conditionalLogic = $displayMeta['conditional_logic'];
                            } catch (\Exception $e) {
                                $conditionalLogic = false;
                            }
                        @endphp            

                        @if ($conditionalLogic == true)                           
                            @php 
                                $conditionals = $cfLogic->where('form_id', $form_id)->where('field_id', $field->field_id)->get();
                                $fieldCtr = 0;
                            @endphp
                            if (@foreach($conditionals as $conditional) @php $fieldCtr++; @endphp isLogicTrue($('#{{$item->selected_option_id}}').val(), "{{ $conditional->field_rule}}", "{{$conditional->field_value}}") @if ( $fieldCtr < count($conditionals)) || @endif @endforeach)
                            {                                
                                $('{{ '#' . $field->field_id }}_field_row').show();
                                @if( strtolower($writingField->type) == "htmlcontent") getHTMLContent(1, "{{ $field->field_id }}") @endif

                               

                            } else {
                                $('{{ '#' . $field->field_id }}_field_row').hide();
                                @if( strtolower($writingField->type) == "htmlcontent")
                                     removeHTMLContent(1, "{{ $field->field_id }}") 
                                @else 
                                    //unselect
                                    $('{{ '#' . $field->field_id }}').val('');      
                                    //$('{{ '#' . $field->field_id }}').trigger('change');
                                @endif
                            }
                        @endif
                     @endforeach
                });
                $('{{ '#' . $item->selected_option_id }}').trigger('change', 'keypress');

      
            @endforeach




            //ADD TEXTY CKEDITOR FORMATTER (ADDED SEPTEMBER 12, 2022)
            $('.ckEditor').each( function () {
                //_paragraphTextfield
                addTextFormatter(this.id);
            });



        });//[end] loader


        function getMemberCredit() 
        {   
            $.ajax({
                type: 'POST',
                url: "{{ url('api/getMemberCredit?api_token=') }}" + api_token,
                data: { 
                    memberID      :  {{ Auth::user()->id }},         
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },                    
                success: function(credits) {      
                    console.log(credits)

                    if (credits >= 1) {
                        $('#writing-form .actions').show();
                        $('#writing-form .warnings').hide();
                    } else {
                        $('#writing-form .actions').hide();
                    }

                   
                },
                error: function(err) {
                    //reject(err) // Reject the promise and go to catch()
                }
            });
        }

        function addTextFormatter(fieldID) 
        {

            CKEDITOR.replace( fieldID , {
                //toolbar: ['removeFormat'],             
                removePlugins: 'easyimage, exportpdf, cloudservices',

            }).on('change', (evt) => {

                
                let htmlData = evt.editor.getData()
                let  words = $(htmlData).text()           
            
                let wordcount = countWords(words);
                var wordlimit = $('#'+fieldID+"_wordLimit").val();

                //Update Textarea ELement 
                evt.editor.updateElement();


                console.log(fieldID + " wordlimit " + wordlimit)
                console.log(fieldID + " words " + wordcount)

                $("#"+ fieldID +"_total_word_count").text(wordcount);                   

                if (wordcount > wordlimit) 
                {                       
                    $('.'+fieldID+"_field_content").find('.error2').remove();
                    $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 bg-danger text-white p-1 float-right" for="'+fieldID+'" >You have exceeded the maximum word limit.</label>');
                } else {

                     $('.'+fieldID+"_field_content").find('.error2').remove();
                }

            });          
        }

        function checkCredits(fieldID) 
        { 
            let wordCount = 0;      
            //attachment (automatically the words is just 1, so it will deduct 1)    
            if  (fieldID == null) {
                wordCount = 1;                    
                ajaxGetCredit(wordCount);
            } else {
                wordCount = countWords ($('#'+fieldID).val());
                ajaxGetCredit(wordCount);
            }
        }

        function ajaxGetCredit(wordCount) 
        {            
            let tutorSelectFieldID =  $("[name='appoint_teacher_field_id']").val();    
            $.ajax({
                type: 'POST',
                url: "{{ url('api/writing/checkCredits?api_token=') }}" + api_token,
                data: {
                    formID      :  1,
                    tutorID     :  $('#'+ tutorSelectFieldID).val(),
                    words       :  wordCount,                                                
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) 
                {
                    if (data.totalPointsLeft < 0) 
                    {                                                  
                        $('.message-container').html('<div class="alert alert-danger">' + data.message +'</div>');                                                   
                    } else {
                        //$('#writing-form').find('[type="submit"]').trigger('click');                                                     
                        submitFormEntry();
                    } 
                }
            }); 
        } 

        function submitFormEntry() {
            var hiddenInputElements = document.querySelectorAll('input[style*="display: none;"]');


            // Loop through the selected elements and remove them
            hiddenInputElements.forEach(function(inputElement) {
                inputElement.remove();
            });

            $('#writing-form').submit()
        }


        /* Count point to deduct: 180words = 1 point, 181-500words = 2point, 501-800words = 3point */
        function getDeduction(words) 
        {              
            if  (parseInt(words) >= 1 && parseInt(words) <= 180)  {
                return 1;                        
            } else if (parseInt(words) >= 181 && parseInt(words) <= 500) {
                return 2;
            } else if (parseInt(words) >= 501 && parseInt(words) <= 800) {
                return 3;
            } else {
                return false;
            } 
        }


        function adjustIframeHeight() {
            var $body = $('body'), $iframe = $body.data('iframe.fv');
            if ($iframe) {
                // Adjust the height of iframe
                $iframe.height($body.height());
            }
        }

        function encodeData() 
        {

          

            let inputs = $('#writing-form').find('.form-control');
            let fieldsArr = new Object;

            var itemsProcessed = 0;

            Array.from(inputs).forEach( (field, index) => 
            {
                

                itemsProcessed++;
                let id = $(field).attr('id') 
                let name = $(field).attr('name');
                let value = $(field).val();

           
                

                if ($('#'+id+"_field_row").css( "display" ) == 'none' ) {                        
                    //field is not showed, we will not include it to post
                } else {                        
                    //console.log( id + " : "  + $(field).attr('name') + " " + $(field).val() );
                    fieldsArr[name] = value;
                }

                if (itemsProcessed == inputs.length) {
                    //console.log(fieldsArr);
                    convertToJSON(fieldsArr);
                }
            });     

        };
        

        function convertToJSON(data) {
            let JSON_data = JSON.stringify(data);
            $('#data').text(JSON_data);
            return 
        }

        function validateFields(currentIndex) 
        {
            //clean array
            // fieldsArray = [];

            let inputs = $("#writing-form-p-"+currentIndex).find('.form-control');
            let requiredFieldsArr = [];

            Array.from(inputs).forEach(field => 
            {
                let fieldID =  $(field).attr('id');                                 
                highlightFieldRow(fieldID);

                $('#'+fieldID+"_field_row").on('keyup change', function() 
                {
                    highlightFieldRow(fieldID)                                     
                });
                
                
                //check all has required in Array
                if ($('#'+fieldID+"_field_row").css( "display" ) == 'none' ) {                        
                    //console.log(fieldID + " is hidden, we will not verify");

                } else {

                    if ($('#'+fieldID).attr("required")) 
                    {
                        //console.log(fieldID + " is required")
                        let isValid = $('#'+fieldID).valid();
                        requiredFieldsArr.push({
                            'id': fieldID,
                            'isValid': isValid
                        });
                    }

                    //check if field is an email field and validate manually (since we cant validate it in section)
                    if ($('#'+fieldID).hasClass('emailfield')) 
                    {                            
                        var email = $('#'+fieldID).val();
                        if(email.match(/([+\w0-9._-]+@[\w0-9._-]+\.[\w0-9_-]+)/)) {
                            // valid email
                        } else {
                            requiredFieldsArr.push({
                                'id': fieldID,
                                'isValid': false
                            });                                                                           
                        }
                    }

                    if ($('#'+fieldID).hasClass('uploadfield')) 
                    {
                        try {

                            const oFile = document.getElementById(fieldID).files[0];

                            if (oFile || $('#'+fieldID).attr("required")) {


                                let fileExtension = ['pdf', 'doc', 'docx', 'jpeg', 'jpg', 'png'];                                

                                if ($.inArray($('#'+fieldID).val().split('.').pop().toLowerCase(), fileExtension) == -1) 
                                {

                                    console.log(fileExtension, " the file extension")

                                    let message = "Only formats are allowed : "+fileExtension.join(', ');
                                    colorHighlight(fieldID)
                                    $('.'+fieldID+"_field_content").find('.error2').remove();
                                    $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 label-error" for="'+fieldID+'" >' + message +'.</label>');


                                    let isValid = $('#'+fieldID).valid();
                                    
                                    requiredFieldsArr.push({
                                        'id': fieldID,
                                        'isValid': false
                                    });
                                } 
                                else if (oFile.size <= 2097152) // 2 MiB for bytes.
                                {
                                    //console.log(fileExtension, " the file extension 2")
                                    $('.'+fieldID+"_field_content").find('.error2').remove();    

                                } else {

                                    colorHighlight(fieldID)

                                    $('.'+fieldID+"_field_content").find('.error2').remove();
                                    $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 label-error" for="'+fieldID+'" >This File Size exceeds 2MB.</label>');

                                    let isValid = $('#'+fieldID).valid();

                                    requiredFieldsArr.push({
                                        'id': fieldID,
                                        'isValid': isValid
                                    });
                                }  

                            } else {

                                $('#'+fieldID+"_field_row").removeAttr("style");
                            }
                        } catch(err) {
                            //alert( err )
                        }

                    }
                }

            });

            let goToNextStep = true;
            Array.from(requiredFieldsArr).forEach(requiredField => {
                if (requiredField.isValid === false || requiredField.isValid === null) {
                    goToNextStep = false;
                    return false;
                }
            });                        

            if (goToNextStep == true) {
                //console.log("Go to step next page")
                return true;
            } else{
                //console.log("stay on current page")
                return false;
            }                
        }
            
        function highlightFieldRow(fieldID) 
        {

            console.log("highlight : " + fieldID);

            if ($('#'+fieldID+"_field_row").css( "display" ) == 'none' ) {                    
                //console.log(fieldID + " is hidden, we will not highlight");
            } else {

                if ($('#'+fieldID).attr( "required" )) 
                {
                    let isValid = $('#'+fieldID).valid();

                    if (isValid === false || isValid === null) {
                        //console.log(fieldID +"_field_row is invalid")
                        colorHighlight(fieldID)

                    } else {
                        $('.'+fieldID+"_field_content").find('label.form-label').removeClass('label-error')
                        $('#'+fieldID+"_field_row").removeAttr("style");
                    }
                }

                if ($('#'+fieldID).hasClass('emailfield')) {
                    var email = $('#'+fieldID).val();
                    if(email.match(/([+\w0-9._-]+@[\w0-9._-]+\.[\w0-9_-]+)/)) {
                        // valid email
                        $('.'+fieldID+"_field_content").find('.error2').remove();
                    }
                    else 
                    {
                            //console.log(fieldID +"_field_row is invalid")
                        colorHighlight(fieldID)

                        $('.'+fieldID+"_field_content").find('.error2').remove();
                        $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 label-error" for="'+fieldID+'" >This field only accepts E-Mail Address.</label>');
                        //console.log("error in email");                            
                    }
                }
                    
                if ($('#'+fieldID).hasClass('uploadfield')) {

                    try {
                        const oFile = document.getElementById(fieldID).files[0];

                        if (oFile) {

                            // 2 MiB for bytes.
                            if (oFile.size <= 2097152) {

                                $('.'+fieldID+"_field_content").find('.error2').remove();
                                $('.'+fieldID+"_field_content").find('label.form-label').removeClass('label-error')
                                $('#'+fieldID+"_field_row").removeAttr("style");         
                            }                   
                        }
                
                    } catch(err) {
                        //alert( err )
                    }                    
                }

                    /*
                    if ($('#'+fieldID).hasClass('paragraphText')) 
                    {
                    var isWordLimitEnabled = $('#'+fieldID+"_enableWordLimit").val();
                    var limit = $('#'+fieldID+"_wordLimit").val();

                    if (isWordLimitEnabled == true) 
                    {
                        let wordcounter = countWords ($('#'+fieldID).val());

                        if (wordcounter > limit) {
                            
                                colorHighlight(fieldID)
                            $('.'+fieldID+"_field_content").find('.error2').remove();
                            $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 label-error" for="'+fieldID+'" >You have exceeded the maximum word limit.</label>');
                        } else {
                            $('.'+fieldID+"_field_content").find('.error2').remove();
                        }
                    }
                }
                */

            }
        }


        function colorHighlight(fieldID) {
            $('.'+fieldID+"_field_content").find('label.form-label').addClass('label-error')
            $('#'+fieldID+"_field_row").css({
                'background-color': 'rgba(255,223,224,.25)',
                'margin-bottom': '6px!important',
                'border-top': '1px solid #C89797',
                'border-bottom': '1px solid #C89797',
                'padding-bottom': '6px',
                'padding-top': '8px',
                'margin-top': '16px',
                'margin-bottom': '16px',
                'box-sizing': 'border-box',
            });                
        }


        function getHTMLContent(formID, FieldID) 
        {
            //Get the html field content
            $.ajax({
                type: 'POST',
                url: "{{ url('api/getHTMLFieldContent?api_token=') }}" + api_token,
                data: {
                    formID              :  1,
                    field_id            :  FieldID,                                                           
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) 
                {
                    $('.'+ FieldID +'_field_content').html(data.content);   
                    $('#'+ FieldID +'_field_row').show();  
                    $('.'+ FieldID +'_field_content').find('#'+ FieldID).val(data.content);                    
                }
            });                
        }


        function removeHTMLContent(formID, FieldID) 
        {            
            if ( $('.'+ FieldID +'_field_content').hasClass('htmlContentField') ) {
                $('.'+ FieldID +'_field_content').html("");   
                $('#'+ FieldID +'_field_row').hide();  
                $('.'+ FieldID +'_field_content').find('#'+ FieldID).val("");                  
            }
        
        }

        function isLogicTrue(formFieldValue, rule, recordFeldValue) 
        {
            if (rule == "is") 
            {
                if (eval (formFieldValue == recordFeldValue) ) {
                    return true
                }
            }
            else if (rule == "isnot") 
            {
                if (formFieldValue !== recordFeldValue) {
                    return true
                }
            } else if (rule == "contains") {

                if (formFieldValue.includes(recordFeldValue)) {
                    return true
                }

            } else {
                return false;
            }

        }
          
        function countWords(text) 
        {             
            if (text.length > 0) {
                return text.trim().split(/\s+/).length;
            } else {
                return 0;
            }
            
        }   


    
    


        //end script

    </script>
@endsection