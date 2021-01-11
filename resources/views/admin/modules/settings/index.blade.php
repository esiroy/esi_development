@extends('layouts.admin')

@section('content')
<div class="container bg-light">
    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Settings</li>
            </ol>
        </nav>

        <!--[start] container -->
        <div class="container">
            @include('includes.session.message')
            <!--[start] asi-card-->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    User Settings
                </div>
                <div class="card-body">
                    <!--[start] change password-->
                    @include('admin.modules.settings.includes.formPassword')
                    <!--[end] change password-->
                    
                    <!--[start] change user details-->
                    @include('admin.modules.settings.includes.changeUserDetails')
                    <!--[end] change user details-->
                </div>
            </div>
            <!--[end] card-->
        </div>
        <!--[end] container-->

    </div>
</div>
@endsection
