@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-10 py-4">
            <div class="card">
                <div class="card-body">

                    <h6>
                        {{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}
                    </h6>

                    <form action="{{ route("admin.permissions.update", [$permission->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">{{ trans('cruds.permission.fields.title') }}*</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($permission) ? $permission->title : '') }}" required>
                            @if($errors->has('title'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.permission.fields.title_helper') }}
                            </p>
                        </div>

                        <div class="pt-3">
                           
                            <a class="btn btn-primary" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>

                            <input class="btn btn-primary mr-2" type="submit" value="{{ trans('global.save') }}">
                        </div>

                    </form>

   

   

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
