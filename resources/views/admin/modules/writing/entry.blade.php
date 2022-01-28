@extends('layouts.admin')
@section('content')
    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light ">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('admin/writing') }}">Writing</a></li>
                        @if (Auth::user()->user_type == 'ADMINISTRATOR') 
                        <li class="breadcrumb-item "><a href="{{ url('admin/writing/entries/'.$form_id ) }}">Entries</a></li>
                        @endif
                        <li class="breadcrumb-item " aria-current="page">Entry - {{ $entry_id }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container bg-light">
        <div class="row">
            <div class="col-md-12">

                @if (Auth::user()->user_type == 'ADMINISTRATOR')  
                <div class="card esi-card mb-2">                                 
                    @include('admin.modules.writing.includes.menu.navigation')
                </div>
                @endif

            </div>
        </div>

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
    </div>



    @php 
        $is_appointed = false;
        $has_attachement = false;
        $values = json_decode($entry->value, true);
    @endphp

    @foreach ($values as $index => $value)                                             
        @php
            $numIndex = explode("_", $index);
            $fieldValue[$entry->id][$numIndex[0]] = $value;
        @endphp

        @if ($index == "appointed")
            @php 
                $is_appointed = true; 
            @endphp
        @endif
    @endforeach   

    <div class="container bg-light">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                         Writing Entry ID - {{ $entry_id ?? "" }}
                    </div>

                    <div class="card-body">
                        <div class="entry-container border border-light">
                                
                            @foreach ($formFields as $formField)
                                @if (isset($fieldValue[$entry->id][$formField->id]))
                                <div id="{{$entry->id}}" class="bg-light">
                                     <strong>{{$formField->name}}</strong>
                                </div>
                                @endif
                                
                                
                                @if ($formField->type == 'uploadfield')
                                    @if (isset($fieldValue[$entry->id][$formField->id]))
                                        @php 
                                            $writingFields = new \App\Models\WritingEntries;
                                            $has_attachement = true;
                                        @endphp      
                                        <div id="{{$entry->id}}" class="col-md-12"> 
                                            <div class="text-center pl-2">
                                                {{$writingFields->generateFileAnchorLink( $fieldValue[$entry->id][$formField->id] )}}
                                            </div>
                                        </div>                                                                
                                    @endif
                                @else
                                    @if (isset($fieldValue[$entry->id][$formField->id]))
                                        <div id="{{$entry->id}}" class="col-md-12"> 
                                            <div class="text-left pl-2 py-2">                                            
                                                {!! $fieldValue[$entry->id][$formField->id]  !!}                                            
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                      
                        </div>

                    </div>
                </div>


               @if (count($postedEntries) > 0)
                <div class="card mt-4">    
                    <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                        Teacher Submitted Reply
                    </div>                    
                    <div class="card-body">
                        <div class="container">
                            @foreach ($postedEntries as $postedEntry)

                            <div id="{{ $postedEntry->id }}" style="border-bottom:1px dotted #999" class="pb-4 pt-4">

                                <div class="row">
                                    <div class="col-md-3">Date Submitted</div>
                                    <div class="col-md-9">{{ ESIDateTimeSecondsFormat($postedEntry->created_at) }}</div>
                                </div>


                                <div class="row">
                                    <div class="col-md-3">Course</div>
                                    <div class="col-md-9">{{ $postedEntry->course }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">Material</div>
                                    <div class="col-md-9">{{ $postedEntry->material }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">Subject</div>
                                    <div class="col-md-9">{{ $postedEntry->subject }}</div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-3">Grade</div>
                                    <div class="col-md-9">{{ $postedEntry->grade }}</div>                                                                                                        
                                </div>
                                <div class="row">
                                    <div class="col-md-3">Appointed</div>
                                    <div class="col-md-9">{{ (boolval($postedEntry->appointed) ? 'Yes' : 'No')  }}</div>                                                                                                        
                                </div>
                                <div class="row">
                                    <div class="col-md-3">Words</div>
                                    <div class="col-md-9">{{ $postedEntry->words }}</div>                                                                                                        
                                </div>
                                <div class="row">
                                    <div class="col-md-3">Content</div>
                                    <div class="col-md-9">{{ $postedEntry->content }}</div>                                                                                                        
                                </div> 

                                @if ($postedEntry->attachment)                                
                                    <div class="row">
                                        <div class="col-md-3">Attachment</div>
                                        <div class="col-md-9">
                                            <a href="{{ url($postedEntry->attachment) }}" download="{{ url($postedEntry->attachment) }}">Download Attachment</a>
                                        </div>
                                    </div>                                    
                                @endif
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                @endif



                @if (Auth::user()->user_type == 'TUTOR' || Auth::user()->user_type == 'ADMINISTRATOR' )

                <div class="mt-4">
                    <div class="col-12 message-container text-center"></div>
                </div>

                <div class="card mt-4">
                        <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                            Teacher Reply Form
                        </div>          

                        <div class="card-body">
                            <form id="TutorReplyForm" name="TutorReplyForm" action="{{ route('admin.writing.postGrade', $entry->id ) }}"  method="POST" enctype="multipart/form-data" >
                                @csrf
                                <!--[start] From Tutor -->
                                <div class="container">
                                    <div class="row">
                                        <!--[start] Column 1-->
                                        <div class="col-2">
                                            Course: 
                                        </div>
                                        <div class="col-4">
                                            <input type="text" name="course" id="course" class="form-control form-control-sm" required>
                                        </div>

                                        <!--[start] Column 2-->
                                        <div class="col-2">
                                            Appointed: 
                                        </div>
                                        <div class="col-4">
                                            <input type="hidden" name="appointed_value" id="appointed_value" value="@if(isset($is_appointed) && $is_appointed == 'true'){{'on'}}@else{{'off'}}@endif"/>
                                            <input type="checkbox" name="appointed" id="appointed"  @if (isset($is_appointed) && $is_appointed == 'true') {{ "checked" }} @endif disabled >
                                        </div> 
                                    </div>

                                    <div class="row mt-2">
                                        <!--[start] Column 1-->
                                        <div class="col-2">
                                            Material: 
                                        </div>
                                        <div class="col-4">
                                            <input type="text" name="material" id="material"  class="form-control form-control-sm" required>
                                        </div>

                                        @if (($has_attachement == true))
                                            <!--[start] Column 2-->
                                            <div class="col-2">
                                                Words: 
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="words" id="words" class="form-control form-control-sm" required>
                                                <input type="hidden"  name="hasAttachement" value="true">
                                            </div>     
                                        
                                        @endif
                                      
                                    </div>


                                    <div class="row  mt-2">
                                        <!--[start] Column 1-->
                                        <div class="col-2">
                                            Subject: 
                                        </div>
                                        <div class="col-4">
                                            <input type="text" name="subject" id="subject" class="form-control form-control-sm" required>
                                        </div>

                                        @if (($has_attachement == true))
                                         <div class="col-2"></div>
                                        <div class="col-4">
                                            <button id="sendMemberReloadEmail" type="button" class="btn btn-success btn-sm" style="display:none"> Send Member Reload E-Mail</button>
                                        </div>
                                        @endif

                                    </div>

                                    <div class="row  mt-2">
                                        <!--[start] Column 1-->
                                        <div class="col-2">
                                            Grade: 
                                        </div>
                                        <div class="col-4">
                                            <input type="number" name="grade" id="grade" class="form-control form-control-sm" step='any' required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-2">
                                            Content: 
                                        </div>                                
                                        <div class="col-10">                                   
                                            <textarea name="content" id="content" class="form-control form-control-sm" required></textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-2">
                                            &nbsp;
                                        </div>                                
                                        <div class="col-10">      

                                            <input type="submit" style="display:none">

                                            <button id="teacherSubmitBtn" type="button" value="Submit Reply" class="btn btn-primary btn-sm" >Submit</button>
                                            <input type="file" id="file" name="file" class="btn btn-sm float-right" required><br><br>
                                        </div>

                                    </div>


                                </div>                            
                                <!--[end] From Tutor -->
                            </form>

                        </div>
                  

                </div> 
                @endif

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                        Entry Status
                    </div>
                    <div class="card-body p-0 m-0 b-0">

                        <div class="table-responsive mb-0">
                            <table class="table esi-table table-bordered table-striped  ">
                                <thead>
                                    <tr>
                                        <td id="countdownLabel">
                                            Countdown
                                        </td>
                                        <td>
                                            @if (Auth::user()->user_type == 'ADMINISTRATOR') 
                                                Appoint Teacher
                                            @else 
                                                Teacher
                                            @endif                                            
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>

                              
                                    @php 
                                        $user = \App\Models\User::find($entry->user_id); 
                                        $values = json_decode($entry->value, true);
                                        $grade = \App\Models\WritingEntryGrade::where('writing_entry_id', $entry->id)->first(); 
                                    @endphp                                 
                                                                     
                                    <tr>
                                        <td>

                                             @if (isset($grade))
                                                <div id="{{$formField->id . '_countdown' }}" style="background-color:green; padding:0px 15px; width:80%; margin:auto; color:#fff">
                                                    
                                                </div>
                                                <script type="text/javascript">
                                                window.addEventListener('load', function() {
                                                    countdownLeft("{{$formField->id . '_countdown' }}", 
                                                            "{{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 48 hours')) }}", 
                                                            "{{ date('M d, Y H:i:s', strtotime($grade->created_at)) }}");
                                                    let elem = document.querySelector('#countdownLabel');
                                                    elem.innerHTML = "Time Left After Submission";
                                                });
                                                </script> 
                                            @else 
                                           
                                                <div id="{{$formField->id . '_countdown' }}" style="background-color:blue; padding:0px 15px; width:80%; margin:auto; color:#fff">
                                                        <!--{{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 2 days')) }}-->
                                                </div>

                                                <script type="text/javascript">
                                                    window.addEventListener('load', function() {
                                                        countdown("{{$formField->id . '_countdown' }}", " {{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 47 hours')) }} ");
                                                    });
                                                </script>
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->user_type == 'ADMINISTRATOR') 
                                                <select id="assignTutor_{{ $entry->id }}" class="assignTutor">
                                                    <option value="" class="{{ $entry->id }}"> Select </option>
                                                    @foreach($tutors as $tutor)
                                                    <option value="{{ $tutor->user_id }}" class="{{ $entry->id }}" @if ($entry->appointed_tutor_id == $tutor->user_id ) {{ " selected = 'selected"}}  @endif>
                                                        {{ $tutor->user->firstname }}
                                                    </option>
                                                    @endforeach
                                                </select>

                                            @else 
                                                {{Auth::user()->firstname ?? " "}}
                                            @endif                                              
                                        </td>                                       
                                    </tr>
                                                                                           
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>   
@endsection


@section('styles')
    @parent
    <style type="text/css">
        .esi-table img {
            width: 100%;
            padding: 10px;
        }
        .entry-container img {
            width: 100%;
            padding: 10px;            
        }



    input:required:invalid, input:focus:invalid,
    textarea:required:invalid, textarea:focus:invalid  {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAYAAABWdVznAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAT1JREFUeNpi/P//PwMpgImBRMACY/x7/uDX39sXt/67cMoDyOVgMjBjYFbV/8kkqcCBrIER5KS/967s+rmkXxzI5wJiRSBm/v8P7NTfHHFFl5mVdIzhGv4+u///x+xmuAlcdXPB9KeqeLgYd3bDU2ZpRRmwH4DOeAI07QXIRKipYPD35184/nn17CO4p/+cOfjl76+/X4GYAYThGn7/g+Mfh/ZZwjUA/aABpJVhpv6+dQUjZP78Z0YEK7OezS2gwltg64GmfTu6i+HL+mUMP34wgvGvL78ZOEysf8M1sGgZvQIqfA1SDAL8iUUMPIFRQLf+AmMQ4DQ0vYYSrL9vXDz2sq9LFsiX4dLRA0t8OX0SHKzi5bXf2HUMBVA0gN356N7p7xdOS3w5fAgcfNxWtn+BJi9gVVBOQfYPQIABABvRq3BwGT3OAAAAAElFTkSuQmCC);
        background-position: right top;
        background-repeat: no-repeat;
        -moz-box-shadow: none;
    }
    input:required:valid,
    textarea:required:valid {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAYAAABWdVznAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAZZJREFUeNpi/P//PwMpgImBRMAy58QshrNPTzP8+vOLIUInisFQyYjhz98/DB9/fmT48/+35v7H+8KNhE2+WclZd+G0gZmJmYGThUNz1fUVMZtvbWT59eUXG9wGZIWMUPj993eJ5VeWxuy8veM/CzPL3yfvH/9H0QBSBDYZyOVm4mGYfn6q4cory5lYmFh+MrEwM/76/YsR7mk2ZjbWP///WP37/y8cqIDhx58fjvtu7XV6//ndT34G/v8FasUsDjKO/+A2PP3wpGLd+TVsfOz8XH6KAT+nHpokcu7h6d9q/BoMxToVbBYqlt9///+1GO4/WVdpXqY/zMqXn13/+vTjI9mj94/y//v9/3e9ZRObvYbDT0Y2xnm///x+wsfHB3GSGLf41jb3rv0O8nbcR66d+HPvxf2/+YZFTHaqjl8YWBnm/vv37yly5LL8+vuLgYuVa3uf/4T/Kd8SnSTZpb6FGUXwcvJxbAPKP2VkZESNOBDx8+9PBm4OwR1TwmYwcfzjsBUQFLjOxs52A2YyKysrXANAgAEA7buhysQuIREAAAAASUVORK5CYII=);
        background-position: right top;
        background-repeat: no-repeat;
    }


    </style>
@endsection

@section('scripts')
    @parent
    <script>

        window.addEventListener('load', function() 
        {
            $('#teacherSubmitBtn').click(function( event ) 
            { 
                var $myForm = $('#TutorReplyForm');

                if (!$myForm[0].checkValidity()) 
                {
                     $(document).find('.message-container').html('<div class="alert alert-danger">Please enter all required fields </div>').show()
                } else {
                
                    @if($has_attachement == true) 
                        //console.log("no attachment , point balance member, false = no override deduction")
                        checkMemberCredits(false);  
                    @else
                        console.log("no attachment , point balance member")
                        checkMemberCredits(true);   
                    @endif;
                }               
            });

            $(document).on('change keydown keyup', '#words', function() {
                @if($has_attachement == true) 
                    //console.log("no attachment , point balance member, false = no override deduction")
                    autoCheckMemberCredits(false);                      
                @else 
                    autoCheckMemberCredits(true);   
                @endif;
            });

            $('#sendMemberReloadEmail').on('click', function() 
            {            
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/writing/sendReloadEmail?api_token=') }}" + api_token,
                    data: {
                        formID              :  1,
                        entryID             : "{{ $entry->id }}",
                        memberID            : "{{ $entry->user_id }}",
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) 
                    {
                        if (data.success == true) 
                        {
                            alert(data.message);
                        } else {
                        
                        }
                    }
                });  
             });
        });





        /*            
            @variable  id              :  Element ID
            @variable  expiration_date :  Target Expiration Date  eg: "Jan 5, 2022 15:37:25"
        */
        function countdown(id, expiration_date) 
        {
           // console.log(expiration_date);
            
            // Set the date we're counting down to
            var countDownDate = new Date(expiration_date).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                // Get today's date and time
                let now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result in the element with id="demo"
                let timer = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                $('#'+id).html(timer);


                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    $('#'+id).html("EXPIRED");                
                }
            }, 1000);
        }

        function countdownLeft(id, expiration_date, submission_date) 
        {
            //console.log(expiration_date);
            
            // Set the date we're counting down to
            var countDownDate = new Date(expiration_date).getTime();
            // Get today's date and time
            let now = new Date(submission_date).getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            let timer = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
            $('#'+id).html(timer);           
        }



        function countWords(text) 
        {             
            return text.trim().split(/\s+/).length;
        }   

        function submitForm() {
            $('#TutorReplyForm').find('[type="submit"]').trigger('click');    
        }
 
        function autoCheckMemberCredits(overrideWordCount) 
        {            
            $.ajax({
                type: 'POST',
                url: "{{ url('api/writing/checkMemberCredits?api_token=') }}" + api_token,
                data: {
                    formID              :  1,
                    entryID             : "{{ $entry->id }}",
                    memberID            : "{{ $entry->user_id }}",
                    tutorID             : "{{ $entry->appointed_tutor_id }}",
                    overrideWordCount   : overrideWordCount,
                    words               : $('#words').val(),                  
                    appointed           : $('#appointed_value').val(),
                    hasAttachement      : "{{ $has_attachement }}",
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) 
                {
                    if (data.success == true) {
                        if (data.totalPointsLeft < 0) 
                        {   
                            $('#sendMemberReloadEmail').show();
                            $('.message-container').show();
                            $('.message-container').html('<div class="alert alert-danger">' + data.message +'</div>');                                  
                        } else {                        
                            $('#sendMemberReloadEmail').hide();
                            $('.message-container').show();
                            $('.message-container').html('<div class="alert alert-success">' + data.message +'</div>');
                        }                     
                    } else {

                        $('#sendMemberReloadEmail').show();

                         $('.message-container').show();
                         $('.message-container').html('<div class="alert alert-danger">' + data.message +'</div>');                                
                    }
                }
            });     
        }

        function checkMemberCredits(overrideWordCount) 
        {            
            $.ajax({
                type: 'POST',
                url: "{{ url('api/writing/checkMemberCredits?api_token=') }}" + api_token,
                data: {
                    formID              :  1,
                    entryID             : "{{ $entry->id }}",
                    memberID            : "{{ $entry->user_id }}",
                    tutorID             : "{{ $entry->appointed_tutor_id }}",
                    overrideWordCount   : overrideWordCount,
                    words               : $('#words').val(),                  
                    appointed           : $('#appointed_value').val(),
                    hasAttachement      : "{{ $has_attachement }}",
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) 
                {
                    if (data.success == true) {
                        if (data.totalPointsLeft < 0) 
                        {            
                            $('#sendMemberReloadEmail').show();
                            $('.message-container').show();
                            $('.message-container').html('<div class="alert alert-danger">' + data.message +'</div>');                                 
                        } else {

                            $('#sendMemberReloadEmail').hide();
                            $('.message-container').show();
                            $('.message-container').html('<div class="alert alert-success">' + data.message +'</div>');    
                            setTimeout(submitForm, 3000);
                        }                     
                    } else {
                        $('#sendMemberReloadEmail').show();
                        $('.message-container').show();
                        $('.message-container').html('<div class="alert alert-danger">' + data.message +'</div>');                                
                    }
                }
            });     
        }

        function assignTutor(entryID, tutorID) 
        {

            let api_token = "{{ Auth::user()->api_token }}";

            $.ajax({
                type: 'POST',
                url: '/api/writing/assignTutor?api_token=' + api_token,
                data: {
                    entryID: entryID,                    
                    tutorID: tutorID
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#loadingModal').modal('show');
                    $('#loadingModal').show();  
                },
                success: function(data) {                    
                    if (data.success == true) {
                    

                    } else {
                    
                        
                    }
                },
                complete: function(data) {              
                    $('#loadingModal').modal('hide');
                    $('#loadingModal').hide();                     
                }          
            });            
        }


        /* Assign Tutor */
        window.addEventListener('load', function() {

           $('.assignTutor').on('change', function() {
                let entryID = $(this).find('option:selected').attr('class');
                let tutorID = $(this).find('option:selected').val();
                assignTutor(entryID, tutorID);                
           });
           
        });

    </script>
@endsection