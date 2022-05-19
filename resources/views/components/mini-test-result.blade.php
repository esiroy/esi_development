<div>

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