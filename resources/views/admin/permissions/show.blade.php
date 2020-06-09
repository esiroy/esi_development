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
                        {{ trans('global.show') }}
                        {{ trans('cruds.permission.title') }}
                    </h6>

                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('cruds.permission.fields.id') }}
                                </th>
                                <td>
                                    {{ $permission->id }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.permission.fields.title') }}
                                </th>
                                <td>
                                    {{ $permission->title }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a style="margin-top:20px;" class="btn btn-primary" href="{{ url()->previous() }}">
                        {{ trans('global.back_to_list') }}
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
