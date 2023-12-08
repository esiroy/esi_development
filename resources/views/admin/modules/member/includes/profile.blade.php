
<!--
@todo: add image from database 
database tables ($userImages)
@crop - cropped image url
@filename
@original
user_id
-->
<div id="profile-image">
	
		@if ($userImage == null)
			<img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded">
		@else 
			<img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
		@endif
	
</div>

<div id="profile-details" class="details mt-4">
    <div>Member : {{ $memberInfo->user->lastname}}, {{ $memberInfo->user->firstname}}</div>

    @if (isset($member->age))
    <div>Age : {{ $memberInfo->age ?? "-" }}</div>
    @endif

    @if (isset($memberInfo->student_year))
    <div>Student Year : {{ $memberInfo->student_year }}</div>
    @endif

    <div>Gender : {{ $memberInfo->gender   ?? "-" }}</div>

    @if (isset( $scheduleItem['lesson_time']))
		<div>Lesson Date : {{ $scheduleItem['lesson_time']  ?? "-" }}</div>
    @endif
 
    <div>Tutor : {{ $tutorInfo->user->firstname ?? "-" }}</div>  

</div>
