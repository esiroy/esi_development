@extends('layouts.adminsimple')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/company') }}">Company</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Course</li>
            </ol>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
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
            </div>
        </div>

        <div class="container pt-4">
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Category Form
                </div>
                <div class="card-body esi-card-body">

                    <form action="{{ route("admin.course.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table cellspacing="9" cellpadding="0" class="table table-borderless">
                            <tbody>

                                <tr valign="top">
                                    <td>Parent Category</td>
                                    <td>:</td>
                                    <td>
                                        <select name="parentid">
                                            <option value="">-- Select --</option>
                                            @foreach($categories as $category)
                                            <option value="">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>


                                <tr valign="top">
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>
                                        <input required type="text" id="name*" name="name">
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td>Category Description</td>
                                    <td>:</td>
                                    <td>
                                        <textarea id="body" name="body"></textarea>
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

</div>
@endsection


@section('scripts')
<script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('body');

</script>
@endsection
