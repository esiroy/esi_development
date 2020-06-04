@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8">
           
            <div class="card">
                <div class="card-header"> Files</div>
                <div class="card-body">
                    @foreach ($files as $file)
                    <div class="row mb-3">
                        <div class="col-md-9">
                            {{ $file->file_name }}
                        </div>
                  
                        <div class="col">
                            <a href="{{ asset($file->path) }}" target="_blank">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i>View</button>
                            </a>
                            <a href="{{ asset($file->path) }}" download="{{ $file->file_name }}">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i>Download</button>
                            </a>
                        </div>
                    </div>
                    @endforeach 
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Folder Details</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-5 small text-muted">
                                Folder
                            </div>
                            <div class="col-md-7 small text-muted">
                            {{ $folder->folder_name }}
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-5 small text-muted">
                                Description
                            </div>
                            <div class="col-md-7 small text-muted">
                            {{ $folder->folder_description }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection