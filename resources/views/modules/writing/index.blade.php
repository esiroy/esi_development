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
                            if(email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                                // valid email
                            } else {
                                requiredFieldsArr.push({
                                    'id': fieldID,
                                    'isValid': false
                                });                                                                           
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
                            console.log(fieldID +"_field_row is invalid")
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
                        } else {
                            $('.'+fieldID+"_field_content").find('label.form-label').removeClass('label-error')
                            $('#'+fieldID+"_field_row").removeAttr("style");
                        }
                    }

                    if ($('#'+fieldID).hasClass('emailfield')) {
                        var email = $('#'+fieldID).val();
                        if(email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                            // valid email
                            $('.'+fieldID+"_field_content").find('.error2').remove();
                        }
                        else 
                        {
                            // not valid
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
                            $('.'+fieldID+"_field_content").find('.error2').remove();
                            $('.'+fieldID+"_field_content").append('<label id="'+fieldID+'-error2" class="error2 label-error" for="'+fieldID+'" >This field only accepts E-Mail Address.</label>');
                            console.log("error in email");                            
                        }
                    }
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
                    onFinishing: function(e, currentIndex) {

                        let isValid = validateFields(currentIndex);

                        if (isValid === "true" || isValid === false || isValid === null ) 
                        {                          
                            console.log("not valid field detected");

                        } else {                            
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
                $items = $cfLogic->where('form_id', $form_id)->distinct()->get(['selected_option_id']);
            @endphp

            /*
            @foreach ($items as $item) 
                console.log("{{$item->selected_option_id}}");
            @endforeach
            */

            @foreach ($items as $item) 

                $('{{ '#' . $item->selected_option_id }}').on('change', function() {
                    let cflogic = $(document).find('.cfLogic');

                    @php                
                        $options = $cfLogic->where('selected_option_id', $item->selected_option_id)->get();
                    @endphp

                    @foreach ($options as $option) 
                        if ($(this).val() == "{{ $option->field_value }}") 
                        {                          
                            console.log(" fied {{ $item->selected_option_id }} " + ", show : {{$option->field_id}} ");
                            $('{{ '#'. $option->field_id ."_field_row"  }}').show();   
                            //Get the html field content
                            $.ajax({
                                    type: 'POST',
                                    url: "{{ url('api/getHTMLFieldContent?api_token=') }}" + api_token,
                                    data: {
                                        formID              :  1,
                                        field_id               :  {{$option->field_id}},                                                           
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(data) {                       
                                        $( "#form-content" ).append( data.field );                                       
                                        $('{{ '.'. $option->field_id ."_field_content"  }}').html(data.content);   
                                        $('{{ '#'. $option->field_id ."_field_row"  }}').show();   
                                    }
                                });
                        } else {
                             $('{{ '#'. $option->field_id ."_field_row"  }}').hide();   
                        }
                        //console.log(" fied {{ $item->selected_option_id }} " + ", show : {{$option->field_id}} ");
                    @endforeach


                });

            @endforeach



        });
    </script>


@endsection
