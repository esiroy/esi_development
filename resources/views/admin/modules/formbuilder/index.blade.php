@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 pl-0">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-md-10 py-4">
            <formbuilder-component />
            {{ "testing mode: version 0.1" }}
        </div>
    </div>
</div>
@endsection
