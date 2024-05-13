@extends('layouts.admin')
@section('content')
<div class="container bg-light px-0">
    @include('admin.modules.member.includes.menu')
    <div class="esi-box">
        <!--@include('admin.modules.member.includes.breadcrumbs')-->
        <div class="container mt-5">
            <div class="card esi-card mt-5">
                <div class="card-header esi-card-header">
                    Lesson Report Card 
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-5">
                            @include('admin.modules.member.includes.profile')
                                              
                          <member-notes-component
                            :tutorinfo="{{ json_encode(Auth::user()) }}"
                            :memberinfo="{{ json_encode($memberInfo) }}"                                                
                            api_token="{{ Auth::user()->api_token }}" 
                            csrf_token="{{ csrf_token() }}"                                             
                            ></member-notes-component>                                              
                        </div>

                        <div class="col-md-7">
                        
                            <form action="{{ route('admin.reportcard.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="scheduleitemid" value="{{ $scheduleitemid }}">

                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>Lesson Course</td>
                                            <td colspan="6">
                                                <input required type="text" name="lessonCourse" id="lessonCourse*" class="form-control form-control-sm"
                                                value="@if(isset($reportCard->lesson_course)){{$reportCard->lesson_course}}@else{{$latestReportCard->lesson_course??''}}@endif" size="50">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Material</td>
                                            <td colspan="6">
                                                <input required type="text" name="lessonMaterial" id="lessonMaterial*" class="form-control form-control-sm"
                                                value="@if(isset($reportCard->lesson_material)){{$reportCard->lesson_material}}@else{{$latestReportCard->lesson_material??''}} @endif" size="50">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Lesson Subject
                                            </td>
                                            <td colspan="6">
                                                <input required type="text" name="lessonSubject" id="lessonSubject*" class="form-control form-control-sm"
                                                value="@if(isset($reportCard->lesson_subject)){{$reportCard->lesson_subject}}@else{{$latestReportCard->lesson_subject??''}} @endif" size="50">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Multi Account ID</td>
                                            <td> 
                                                <input required type="text" disabled value="{{$scheduleItem->member_multi_account_id ??'' }}">                                               
                                            </td>
                                        </tr>    

                                        <tr>
                                            <td>Multi Account Alias</td>
                                            <td> 
                                                <input required type="text" disabled value="{{ $multiAccountAlias  ?? ''}}">
                                            </td>
                                        </tr>                                        

                                        <!--
                                        <tr>
                                            <td>
                                                Lesson Level
                                            </td>
                                            <td colspan="6">
                                           
                                                @php 
                                                    if (isset($reportCard->lesson_level)){
                                                        $lesson_level = $reportCard->lesson_level;

                                                    } elseif(isset($latestReportCard->lesson_level)) {

                                                        $lesson_level = $latestReportCard->lesson_level;
                                                    } else {


                                                        $lesson_level = null;
                                                    }
                                                @endphp

                                                

                                                <select name="lessonLevel" class="form-control form-control-sm col-md-3">
                                                    <option value="1" @if ($lesson_level == '1') {{ 'selected' }}  @endif>1</option>
                                                    <option value="2" @if ($lesson_level == '2') {{ 'selected' }}  @endif>2</option>
                                                    <option value="3" @if ($lesson_level == '3') {{ 'selected' }}  @endif>3</option>
                                                    <option value="4" @if ($lesson_level == '4') {{ 'selected' }}  @endif>4</option>
                                                    <option value="5" @if ($lesson_level == '5') {{ 'selected' }}  @endif>5</option>
                                                    <option value="6" @if ($lesson_level == '6') {{ 'selected' }}  @endif>6</option>
                                                    <option value="7" @if ($lesson_level == '7') {{ 'selected' }}  @endif>7</option>
                                                    <option value="8" @if ($lesson_level == '8') {{ 'selected' }}  @endif>8</option>
                                                    <option value="9" @if ($lesson_level == '9') {{ 'selected' }}  @endif>9</option>
                                                    <option value="10" @if ($lesson_level == '10') {{ 'selected' }}  @endif>10</option>
                                                </select>
                                               

                                            </td>

                                        </tr>-->

                                        <tr>
                                            <td colspan="7">

                                                <div class="row mt-4">


                                                    <div class="col-4">
                                                        
                                                        <table class="table table-sm border">
                                                            <tr class="bg-darkblue">
                                                                <th class="small text-white text-center font-weight-bold" colspan="7">
                                                                    Understanding Percentage
                                                                </th>
                                                            </tr>                                                                    
                                                            <tr>
                                                                <td class="pl-4">                                                                    
                                                                    <input required type="radio" name="grade" value="UNDERSTAND_86_100"
                                                                    @if (isset($reportCard->grade) && ($reportCard->grade == 'UNDERSTAND_86_100')) {{ 'checked' }}  @endif> 
                                                                    <span class="small">understand 86-100 %</span>                                                                  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pl-4">
                                                                    <input type="radio" name="grade" value="UNDERSTAND_65_85"
                                                                    @if (isset($reportCard->grade) &&  ($reportCard->grade == 'UNDERSTAND_65_85')) {{ 'checked' }}  @endif> 
                                                                    <span class="small">understand 65-85 %</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pl-4">
                                                                    <input type="radio" name="grade" value="UNDERSTAND_41_64"
                                                                    @if (isset($reportCard->grade) &&  ($reportCard->grade == 'UNDERSTAND_41_64')) {{ 'checked' }}  @endif> 
                                                                    <span class="small">understand 41-64 %</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pl-4">
                                                                    <input type="radio" name="grade" value="UNDERSTAND_20_40"
                                                                    @if (isset($reportCard->grade) && ($reportCard->grade == 'UNDERSTAND_20_40')) {{ 'checked' }}  @endif> 
                                                                    <span class="small">understand 20-40 %</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pl-4">
                                                                    <input type="radio" name="grade" value="UNDERSTAND_0_19"
                                                                    @if (isset($reportCard->grade) && ($reportCard->grade == 'UNDERSTAND_0_19')) {{ 'checked' }}  @endif> 
                                                                    <span class="small">understand 0-19 %</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="col-8">


                                                        <table class="table table-sm border">
                                                            <tr class="bg-darkblue">
                                                                <th class="small text-white text-center font-weight-bold" colspan="7">
                                                                   Members Home Work 
                                                                </th>
                                                            </tr>                                                                    
                                                            <tr>
                                                                <td class="px-2">  

                                                                    @if (isset($homework))
                                                                    <div class="small">
                                                                        File: <a href="{{ url( Storage::url($homework->original) ) }}" 
                                                                        download="{{ url( Storage::url($homework->original) ) }}" >{{ $homework->filename }}</a>
                                                                    </div>

                                                                     <div class="small">
                                                                        Instruction : {{ $homework->instruction ?? '' }}
                                                                    </div>
                                                                    @else
                                                                        <div class="text-center">
                                                                            <span class="small text-secondary">No homework found!</span>
                                                                        </div>
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                        </table>


                        
                                                        <table class="table table-sm border">
                                                            <tr class="bg-darkblue">
                                                                <th class="small text-white text-center font-weight-bold" colspan="7">

                                                                    @if (isset($homework))
                                                                        {{ "Update Home Work Attachments" }}
                                                                    @else 
                                                                        {{ "Add Home Work Attachments" }}
                                                                    @endif
                                                                </th>
                                                            </tr>                                                                    
                                                            <tr>
                                                                <td class="px-2">                                                                    
                                                                   

                                                                    <div id="file-attachments" class="mb-2 mx-0">
                                                                        <input id="file" type="file" name="file" class="form-control-file form-control-sm pl-0"  />
                                                                        <span class="small text-secondary" >* accepts .png, jpg, jpeg, pdf file extension only<span>
                                                                        <span class="error"></div>
                                                                    </div>


                                                                    <textarea id="instruction" name="instruction" 
                                                                        class="form-control form-control-sm" 
                                                                        placeholder="Add Homework Instruction"
                                                                        style="min-height:50px" disabled
                                                                    ></textarea>


                                                                </td>
                                                            </tr>
                                                        </table>



                                                    </div>
                                                </div>
                                        

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="7">
                                                Tutor Comment <br>
                                                <textarea name="comment" rows="5" cols="70" class="form-control form-control-sm">@if(isset($reportCard->comment)) {{ $reportCard->comment }}@endif</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <input type="submit" value="save" class="btn btn-light">
                                            </td>
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

@section('styles')
@parent
<style>

</style>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() 
    {
        $(document).on("change","#file",function() 
        {
           
            const oFile = document.getElementById("file").files[0];  

            let fileExtension = ['pdf', 'doc', 'docx', 'jpeg', 'jpg', 'png'];                                

            if ($.inArray($('#file').val().split('.').pop().toLowerCase(), fileExtension) == -1) 
            {
                let message = "Only formats are allowed : "+fileExtension.join(', ');
                $('#file-attachments').find('.error').html('<div class="alert alert-danger small">' + message +'.</div>')


                $('#file').val("");

                return false;

            } 
            else if (oFile.size <= 2097152) // 2 MiB for bytes.
            {             
            
                $('#file-attachments').find('.error').html('')

                $('#instruction').prop('disabled', false);

            } else {
                let message = "This File Size exceeds 2MB";
                $('#file-attachments').find('.error').html('<div class="alert alert-danger small">' + message +'.</div>')
                $('#file').val("");

                return false;
            }   
            
        });

    });
</script>
@endsection