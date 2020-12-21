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

                            <form action="{{ route("admin.reportcard.store") }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="scheduleitemid" value="{{ $scheduleitemid }}">

                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Lesson Course</td>
                                            <td colspan="6">
                                                <input required type="text" name="lessonCourse" id="lessonCourse*" 
                                                value="@if (isset($reportCard->lesson_course)) {{ $reportCard->lesson_course }}  @endif" size="50">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Material</td>
                                            <td colspan="6">
                                                <input required type="text" name="lessonMaterial" id="lessonMaterial*" 
                                                value="@if (isset($reportCard->lesson_material)) {{ $reportCard->lesson_material }}  @endif" size="50">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lesson Subject</td>
                                            <td colspan="6">
                                                <input required type="text" name="lessonSubject" id="lessonSubject*" 
                                                value="@if (isset($reportCard->lesson_subject)) {{ $reportCard->lesson_subject }}  @endif" size="50">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Lesson Level
                                            </td>
                                            <td colspan="6">
                                                <select name="lessonLevel">
                                                    <option value="1" @if (($reportCard->lesson_level == '1')) {{ 'selected' }}  @endif>1</option>
                                                    <option value="2" @if (($reportCard->lesson_level == '2')) {{ 'selected' }}  @endif>2</option>
                                                    <option value="3" @if (($reportCard->lesson_level == '3')) {{ 'selected' }}  @endif>3</option>
                                                    <option value="4" @if (($reportCard->lesson_level == '4')) {{ 'selected' }}  @endif>4</option>
                                                    <option value="5" @if (($reportCard->lesson_level == '5')) {{ 'selected' }}  @endif>5</option>
                                                    <option value="6" @if (($reportCard->lesson_level == '6')) {{ 'selected' }}  @endif>6</option>
                                                    <option value="7" @if (($reportCard->lesson_level == '7')) {{ 'selected' }}  @endif>7</option>
                                                    <option value="8" @if (($reportCard->lesson_level == '8')) {{ 'selected' }}  @endif>8</option>
                                                    <option value="9" @if (($reportCard->lesson_level == '9')) {{ 'selected' }}  @endif>9</option>
                                                    <option value="10" @if (($reportCard->lesson_level == '10')) {{ 'selected' }}  @endif>10</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="7">
                                                <input required type="radio" name="grade" value="UNDERSTAND_86_100" 
                                                @if (($reportCard->grade == 'UNDERSTAND_86_100')) {{ 'checked' }}  @endif> understand 86-100 %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <input type="radio" name="grade" value="UNDERSTAND_65_85"
                                                @if (($reportCard->grade == 'UNDERSTAND_65_85')) {{ 'checked' }}  @endif> understand 65-85 %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <input type="radio" name="grade" value="UNDERSTAND_41_64"
                                                @if (($reportCard->grade == 'UNDERSTAND_41_64')) {{ 'checked' }}  @endif> understand 41-64 %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <input type="radio" name="grade" value="UNDERSTAND_20_40"
                                                @if (($reportCard->grade == 'UNDERSTAND_20_40')) {{ 'checked' }}  @endif> understand 20-40 %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <input type="radio" name="grade" value="UNDERSTAND_0_19"
                                                @if (($reportCard->grade == 'UNDERSTAND_0_19')) {{ 'checked' }}  @endif> understand 0-19 %
                                            </td>
                                        </tr>


                                        <tr>
                                            <td colspan="7">
                                                &nbsp;
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="7">
                                                Tutor Comment <br>
                                                <textarea name="comment" rows="5" cols="70">@if(isset($reportCard->comment)) {{ $reportCard->comment }}  @endif</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="center">
                                                <input type="submit" value="save">
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
