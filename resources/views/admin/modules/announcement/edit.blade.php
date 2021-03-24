@extends('layouts.adminsimple')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/company') }}">Company</a>
            </nav>
        </div>
    </div>

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
                                    <input required type="date"  name="dateFrom" value="{{ old('dateFrom', isset($announcement->date_from ) ? date("Y-m-d", strtotime($announcement->date_from)) : '') }}" class="hasDatepicker">
                                    
                                </td>
                            </tr>

                            <tr valign="top">
                                <td>To</td>
                                <td>:</td>
                                <td>
                                    <input required type="date" id="dateTo*" name="dateTo" value="{{ old('dateTo', isset($announcement->date_to ) ?date("Y-m-d", strtotime($announcement->date_to))  : '') }}" class="hasDatepicker">
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

@section('scripts')
<script src="https://cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>
<script>
  CKEDITOR.replace( 'body' );
</script>
@endsection
        