@foreach ($scores as $score)    
<div class="row my-0">
    <div class="col-md-12 mb-1">
        <div class="small font-weight-bold">
            Exam Date : <span id="memberExamDate" class="font-weight-normal"> {{ $score->exam_date }} </span>
        </div>

        <div class="small font-weight-bold">
            Exam Type : <span id="memberExamType" class="font-weight-normal"> {{ formatWords($score->exam_type) }} </span>
        </div>  


        @foreach (json_decode($score->exam_scores) as $key => $score)
        <div class="small font-weight-bold">
            {{ formatWords($key) }}: <span id="overallBandScore" class="font-weight-normal"> {{ $score }} </span>
        </div>
        @endforeach

        

        <div> 
            <hr style="margin-top:3px; margin-bottom:3px; border: 1px #B8B8B8 dashed">
        </div>
    </div>
</div>
@endforeach
{!! $scores->links() !!}