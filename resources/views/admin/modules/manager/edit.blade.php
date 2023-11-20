@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link tfont-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manager</li>
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

            <div class="card esi-card mt-4">
                <div class="card-header esi-card-header">Update Password</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.manager.resetPassword', $manager->id) }}">
                        @csrf
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" 
                                        value="{{ old('password') }}" required autocomplete="password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm ml-2">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card esi-card mt-4">
                <form method="POST" action="{{ route('admin.manager.update', $manager) }}">
                    @csrf
                    @method('put')

                    <div class="card-header esi-card-header">Edit Manager Form</div>
                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> E-Mail<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" 
                                        value="{{ old('email', isset($manager->email ) ? $manager->email : '') }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="name_en" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name (English)<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="name_en" type="name_en" class="form-control form-control-sm @error('name_en') is-invalid @enderror" name="name_en" 
                                        value="{{ old('name_en', isset($manager->firstname ) ? $manager->firstname  : '') }}" required autocomplete="name_en">
                                        @error('name_en')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="name_jp" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name (Japanese)<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="name_jp" type="name_jp" class="form-control form-control-sm @error('name_jp') is-invalid @enderror" name="name_jp" 
                                            value="{{ old('name_jp ', isset($manager->japanese_firstname ) ? $manager->japanese_firstname  : '') }}" required autocomplete="name_jp">
                                        @error('name_jp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="name_jp" class="px-0 col-md-12 col-form-label">&nbsp; Is Japanese?<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="checkbox" name="is_japanese" class="mt-2" @if($manager->is_japanese == true) {{ "checked" }} @endif>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row py-4">
                            <div class="col-2">&nbsp;</div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                 <input type="reset" value="Cancel" class="btn btn-primary btn-sm">
                            </div>
                        </div>


                    </div>
                 </form>

            </div>
           

        </div>
    </div>
</div>
</div>
@endsection
