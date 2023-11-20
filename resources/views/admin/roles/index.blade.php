@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-2">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-10 py-4">

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
                    {{ trans('cruds.role.title_singular') }}
                    {{ trans('global.list') }}
                </div>
                <div class="card-body">
                    @can('role_create')
                        <div class="row my-3">
                            <div class="col-lg-12">
                                <a class="btn btn-success"
                                    href="{{ route("admin.roles.create") }}">
                                    {{ trans('global.add') }}
                                    {{ trans('cruds.role.title_singular') }}
                                </a>
                            </div>
                        </div>
                    @endcan
                    <div class="table-responsive">
                        <table id="dataTablePermission"
                            class="table table-bordered table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">
                                        <input id="selectAll" type="checkbox" /> <span class="d-none">Select All</span>
                                        
                                    </th>
                                    <th width="30">
                                        {{ trans('cruds.permission.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.permission.fields.title') }}
                                    </th>
                                    <th width="40%">
                                        {{ trans('cruds.role.fields.permissions') }}
                                    </th>
                                    <th>
                                    {{ trans('global.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $key => $role)
                                    <tr data-entry-id="{{ $role->id }}">
                                        <td></td>
                                        <td>
                                            {{ $role->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $role->title ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($role->permissions as $key => $item)
                                                <span class="badge badge-info">{{ $item->title }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('role_show')
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('admin.roles.show', $role->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('role_edit')
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('admin.roles.edit', $role->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('role_delete')
                                                <form
                                                    action="{{ route('admin.roles.destroy', $role->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger"
                                                        value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!--@end: class=table-responsive-->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">    
    window.addEventListener('load', function () 
    {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        let _token = "{{ csrf_token() }}"

        @can('role_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.roles.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) 
                {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { 
                                ids: ids, 
                                _method: 'DELETE' 
                            }
                        }).done(function () { 
                            location.reload() 
                        })
                    }
                }
            }
            dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });

        $('#dataTablePermission').DataTable({ buttons: dtButtons })

        /*
        var table = $('#dataTablePermission').DataTable( {
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'csv', 'pdf', 'print', 'colvis' ]
        } );

        table.buttons().container().appendTo( '#dataTablePermission_wrapper .col-md-6:eq(0)' );
        */


    });
</script>


@endsection