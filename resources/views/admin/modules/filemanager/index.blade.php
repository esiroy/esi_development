@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-md-2">
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
                    {{ trans('modules/filemanager.title') }}
                    {{ trans('global.list') }}
                </div>
                <div class="card-body">
                    @can('filemanager_create')
                        <div class="row my-3">
                            <div class="col-lg-12">
                                <a class="btn btn-success"
                                    href="{{ route("admin.module.filemanager.create") }}">
                                    {{ trans('global.add') }}
                                    {{ trans('modules/filemanager.folder') }}
                                </a>
                            </div>
                        </div>
                    @endcan
                    <div class="table-responsive">
                        <table id="dataTableFileManager"
                            class="table table-bordered table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">
                                        <input id="selectAll" type="checkbox" /> <span class="d-none">Select All</span>
                                        
                                    </th>
                                    <th width="30">
                                        {{ trans('global.id') }}
                                    </th>
                                    <th width="150">
                                        {{ trans('modules/filemanager.folder_name') }}
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
                                @foreach($folders as $key => $folder)
                                    <tr data-entry-id="{{ $folder->id }}">
                                        <td></td>
                                        <td>
                                            {{ $folder->id ?? '' }}
                                        </td>
                                      
                                        <td>
                                            {{ $folder->folder_name ?? '' }}
                                           
                                        </td>
                                        <td>
                                            {{ $folder->folder_description ?? '' }}
                                        </td>
                                      
                                        <td>
                                            @can('filemanager_show')
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('admin.module.filemanager.show', $folder->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('filemanager_edit')
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('admin.module.filemanager.edit', $folder->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('filemanager_delete')
                                                <form
                                                    action="{{ route('admin.module.filemanager.destroy', $folder->id) }}"
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

        @can('filemanager_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.module.filemanager.massDestroy') }}",
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

        $('#dataTableFileManager').DataTable({ buttons: dtButtons })


    });
</script>


@endsection