@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-3"></div>
        <div class="col-md-9">
            <formbuilder-component/>

            <div class="card">
                <div class="card-header">{{ __('Form Builder') }}</div>

                <div class="card-body">
                    (( TEST MODE ))

                    <formbuilder-component/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
