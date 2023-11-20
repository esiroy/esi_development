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
                   List of Members as Main Tutor
                </div>
                <div class="card-body">               
                    @include('admin.modules.tutor.includes.maintutorlist')
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
