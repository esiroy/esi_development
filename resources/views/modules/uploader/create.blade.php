@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Folders
                    <div class="float-right">
                        <a href="{{ URL::route('uploader.index') }}">
                            <svg class="bi bi-arrow-left-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
                            <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="folders" >
                        @foreach ($folders as $folder)
                            <div class="mx-2">
                                <a href="{{ URL::to('/uploader/'. $folder->folder_name ) }}">
                                    <svg class="bi bi-folder" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z"/>
                                        <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z"/>
                                    </svg>
                                </a>
                                <a href="{{ URL::to('/uploader/'. $folder->folder_name ) }}">{{ $folder->folder_name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
        
            <div class="card">
                <div class="card-header">Create New Folder</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('uploader.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('Folder Name') }}</label>

                            <div class="col-md-6">
                                <input id="folder_name" type="text" 
                                    class="form-control @error('folder_name') is-invalid @enderror" 
                                    name="folder_name" value="{{ old('folder_name') }}" 
                                    required autocomplete="folder_name" autofocus>

                                @error('folder_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="folder_description" class="col-md-4 col-form-label text-md-right">{{ __('Folder Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="folder_name" type="text" 
                                    class="form-control @error('folder_description') is-invalid @enderror" 
                                    name="folder_description" value="{{ old('folder_description') }}" 
                                    autocomplete="folder_description" autofocus cols="20" rows="5"></textarea>

                                @error('folder_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Folder') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection