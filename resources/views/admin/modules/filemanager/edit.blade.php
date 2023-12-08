@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-10 py-4">
            <div class="card">

                <div class="card-header">
                    {{ trans('global.create') }}
                    {{ trans('modules/filemanager.folder_singular') }}
                </div>

                <div class="card-body">

                    <form method="POST"
                        action="{{ route('admin.module.filemanager.update', $folder->id) }}">

                        @method('PUT')

                        @csrf

                        <div class="form-group row">
                            <label for="first_name"
                                class="col-md-4 col-form-label text-md-right">{{ __('Folder Name') }}</label>

                            <div class="col-md-6">
                                <input id="folder_name" type="text"
                                    class="form-control @error('folder_name') is-invalid @enderror" name="folder_name"
                                    value="{{ (old('folder_name')) ? old('folder_name') : $folder->folder_name }}"
                                    required autocomplete="folder_name" autofocus>

                                @error('folder_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="folder_description"
                                class="col-md-4 col-form-label text-md-right">{{ __('Folder Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="folder_name" type="text"
                                    class="form-control @error('folder_description') is-invalid @enderror"
                                    name="folder_description" autocomplete="folder_description" autofocus cols="20"
                                    rows="5">{{ (old('folder_description'))? old('folder_description') : $folder->folder_description }}</textarea>

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
                                    {{ __('Update Folder') }}
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
