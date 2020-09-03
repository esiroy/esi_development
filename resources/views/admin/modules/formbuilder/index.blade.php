@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 pl-0">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-md-10 py-4">

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    {{ trans('modules/formbuilder.title') }}
                    {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    @can('formbuilder_create')
                    <div class="row my-3">
                        <div class="col-lg-12">
                            <a class="btn btn-success" href="{{ route("admin.module.formbuilder.create") }}">{{ trans('global.add') }} {{ trans('modules/formbuilder.title') }}</a>
                        </div>
                    </div>
                    @endcan



                    <div class="table-responsive">
                        <table id="dataTableformbuilder" class="table table-bordered table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">
                                        <input id="selectAll" type="checkbox" /> <span class="d-none">Select All</span>

                                    </th>
                                    <th width="30">
                                        {{ trans('global.id') }}
                                    </th>
                                    <th width="150">
                                        {{ trans('modules/formbuilder.title') }}
                                    </th>
                                    <th>
                                        {{ trans('global.description') }}
                                    </th>

                                    <th width="20%">
                                        {{ trans('global.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($forms as $key => $form)
                                <tr data-entry-id="{{ $form->id }}">
                                    <td>
                                    
                                    </td>
                                    <td>
                                        {{ $form->id ?? '' }}
                                    </td>

                                    <td>
                                        {{ $form->form_name ?? '' }}

                                    </td>
                                    <td>
                                        {{ $form->form_description ?? '' }}
                                    </td>

                                    <td>
                                        @can('formbuilder_show')
                                        <a class="btn btn-sm btn-primary" href="{{ route('admin.module.formbuilder.show', $form->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                        @endcan

                                        @can('formbuilder_edit')
                                        <a class="btn btn-sm btn-info" href="{{ route('admin.module.formbuilder.edit', $form->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                        @endcan

                                        @can('formbuilder_delete')
                                        <form action="{{ route('admin.module.formbuilder.destroy', $form->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                    <!--@end: class=table-responsive-->
                </div>

            </div>


        </div>
    </div>


    @endsection


    @section('scripts')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    @endsection
