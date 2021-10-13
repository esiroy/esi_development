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

            
    <form id="writing-form" method="POST"  action="{{ route('writingSaveEntry.store', ['form_id' => $form_id  ]) }}" class="form-horizontal" style="display:none">
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
.steps {
    display: none !important
}
</style>
@endsection

@section('scripts')
    <script src="{{ url('js/steps/jquery.steps.min.js') }}" defer></script>
    <script src="{{ url('js/validation/jquery.validation.min.js') }}" defer></script>
    <script>
        window.addEventListener('load', function() {


            $('#writing-form').show(300);

            function adjustIframeHeight() {
                var $body = $('body'), $iframe = $body.data('iframe.fv');
                if ($iframe) {
                    // Adjust the height of iframe
                    $iframe.height($body.height());
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


                        if (input === false || input === null) {
                            // Do not jump to the next step
                            return false;
                        }                         

                        /*
                        if (isValidStep === false || isValidStep === null) {
                            // Do not jump to the next step
                            return false;
                        }
                        */

                        return true;

                    },
                    // Triggered when clicking the Previous/Next buttons
                    onStepChanging: function(e, currentIndex, newIndex) {

                        let inputs = $("#writing-form-p-"+currentIndex).find('.form-control');

                        let requiredFieldsArr = [];

                        Array.from(inputs).forEach(field => 
                        {
                            let fieldID =  $(field).attr('id');

                            if ($('#'+fieldID).attr( "required" )) 
                            {
                                console.log(fieldID + " is required")
                                let isValid = $('#'+fieldID).valid();

                                requiredFieldsArr.push({
                                    'id': fieldID,
                                    'isValid': isValid
                                })
                            }
                        });

                        let goToNextStep = true;

                        Array.from(requiredFieldsArr).forEach(requiredField => {
                            if (requiredField.isValid === false || requiredField.isValid === null) {
                                console.log(requiredField.isValid + " 1")

                                goToNextStep = false;

                                return false;

                            } else {
                                console.log(requiredField.isValid + " 2")
                            }
                        });
                     

                        if (goToNextStep == true) {
                            return true;
                        } else{
                            return false;
                        }
                        

                        /*
                        if ( (input1 === false || input1 === null)) {
                            // Do not jump to the next step
                            return false;
                        }   


                        if ( (input2 === false || input2 === null)) {
                            // Do not jump to the next step
                            return false;
                        }   

                        */

                    },
                    // Triggered when clicking the Finish button
                    onFinishing: function(e, currentIndex) {
                        $('#writing-form').find('[type="submit"]').trigger('click');
                    },
                    onFinished: function(e, currentIndex) {

                        alert ("finished");

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

                        if ($(this).val() == "{{ $option->field_value }}") {
                          
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
