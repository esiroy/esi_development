@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    @include('admin.modules.tutor.includes.menu')

    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>

        <div class="container">
            <!--Member List -->

            <!--[start card] -->
            <div class="card esi-card mt-5">
                <div class="card-header esi-card-header">
                    Member List
                </div>
                <div class="card-body">
                    <div class="row">
                        <form class="form-inline" style="width:100%" method="GET">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="member_id" class="small col-4">ID:</label>
                                    <input name="member_id" type="text" class="form-control form-control-sm col-8" value="{{ request()->has('member_id') ? request()->get('member_id') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="small col-4">Name:</label>
                                    <input name="name" type="text" class="form-control form-control-sm col-8" value="{{ request()->has('name') ? request()->get('name') : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="small col-2">Email:</label>
                                    <input name="email" type="text" class="form-control form-control-sm col-4" value="{{ request()->has('email') ? request()->get('email') : '' }}">

                                    <input type="submit" class="btn btn-primary btn-sm col-1 ml-1" value="Go"></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            @can('report_access', Auth::user())
                            <button type="button" class="btn btn-primary btn-sm">Generate Member List</button>
                            @endcan
                            <button type="button" class="btn btn-primary btn-sm">Sort Soon to Expire</button>
                            <button type="button" class="btn btn-primary btn-sm">Sort Expired</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            @include('admin.modules.tutor.includes.maintutorlist')
                        </div>
                    </div>
                </div>
            </div>
            <!--[end card]-->
        </div>

    </div>
</div>
@endsection

@section('styles')
@parent
<style>
    table.dataTable thead>tr>td.sorting,
    table.dataTable thead>tr>td.sorting_asc,
    table.dataTable thead>tr>td.sorting_desc,
    table.dataTable thead>tr>th.sorting,
    table.dataTable thead>tr>th.sorting_asc,
    table.dataTable thead>tr>th.sorting_desc {
        padding-right: 0px;
    }
</style>

@endsection

@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        let _token = "{{ csrf_token() }}"


        @can('member_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete ') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.tutor.massDestroy') }}",
            className: 'btn-danger',
            action: function(e, dt, node, config) {
                var ids = $.map(dt.rows({
                    selected: true
                }).nodes(), function(entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('
                        global.datatables.zero_selected ') }}')
                    return
                }

                if (confirm('{{ trans('
                        global.areYouSure ') }}')) {
                    $.ajax({
                        headers: {
                            'x-csrf-token': _token
                        }
                        , method: 'POST'
                        , url: config.url
                        , data: {
                            ids: ids
                            , _method: 'DELETE'
                        }
                    }).done(function() {
                        location.reload()
                    })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan



        $.extend(true, $.fn.dataTable.defaults, {
            order: [
                [1, 'desc']
            ]
            , pageLength: 100
        , });
        $('#dataTable').DataTable({
            "buttons": dtButtons
            , "paging": false

        })
    });

</script>
@endsection
