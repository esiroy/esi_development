
 @if (isset($examTypes)) 
    @foreach($examTypes as $examType)

        <span class="border border-primary">
            {{ $examType->exam_type }} 
        </span>

        
    @endforeach
@endif   




@foreach ($scores as $score)    
<div class="row my-0">
    <div class="col-md-12 mb-1">


        <div class="small font-weight-bold">
            Exam Date : <span id="memberExamDate" class="font-weight-normal"> {{ $score->exam_date }} </span>
        </div>

        <div class="small font-weight-bold">
            Exam Type : <span id="memberExamType" class="font-weight-normal"> {{ formatWords($score->exam_type) }} </span>
        </div>  

        @if (isset($score->exam_scores))
            @foreach (json_decode($score->exam_scores) as $key => $score)
            <div class="small font-weight-bold">
                {{ formatWords($key) }}: <span id="overallBandScore" class="font-weight-normal"> {{ $score }} </span>
            </div>
            @endforeach
        @endif

        

        <div> 
            <hr style="margin-top:3px; margin-bottom:3px; border: 1px #B8B8B8 dashed">
        </div>
    </div>
</div>
@endforeach


{!! $scores->links() !!}