@extends('layouts.writing.template')

@section('content')

    <div class="row">
        <div class="col-12">
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
    </div>
            
    <form id="writing-form" method="POST" enctype="multipart/form-data" action="{{ route('writingSaveEntry.store', ['form_id' => $form_id  ]) }}" class="form-horizontal" style="display:none">
        @csrf
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
         <textarea id="data" name="data" style="display:none" ></textarea>
        <input type="submit" style="display:none">
    </form>
@endsection

@section('styles')
    @parent
    <style>
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
    <script src="{{ url('js/steps/jquery.steps.min.js') }}" defer></script>
    <script src="{{ url('js/validation/jquery.validation.min.js') }}" defer></script>
    <script>
        window.addEventListener('load', function() 
        {
            $('#writing-form').show(300);

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
                        console.log( id + " : "  + $(field).attr('name') + " " + $(field).val() );
                        fieldsArr[name] = value;
                    }

                    if (itemsProcessed == inputs.length) {
                        console.log(fieldsArr);
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
                        console.log(fieldID + " is hidden, we will not verify");
                    } else {
                        if ($('#'+fieldID).attr( "required" )) 
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
                            const oFile = document.getElementById(fieldID).files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
                            if (oFile.size <= 2097152) // 2 MiB for bytes.
                            {
                                //less than 2mb (its okay)
                                
                                $('.'+fieldID+"_field_content").find('.error2').remove();                                
                            } else {

                                colorHighlight(fieldID)

                                $('.'+fieldID+"_field_content").find('.error2').remove();
                                $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 label-error" for="'+fieldID+'" >This File Size exceeds 2MB.</label>');
                                requiredFieldsArr.push({
                                    'id': fieldID,
                                    'isValid': false
                                });
                                //alert("File size must under 2MiB!");
                                //return;
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
                    console.log("Go to step next page")
                    return true;
                } else{
                    console.log("stay on current page")
                    return false;
                }                
            }
            
            function highlightFieldRow(fieldID) 
            {
                if ($('#'+fieldID+"_field_row").css( "display" ) == 'none' ) {                    
                    console.log(fieldID + " is hidden, we will not highlight");
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
                            console.log("error in email");                            
                        }
                    }
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
                            return true;
                        }

                    },
                    // Triggered when clicking the Finish button
                    onFinishing: function(e, currentIndex) 
                    {
                        let isValid = validateFields(currentIndex);
                        if (isValid === "true" || isValid === false || isValid === null ) 
                        {                          
                            console.log("not valid field detected");
                        } else {         
                            encodeData();
                            $('#writing-form').find('[type="submit"]').trigger('click');
                        }                        
                    },
                    onFinished: function(e, currentIndex) {                     
                        // Uncomment the following line to submit the form using the defaultSubmit() method
                        // $('#writing-form').formValidation('defaultSubmit');
                    }
                });

            @php
                $cfLogic = new \App\Models\ConditionalFieldLogic;
                $items = $cfLogic->where('form_id', $form_id)->groupby(['selected_option_id'])->get();
            @endphp

            @foreach ($items as $item) 

                $('{{ '#' . $item->selected_option_id }}').on('change keyup blur', function() 
                {
                    //let cflogic = $(document).find('.cfLogic');

                    @php 
                        $showFields = $cfLogic->where('form_id', $form_id)->where('selected_option_id', $item->selected_option_id)->groupby(['field_id'])->get();
                    @endphp

                     @foreach ($showFields as $field)
                        //"{{ $field->field_id }}"
                        //@todo: search if conditional logic is activated
                        @php 
                            $writingModel = new \App\Models\WritingFields;
                            $writingField = $writingModel->where('form_id', $form_id)->where('id', $field->field_id)->first();

                            $displayMeta = json_decode($writingField->display_meta, true);
                            $conditionalLogic = $displayMeta['conditional_logic'];
                        @endphp            

                        @if ($conditionalLogic == true)

                            //@todo: value condtionals rule logic
                            @php 
                                $conditionals = $cfLogic->where('form_id', $form_id)->where('field_id', $field->field_id)->get();
                                $fieldCtr = 0;
                            @endphp

                            if (@foreach($conditionals as $conditional) @php $fieldCtr++; @endphp isLogicTrue($('#{{$item->selected_option_id}}').val(), "{{ $conditional->field_rule}}", "{{$conditional->field_value}}") @if ( $fieldCtr < count($conditionals)) || @endif @endforeach)
                            {
                                
                                $('{{ '#' . $field->field_id }}_field_row').show();
                            } else {
                                 $('{{ '#' . $field->field_id }}_field_row').hide();
                            }
                        @endif
                     @endforeach
                });
                $('{{ '#' . $item->selected_option_id }}').trigger('change', 'keypress');
            @endforeach
        });
    </script>
@endsection