@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Folders</div>
                <div class="card-body">
                    <a href="{{ URL::route('uploader.index') }}"><< Back</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload Folder</div>
                <div class="card-body">
                  
                <simpleuploader-component/>

                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection