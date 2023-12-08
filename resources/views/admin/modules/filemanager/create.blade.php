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

                    <form action="{{ route("admin.module.filemanager.store") }}" method="POST">
                        @csrf
                        <div class="form-group {{ $errors->has('folder_name') ? 'has-error' : '' }}">

                            <label for="folder_name">
                                {{ trans('modules/filemanager.folder_name') }}
                            </label>

                            <input type="text" id="folder_name" name="folder_name" class="form-control"
                                value="{{ old('folder_name', isset($folder->folder_name) ? $folder->folder_name : '') }}" required>
                            @if($errors->has('folder_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('folder_name') }}
                            </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('modules/filemanager.folder_helper') }}
                            </p>
                        </div>
                        <div>
                            <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
                            <a class="btn btn-danger" href="{{ route('admin.module.filemanager.index') }}">
                                {{ trans('global.cancel') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection