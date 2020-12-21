@extends('layouts.admin')
@section('content')
<div class="container bg-light px-0">
    @include('admin.modules.member.includes.menu')
    <div class="esi-box">
        <!--@include('admin.modules.member.includes.breadcrumbs')-->
        <div class="container mt-5">
            <div class="card esi-card mt-5">
                <div class="card-header esi-card-header">
                    Report Card
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">

                            @include('admin.modules.member.includes.profile')

                        </div>


                        <div class="col-md-6">

                            <form action="{{ route("admin.reportcarddate.store") }}"  method="POST" enctype="multipart/form-data" onsubmit="return validate(this)">
                                @csrf

                                <input type="hidden" name="memberid" value="{{ $member->id }}">
                                <input type="hidden" name="tutorid" value="{{ $member->main_tutor_id }}">
                                <input type="hidden" name="reportcarddateid" value="">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Date</td>
                                            <td>:</td>

                                            <td>
                                                <input required name="inputDate" id="inputDate" type="date" class="inputDate hasDatepicker form-control form-control-sm" style="margin: 0px; width: 176px;" value="" required="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Course</td>
                                            <td>:</td>
                                            <td><input required type="text" name="lessonCourse" class="form-control form-control-sm" value="" required=""></td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Material</td>
                                            <td>:</td>
                                            <td><input required type="text" name="lessonMaterial" class="form-control form-control-sm"  value="" required=""></td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Subject</td>
                                            <td>:</td>
                                            <td><input required type="text" name="lessonSubject" class="form-control form-control-sm"  value="" required=""></td>
                                        </tr>
                                        <tr>
                                            <td>Tutor Grade</td>
                                            <td>:</td>
                                            <td><textarea required name="grade" class="form-control form-control-sm" ></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tutor Comment</td>
                                            <td>:</td>
                                            <td><textarea name="comment" class="form-control form-control-sm" ></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>File</td>
                                            <td>:</td>

                                            <td><input type="file" id="myFile" name="file"></td>

                                        </tr>
                                        <tr>
                                            <td>Display Tutor Name?</td>
                                            <td></td>
                                            <td><input type="checkbox" id="checkboxDisplayTutorName"></td>
                                            <input type="hidden" name="displayTutorName" id="displayTutorName" value="false">
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><input type="submit" value="save" style="background: lightblue;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">   
   window.addEventListener('load', function () 
    {
        //load events
    });

	function validate(myform) {
		var file = jQuery("#myFile").val();
		
		if (file == "") {
			alert("Upload Docx / PDF.");
		} else {
			var extension = file.split(".").pop().toLowerCase();
			if(extension == 'docx' || extension == 'pdf' || extension == 'doc') 
				return true;
			else 
				alert("Upload Docx / PDF.");
		}
		
		return false;
	} 
</script>
@endsection

