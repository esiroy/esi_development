@foreach ($scores as $score)
    
<div class="row">
    <div class="col-md-12 my-2">
        <div>{{ $score->exam_date }}</div>
        <div>{{ $score->exam_type }}</div>
        <div>{{ $score->exam_score }}</div>
    </div>
</div>

@endforeach

{!! $scores->links() !!}