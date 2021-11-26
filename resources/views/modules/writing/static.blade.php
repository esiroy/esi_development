@extends('layouts.writing.template')

@section('content')

    <form id="profileForm" method="post" class="form-horizontal" style="display:none">
        <h2>Account</h2>
        <section data-step="0">
            <div class="row">
                <div class="mb-3 text-left col-md-6">
                    <label for="firstname" class="form-label">First Name (名)*</label>
                    <input type="text" class="form-control" id="firstname" placeholder="First Name (名)">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 text-left col-md-6">
                    <label for="lastname" class="form-label">Last Name (姓)*</label>
                    <input type="text" class="form-control" id="lastname" placeholder="Last Name (姓)">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 text-left col-md-6">
                    <label for="email" class="form-label">ご登録メールアドレス*</label>
                    <input type="email" class="form-control" id="email" required placeholder="Lご登録メールアドレス">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 text-left col-md-6">
                    <label for="course" class="form-label">レッスン名をお選びください*</label>

                    <select name="" class="form-control" onchange="test()">
                        <option value="Select">Select</option>
                        <option value="Academic" selected="selected">Academic</option>
                        <option value="General">General</option>
                    </select>

                </div>
            </div>
        </section>

        <h2>Lessons</h2>
        <section data-step="1">
            <div id="lesson-info" ></div>
        </section>
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
            $('#profileForm')
                .steps({
                    headerTag: 'h2',
                    bodyTag: 'section',
                    onStepChanged: function(e, currentIndex, priorIndex) {
                        // You don't need to care about it
                        // It is for the specific demo
                        adjustIframeHeight();
                    },
                    // Triggered when clicking the Previous/Next buttons
                    onStepChanging: function(e, currentIndex, newIndex) {
                      
                       
                        $("#lesson-info").load("/writing/ielts", function(responseTxt, statusTxt, jqXHR){
                            if(statusTxt == "success"){
                                $( "#lesson-info" ).html( responseTxt );
                            }
                            if(statusTxt == "error"){
                                alert("Error: " + jqXHR.status + " " + jqXHR.statusText);
                            }
                        });           

                        return true;
                    },
                    // Triggered when clicking the Finish button
                    onFinishing: function(e, currentIndex) {
                     
                    },
                    onFinished: function(e, currentIndex) {
                        // Uncomment the following line to submit the form using the defaultSubmit() method
                        // $('#profileForm').formValidation('defaultSubmit');

                        // For testing purpose
                        $('#welcomeModal').modal();
                    }
                });
        });
    </script>


@endsection
