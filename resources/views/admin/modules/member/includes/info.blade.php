

<div class="details mt-4">
    <div>Member : {{ $member->user->last_name}}, {{ $member->user->first_name}}</div>

    @if (isset($member->age))
    <div>Age : {{ $member->age }}</div>
    @endif

    @if (isset($member->student_year))
    <div>Student Year : {{ $member->student_year }}</div>
    @endif

    <div>Gender : {{ $member->gender }}</div>

    @if (isset( $scheduleItem['lesson_time']))
    <div>Lesson Date : {{ $scheduleItem['lesson_time'] }}</div>
    @endif

    @if (isset( $scheduleItem['tutor_id']))
    <div>Tutor : {{ \App\Models\Tutor::find($scheduleItem['tutor_id'])->name_en }}</div>
    @endif

</div>
