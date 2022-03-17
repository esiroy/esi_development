

<p>Course : {{ $writingGrade['course'] }} </p>
<p>Material : {{ $writingGrade['material'] }} </p>
<p>Subject : {{ $writingGrade['subject'] }} </p>
<p>Appointed : {{ boolval($writingGrade['appointed'])  }} </p>

<p>Grade : {{ $writingGrade['grade'] }} </p>
<p>Words : {{ $writingGrade['words'] }} </p>
<p>Content : {{ $writingGrade['content'] }} </p>
<p>Grade : {{ $writingGrade['grade'] }} </p>


<p>
    Attachment : <a href="{{ url($writingGrade['attachment']) }}">{{ basename($writingGrade['attachment']) }}</a>
</p>