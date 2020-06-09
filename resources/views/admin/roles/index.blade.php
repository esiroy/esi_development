@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 pl-0">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-md-8 py-4">
            <div class="card">
                <div class="card-header">Roles</div>
                <div class="card-body"></div>
            </div>
        </div>
    </div>
</div>
@endsection
