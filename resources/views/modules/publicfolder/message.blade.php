@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12 my-auto">
            <div class="d-flex justify-content-center">
                <div class="alert alert-danger" role="alert">
                    <div class="text-center">{{ $message }}<div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection