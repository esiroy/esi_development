@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    @include('admin.menus.manage')

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Announcements</li>
            </ol>
        </nav>

      
        <div class="container mt-4">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Announcements
                </div>
                <div class="card-body">

                    <form action="{{ route("admin.announcement.update", $announcement->id ) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <table cellspacing="9" cellpadding="0" class="table table-borderless">
                        <tbody>
                            <tr valign="top">
                                <td>Announcement</td>
                                <td>:</td>
                                <td>                                    
                                    <textarea required style="width: 450px; height: 300px; display: none;" id="body" name="body">{{ old('body', isset($announcement->body ) ? $announcement->body : '') }}</textarea>
                                </td>
                            </tr>

                            <tr valign="top">
                                <td>From</td>
                                <td>:</td>
                                <td>
                                    <input required type="date"  name="dateFrom" value="{{ old('dateFrom', isset($announcement->date_from ) ? date("Y-m-d", strtotime($announcement->date_from)) : '') }}"  data-date-format="YYYY年 M月 DD日" class="inputDate form-control form-control-sm col-2" style="min-width:150px">
                                    
                                </td>
                            </tr>

                            <tr valign="top">
                                <td>To</td>
                                <td>:</td>
                                <td>
                                    <input required type="date" id="dateTo*" name="dateTo" value="{{ old('dateTo', isset($announcement->date_to ) ?date("Y-m-d", strtotime($announcement->date_to))  : '') }}"  data-date-format="YYYY年 M月 DD日" class="inputDate form-control form-control-sm col-2" style="min-width:150px">
                                </td>
                            </tr>

                            <tr valign="top">
                                <td></td>
                                <td></td>
                                <td colspan="2"><input type="checkbox" id="isHidden" name="isHidden" @if(old('isHidden') == '1' || $announcement->is_hidden == '1') {{ 'checked' }} @endif> Hide this announcement </td>
                            </tr>

                            <tr valign="top">
                                <td></td>
                                <td></td>
                                <td colspan="4">
                                    <input type="checkbox" name="usertypes[]" value="ADMINISTRATOR"> Administrator 
                                    <input type="checkbox" name="usertypes[]" value="MANAGER"> MANAGER 
                                    <input type="checkbox" name="usertypes[]" value="MEMBER"> Member 
                                    <input type="checkbox" name="usertypes[]" value="AGENT"> Agent 
                                    <input type="checkbox" name="usertypes[]" value="TUTOR"> Tutor 
                                </td>
                            </tr>

                            <tr valign="top">
                                <td></td>
                                <td></td>
                                <td>
                                    <input type="submit" value="Save" class="btnPink">
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
@endsection


@section('styles')
@parent
<style>
    input.inputDate {}

    input.inputDate:before {
        content: attr(data-date);
    }

    input.inputDate::-webkit-datetime-edit,
    input.inputDate::-webkit-inner-spin-button,
    input.inputDate::-webkit-clear-button {
        display: none;
    }

    input.inputDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }

</style>
@endsection

@section('scripts')
<script src="https://mypage.mytutor-jpn.com/js/ckeditor/ckeditor.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
<script type="text/javascript">
    window.addEventListener('load', function() 
    {        
      
        $(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")
  

        //editor
        CKEDITOR.replace('body');

    });
</script>
@endsection
