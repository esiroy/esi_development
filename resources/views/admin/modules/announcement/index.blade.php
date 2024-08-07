@extends('layouts.admin')

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

        <div class="container">

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif


            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Announcements
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">Page :</small>
                                    {{ $announcements->appends(request()->query())->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="table-responsive ">
                                <table class="table table-bordered table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="small text-center bg-light text-dark font-weight-bold">From </th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">To </th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Announcement</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Hidden</th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($announcements as $announcement)
                                        <tr>
                                            <th class="small text-center">{{ $announcement->date_from }}</th>
                                            <th class="small text-center">{{ $announcement->date_to }}</th>
                                            <th class="small text-center">
                                                {!! html_entity_decode($announcement->body) !!}

                                            </th>
                                            <th class="small text-center">{{ $announcement->is_hidden ? 'true' : 'false' }}</th>
                                            <th class="small text-center">
                                                <a href="{{ route('admin.announcement.edit', $announcement->id)}}">Edit</a> |
                                               
                                                <a href="{{ route('admin.announcement.destroy', ['announcement' => $announcement]) }}" onclick="confirmSubmitAction('delete-form-{{ $announcement->id }}'); return false; ">Delete
                                                </a>
                                                <form id="delete-form-{{ $announcement->id }}" action="{{ route('admin.announcement.destroy', ['announcement' => $announcement]) }}" method="POST" style="display: none;">
                                                    @method("DELETE")
                                                    @csrf
                                                </form>
                                            </th>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">Page :</small>
                                    {{ $announcements->appends(request()->query())->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!--[end] card-->
        </div>


        <div class="container mt-4">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Announcements
                </div>
                <div class="card-body">

                

                    <form action="{{ route("admin.announcement.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table cellspacing="9" cellpadding="0" class="table table-borderless">
                            <tbody>
                                <tr valign="top">
                                    <td>Announcement</td>
                                    <td>:</td>
                                    <td>
                                        <textarea required style="width: 450px; height: 300px; display: none;" id="body" name="body"></textarea>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td>From</td>
                                    <td>:</td>
                                    <td>
                                        <input required type="date" id="dateFrom*" name="dateFrom" data-date-format="YYYY年 M月 DD日" value="" class="inputDate form-control form-control-sm col-2" style="min-width:150px">
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td>To</td>
                                    <td>:</td>
                                    <td>
                                        <input required type="date" id="dateTo*" name="dateTo" data-date-format="YYYY年 M月 DD日" value="" class="inputDate form-control form-control-sm col-2" style="min-width:150px">
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td></td>
                                    <td></td>
                                    <td colspan="2"><input type="checkbox" id="isHidden" name="isHidden"> Hide this announcement </td>
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
    function confirmSubmitAction(formID) {
        let confirmAction = confirm("Are you sure to execute this action?");
        if (confirmAction) {            
            document.getElementById(formID).submit();            
        } else {             
            return false;
        }
    }

    window.addEventListener('load', function() 
    {        
        $(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        });

        //editor
        CKEDITOR.replace('body');
    });


</script>
@endsection
