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
                    {{ trans('modules/formbuilder.title') }}
                </div>

                <div class="card-body">

                    <form action="{{ route("admin.module.formbuilder.store") }}" method="POST">
                        @csrf

                        <!--[Start] Form Description-->
                        <div class="form-group {{ $errors->has('form_name') ? 'has-error' : '' }}">
                            <label for="form_name">
                                {{ trans('modules/formbuilder.title') }}
                            </label>

                            <input type="text" id="form_name" name="form_name" class="form-control" value="{{ old('form_name', isset($folder->form_name) ? $folder->form_name : '') }}" required>

                            @if($errors->has('form_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('form_name') }}
                            </em>
                            @endif

                            <p class="helper-block">
                                {{ trans('modules/formbuilder.form_name_helper') }}
                            </p>
                        </div>

                        <!--[Start] Form Description-->
                        <div class="form-group {{ $errors->has('form_description') ? 'has-error' : '' }}">
                            <label for="form_description">
                                {{ trans('modules/formbuilder.description') }}
                            </label>

                            <textarea id="form_description" name="form_description" class="form-control">{{ old('form_description', isset($folder->form_description) ? $folder->form_description : '') }}</textarea>

                            @if($errors->has('form_description'))
                            <em class="invalid-feedback">
                                {{ $errors->first('form_description') }}
                            </em>
                            @endif

                            <p class="helper-block">
                                {{ trans('modules/formbuilder.form_description_helper') }}
                            </p>
                        </div>

                        <div>
                            <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
                            <a class="btn btn-danger" href="{{ route('admin.module.formbuilder.index') }}">
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
