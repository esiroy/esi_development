@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-2">
            @include('admin.partials.sidebar')
        </div>

        <div class="folder-container my-4 col-10">
             NEWS 
        </div>
    </div>
</div>
@endsection
