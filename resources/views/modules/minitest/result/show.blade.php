@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/minitest') }}">Minitest</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Result</li>
            </ol>
        </nav>

        <div class="container">

            <!--
            +"question_id": 4
            +"question_text": "The effect on birth weight ______ depending on the prevalence of iron deficiency."
            +"choices": array:3 [▶]
            +"selected_choice_id": "10"
            +"selected_choice_text": "can vary"
            -->

            @foreach($items as $key => $item)

               <div id="{{ $item->question_id ?? '' }}" class="mb-4">

                    <div class="font-weight-bold">
                        {{ $ctr++ . ". " }} {{ $item->question }}
                    </div>

                    <div class="ml-4">

                        <div class="mt-2 font-weight-bold">
                            Correct Answer: 
                            <span class="text-orange">
                                {{ $item->correct_answer }}
                            </span>
                        </div>
                        

                        @if (!isset($item->answer_choice_id) && $item->is_correct == null)

                            <div class="mt-2 text-secondary font-weight-bold">
                                <i class="fa fa-question text-secondary" aria-hidden="true"></i> 
                                No Answer
                            </div>                    
                        
                        @elseif($item->is_correct == true)

                            <div class="mt-2  font-weight-bold">
                                Your Answer: 
                                <span class="text-primary">
                                    {{ $item->your_answer }} 
                                </span>
                            </div>   


                            <div class="mt-2  text-success font-weight-bold"> 
                                <i class="fa fa-check" aria-hidden="true"></i>
                                 Correct 
                            </div>

                        @elseif ($item->is_correct == false)

                        
                            <div class="mt-2 font-weight-bold">
                                Your Answer: 
                                <span class="text-primary">
                                    {{ $item->your_answer }} 
                                </span>
                            </div> 

                            <div class="text-danger font-weight-bold">
                                <i class="fa fa-times" aria-hidden="true"></i> Incorrect 
                            </div>
                        @endif

                    </div>

               </div>

               

            @endforeach

        </div>
    </div>


</div>
</div>
@endsection