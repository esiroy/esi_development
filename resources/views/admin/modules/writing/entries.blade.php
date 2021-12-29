@extends('layouts.admin')

@section('content')


    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                @include('admin.modules.writing.includes.menu.top')      
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


                <div class="card">
                    <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                         Writing Entries
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


                    <div class="card-body p-0 m-0 b-0">

                        <div class="table-responsive mb-0">
                            <table class="table esi-table table-bordered table-striped  ">
                                <thead>
                                    <tr>
                                        <td>First Name</td>
                                        <td>Last Name</td>
                                        <td>ご登録メールアドレス</td>

                                        @foreach ($formFields as $formField)
                                            <td class="{{ strtolower(str_replace(' ', '_', $formField->name)) }}_data" style="display:none">
                                                {{ $formField->name }}
                                            </td>
                                        @endforeach   

                                        <td>Countdown</td>
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
                                @foreach($entries as $key => $entry)
                                    @php 
                                        $user = \App\Models\User::find($entry->user_id); 
                                        $values = json_decode($entry->value, true);
                                    @endphp                                     
                                    <tr>
                                        <td><a href='{{ url("admin/writing/entry/$form_id/$entry->id") }}'>{{ $user->firstname }}</a></td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>{{ $user->email }}</td>


                                        @foreach ($formFields as $formField)
                                            <td class="{{ strtolower(str_replace(' ', '_', $formField->name)) }}_data" style="display:none">
                                                {!! $fieldValue[$entry->id][$formField->id] ?? '' !!}
                                            </td>
                                        @endforeach 

                                        <td>
                                            <div id="{{$formField->id . '_countdown_'. $key }}" style="background-color:">
                                                  <!--{{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 2 days')) }}-->
                                            </div>

                                            <script type="text/javascript">
                                              window.addEventListener('load', function() {
                                                countdown("{{$formField->id . '_countdown_'. $key }}", " {{ date('M d, Y H:i:s', strtotime($entry->created_at. ' + 47 hours')) }} ");
                                              });
                                            </script> 
                                        </td>
                                        <td>
                                            <select id="assignTutor_{{ $entry->id }}" class="assignTutor">
                                               
                                                @if (Auth::user()->user_type == 'ADMINISTRATOR')  
                                                    <option value="" class="{{ $entry->id }}"> Select </option>
                                                    @foreach($tutors as $tutor)
                                                    <option 
                                                        value="{{ $tutor->user_id }}" class="{{ $entry->id }}" @if ($entry->appointed_tutor_id == $tutor->user_id ) {{ " selected = 'selected"}}  @endif>
                                                            {{ $tutor->user->firstname }}
                                                        </option>
                                                    @endforeach
                                                @else 
                                                    @foreach($tutors as $tutor)
                                                    <option 
                                                        value="{{ $tutor->user_id }}" class="{{ $entry->id }}" @if ($entry->appointed_tutor_id == $tutor->user_id ) {{ " selected = 'selected"}}  @endif>
                                                            {{ $tutor->user->firstname }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>                                       
                                    </tr>
                                @endforeach                                                                       
                            </table>


                            <div class="mt-4 mr-4 float-right">
                                {{ $entries->links() }}
                            </div>

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