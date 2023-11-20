@extends('layouts.admin')
@section('content')
<div class="container bg-light px-0">
    @include('admin.modules.member.includes.menu')
    <div class="esi-box">
        <!--@include('admin.modules.member.includes.breadcrumbs')-->
        <div class="container mt-5">

        <div class="col-12">
                    @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                    @elseif (session('error_message'))
                    <div class="alert alert-danger">
                        {{ session('error_message') }}
                    </div>
                    @endif
                </div>


            <div class="card esi-card mt-5">
                <div class="card-header esi-card-header">
                    Report Card
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-5">
                            @include('admin.modules.member.includes.profile')
                        </div>

                        <div class="col-md-7">
                            <form action="{{ route("admin.reportcarddate.update", $reportCardDate->id) }}"  method="POST" enctype="multipart/form-data" onsubmit="return validate(this)">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <input type="hidden" name="reportcardID" value="{{ $reportCardDate->id }}">
                                <input type="hidden" name="memberid" value="{{ $memberInfo->user_id }}">
                                <input type="hidden" name="tutorid" value="{{ Auth::user()->id }}">                                 
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>Date</td>
                                            <td>:</td>
                                            <td>
                                                @php 
                                                if (isset($reportCardDate->lesson_date))
                                                {
                                                    $lesson_date = date('Y-m-d', strtotime($reportCardDate->lesson_date) ) ?? '';
                                                } else {
                                                    $lesson_date = '';
                                                }                                                    
                                                @endphp
                                                <input required name="inputDate" id="inputDate" type="date" class="inputDate hasDatepicker form-control form-control-sm" 
                                                    style="margin: 0px; width: 176px;" value="{{ $lesson_date }}" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Course</td>
                                            <td>:</td>
                                            <td>
                                                <input required type="text" name="lessonCourse" value="{{ $reportCardDate->lesson_course ?? '' }}"
                                                    class="form-control form-control-sm"  required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Material</td>
                                            <td>:</td>
                                            <td><input required type="text" name="lessonMaterial" class="form-control form-control-sm"  value="{{ $reportCardDate->lesson_course ?? ''  }}" required></td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Subject</td>
                                            <td>:</td>
                                            <td><input required type="text" name="lessonSubject" class="form-control form-control-sm"  value="{{ $reportCardDate->lesson_subject ?? '' }}" required=""></td>
                                        </tr>
                                        <tr>
                                            <td>Tutor Grade</td>
                                            <td>:</td>
                                            <td><textarea required name="grade" class="form-control form-control-sm">{{ $reportCardDate->grade ?? '' }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tutor Comment</td>
                                            <td>:</td>
                                            <td><textarea name="comment" class="form-control form-control-sm">{{ $reportCardDate->comment ?? '' }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pt-2">Select New File</td>
                                            <td class="pt-2">:</td>
                                            <td class="pt-2"><input type="file" id="myFile" name="file"></td>
                                        </tr>
                                        <tr>
                                            <td class="pt-2">Uploaded File</td>
                                            <td class="pt-2">:</td>                                        
                                            <td class="pt-2">
                                                <a href="{{ Storage::url("uploads/report_files/". basename($reportCardDate->file_path)) }}" download>DOWNLOAD</a>                                            
                                            </td>
                                        <tr>
                                            <td class="pt-2">Display Tutor Name?</td>
                                            <td class="pt-2"></td>
                                            <td class="pt-2">
                                                <input type="checkbox" id="checkboxDisplayTutorName" checked="@if ($reportCardDate->display_tutor_name == true) ? true : false @endif"> 
                                                <input type="hidden" name="displayTutorName" id="displayTutorName" value="false">
                                            </td>                                                
                                        </tr>
                                        <tr>
                                            <td class="pt-3"></td>
                                            <td class="pt-3"></td>
                                            <td class="pt-3"><input type="submit" value="save" style="background: lightblue;"></td>
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

<!--
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
-->

@endsection