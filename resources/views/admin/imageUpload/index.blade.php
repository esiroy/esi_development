
@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    @include('admin.modules.member.includes.menu')

        <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crop Image</li>
            </ol>
        </nav>

        <div class="container mt-4">
            <div class="card esi-card">
                <div class="card-header esi-card-header">Update Member Form</div>
                <div class="card-body">
                    @if ($userImage == null)
                        <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" >
                    @else 
                        <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                    @endif
                </div>
            </div>
        </div>
</div>

@endsection

