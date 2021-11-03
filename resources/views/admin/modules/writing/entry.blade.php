@extends('layouts.admin')

@section('content')


    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light ">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('admin/writing') }}">Writing</a></li>
                        <li class="breadcrumb-item "><a href="{{ url('admin/writing/entries/'.$form_id ) }}">Entries</a></li>
                        <li class="breadcrumb-item " aria-current="page">Entry - {{ $entry_id }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="container bg-light">
        <div class="row">
            <div class="col-md-12">
                <div class="card esi-card mb-2">
                    <div id="form-navigation" class="card-body esi-card-body">              
                        <div class="form-inline">
                            <a class='text-success' href="{{ url('admin/writing/?id='.$form_id) }}">
                                <button class="btn btn-sm btn-outline-secondary mr-2" type="button">
                                    Edit
                                </button>
                            </a>
                            <a class='text-secondary' href="{{ url('admin/writing/entries/'.$form_id) }}">
                                <button class="btn btn-sm btn-outline-success btn-outline-secondary mr-2" type="button">Entries</button>
                            </a>
                            <a class='text-secondary' href="{{ url('admin/writing/preview/'.$form_id) }}">
                                <button class="btn btn-sm btn-outline-secondary mr-2" type="button">                                
                                    Preview
                                </button>
                            </a>                            
                        </div>                                                
                    </div>
                </div>
            </div>
        </div>
    </div>


    @foreach ($entries as $entry)
        @php 
            $values = json_decode($entry->value, true);
        @endphp

        @foreach ($values as $index => $value)                                             
            @php
                $numIndex = explode("_", $index);
                $fieldValue[$entry->id][$numIndex[0]] = $value;
            @endphp
        @endforeach                  
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
                        @foreach($entries as $entry)                    
                            @foreach ($formFields as $formField)
                                <div class="bg-light">
                                     <strong>{{$formField->name}}</strong>
                                </div>
                                
                                @if ($formField->type == 'uploadfield')

                                    @if (isset($fieldValue[$entry->id][$formField->id]))
                                        @php 
                                            $writingFields = new \App\Models\WritingEntries;
                                        @endphp      
                                        <div class="col-md-4"> 
                                            <div class="text-center">
                                                {{$writingFields->generateFileAnchorLink( $fieldValue[$entry->id][$formField->id] )}}
                                            </div>
                                        </div>                                                                
                                    @endif
                                @else
                                    @if (isset($fieldValue[$entry->id][$formField->id]))
                                        {!! $fieldValue[$entry->id][$formField->id]  !!}
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                        </div>

                    </div>

                </div>
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

                                        <td>Countdown</td>
                                        <td>Appoint Teacher</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($entries as $key => $entry)
                                    @php 
                                        $user = \App\Models\User::find($entry->user_id); 
                                        $values = json_decode($entry->value, true);
                                    @endphp                                     
                                    <tr>
                                       

                                        <td>
                                            <div id="{{$formField->id . '_countdown_'. $key }}" style="background-color:">
                                                  <!--{{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 2 days')) }}-->
                                            </div>

                                            <script type="text/javascript">
                                              window.addEventListener('load', function() {
                                                countdown("{{$formField->id . '_countdown_'. $key }}", " {{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 2 days')) }} ");
                                              });
                                            </script> 
                                        </td>
                                        <td>
                                            <select id="assignTutor_{{ $entry->id }}" class="assignTutor">
                                               
                                                <option value="" class="{{ $entry->id }}"> Select </option>
                                                @foreach($tutors as $tutor)
                                                <option 
                                                    value="{{ $tutor->user_id }}" class="{{ $entry->id }}" @if ($entry->appointed_tutor_id == $tutor->user_id ) {{ " selected = 'selected"}}  @endif>
                                                        {{ $tutor->user->firstname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>                                       
                                    </tr>
                                @endforeach                                                                       
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
    <style>
        .esi-table img {
            width: 100%;
            padding: 10px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        /*            
            @variable  id              :  Element ID
            @variable  expiration_date :  Target Expiration Date  eg: "Jan 5, 2022 15:37:25"
        */
        function countdown(id, expiration_date) 
        {
            console.log(expiration_date);
            
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