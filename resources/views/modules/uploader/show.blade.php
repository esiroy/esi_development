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

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Upload Files</div>
                <div class="card-body">
                    <simple-uploader-component folder_id="{{ $folder->id }}" csrf_token="{{ csrf_token() }}"/>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">All Files</div>
                <div class="card-body">
                    <folder-files-component  v-bind:folder_files="{{ json_encode($files) }}"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Details

                    <div class="float-right">
                        <a href="{{ asset('folder/' . $folder->folder_name) }}" target="_blank">
                            <svg class="bi bi-link" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                                <path d="M6.764 6.5H7c.364 0 .706.097 1 .268A1.99 1.99 0 0 1 9 6.5h.236A3.004 3.004 0 0 0 8 5.67a3 3 0 0 0-1.236.83z"/>
                                <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
                                <path d="M8 11.33a3.01 3.01 0 0 0 1.236-.83H9a1.99 1.99 0 0 1-1-.268 1.99 1.99 0 0 1-1 .268h-.236c.332.371.756.66 1.236.83z"/>
                            </svg>
                        </a>
                    </div>
                </div>
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
@endsection