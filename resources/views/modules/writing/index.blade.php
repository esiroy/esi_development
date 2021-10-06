@extends('layouts.writing.template')

@section('content')

    <form id="profileForm" method="post" class="form-horizontal" style="display:none">

        <h2>Account</h2>

        <section data-step="0">
            @foreach($formFieldHTML as $HTML) 
                {!! $HTML !!}
            @endforeach
        </section>

        <!--
        <h2>Lessons</h2>
        <section data-step="1">
            <div id="lesson-info" ></div>
        </section>
        -->

        <input type="submit" style="display:none">
    </form>
@endsection

@section('scripts')
    <script src="{{ url('js/steps/jquery.steps.min.js') }}" defer></script>
    <script src="{{ url('js/validation/jquery.validation.min.js') }}" defer></script>
    <script>
        window.addEventListener('load', function() {

            $('#profileForm').show(300);

            function adjustIframeHeight() {
                var $body = $('body'), $iframe = $body.data('iframe.fv');email
                if ($iframe) {
                    // Adjust the height of iframe
                    $iframe.height($body.height());
                }
            }

            // IMPORTANT: You must call .steps() before calling .formValidation()
            $('#profileForm').steps({
                    headerTag: 'h2',
                    bodyTag: 'section',
                    onStepChanged: function(e, currentIndex, priorIndex) {
                        // You don't need to care about it
                        // It is for the specific demo
                        adjustIframeHeight();
                    },
                    // Triggered when clicking the Previous/Next buttons
                    onStepChanging: function(e, currentIndex, newIndex) {
                      
                        /*
                        $("#lesson-info").load("/writing/ielts", function(responseTxt, statusTxt, jqXHR){
                            if(statusTxt == "success"){
                                $( "#lesson-info" ).html( responseTxt );
                            }
                            if(statusTxt == "error"){
                                alert("Error: " + jqXHR.status + " " + jqXHR.statusText);
                            }
                        });           
                        */

                        return true;
                    },
                    // Triggered when clicking the Finish button
                    onFinishing: function(e, currentIndex) {
                        //alert ("finished")

                        //$('#profileForm').submit();

                        $('#profileForm').find('[type="submit"]').trigger('click');
                    },
                    onFinished: function(e, currentIndex) {
                        // Uncomment the following line to submit the form using the defaultSubmit() method
                        // $('#profileForm').formValidation('defaultSubmit');

                        // For testing purpose
                        //$('#welcomeModal').modal();

                       // alert ("finished")
                    }
                });

                
        
  
     
            @php
                $cfLogic = new \App\Models\ConditionalFieldLogic;
                $items = $cfLogic->where('form_id', $form_id)->distinct()->get(['selected_option_id']);

            @endphp

            @foreach ($items as $item) 
                console.log("{{$item->selected_option_id}}");
            @endforeach

            @foreach ($items as $item) 

                $('{{ '#' . $item->selected_option_id }}').on('change', function() {

                    $('.cfLogic').hide();

                    @php                
                        $options = $cfLogic->where('selected_option_id', $item->selected_option_id)->get();
                    @endphp

                    @foreach ($options as $option) 
                        if ("{{ $option->field_value }}" == $(this).val() ) {
                          
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

                                    }
                                });

                        }

                        //console.log(" fied {{ $item->selected_option_id }} " + ", show : {{$option->field_id}} ");
                    @endforeach


                });

            @endforeach



        });
    </script>


@endsection
