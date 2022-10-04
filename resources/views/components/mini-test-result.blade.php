

<div id="miniTestResult">


    <div class="text-primary pt-2">
        <strong> Time Taken: {{ ESIDateTimeFormat($result->time_started) }} </strong>
    </div>

    <div class="text-primary pt-2">
        <strong> Time Ended: 
            @if(!(int)$result->time_ended)                                                 
                <span class="text-danger">{{ "Unfinished" }}</span>
            @else 
                <span > {{ ESIDateTimeFormat($result->time_ended) }} </span>                                                    
            @endif   
        </strong>
    </div>



    <h6 class="text-primary pt-4">
        <strong> Your Test Result </strong>
    </h6>


    <h6 class="mb-4 text-success font-weight-bold">                       
        Your have {{ $result->correct_answers }} correct answers out of {{ $result->total_questions }}                  
    </h6>



    @foreach($items as $key => $item)

        <div id="{{ $item->question_id ?? '' }}" class="mb-4">

            <div class="font-weight-bold">
                {{ $ctr++ . ". " }} {!! $item->question !!}
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

    <h6 class="text-primary pt-2">
        <strong> Your Test Result </strong>
    </h6>


    <h6 class="mb-4 text-success font-weight-bold">                       
        Your have {{ $result->correct_answers }} correct answers out of {{ $result->total_questions }}                  
    </h6>



</div>