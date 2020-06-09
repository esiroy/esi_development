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
                    
                    {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
                
                </div>
                
                <div class="card-body">



                    @can('permission_create')
                    <div class="row my-3">
                        <div class="col-lg-12">
                            <a class="btn btn-success" href="{{ route("admin.permissions.create") }}">
                                {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
                            </a>
                        </div>
                    </div>
                    @endcan


                    <div class="table-responsive">
                        <table id="dataTablePermission" class="table table-bordered table-striped table-hover datatable">
                            <thead>
                            <tr>
                                <th width="50" class="text-center">
                                    <input type="checkbox"/> <span class="d-none">Select All</div>
                                </th>
                                <th width="30">
                                    {{ trans('cruds.permission.fields.id') }}
                                </th>
                                <th>
                                    {{ trans('cruds.permission.fields.title') }}
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $key => $permission)
                                <tr data-entry-id="{{ $permission->id }}">
                                    <td></td>
                                    <td>
                                        {{ $permission->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $permission->title ?? '' }}
                                    </td>
                                    <td>
                                        @can('permission_show')
                                            <a class="btn btn-sm btn-primary" href="{{ route('admin.permissions.show', $permission->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('permission_edit')
                                            <a class="btn btn-sm btn-info" href="{{ route('admin.permissions.edit', $permission->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('permission_delete')
                                            <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection