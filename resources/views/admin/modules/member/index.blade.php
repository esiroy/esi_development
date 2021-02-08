@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    @include('admin.modules.member.includes.menu')

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
                                    <input name="member_id" type="text" class="form-control form-control-sm col-8"
                                     value="{{ request()->has('member_id') ? request()->get('member_id') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="small col-4">Name:</label>
                                    <input name="name" type="text" class="form-control form-control-sm col-8" 
                                        value="{{ request()->has('name') ? request()->get('name') : '' }}">
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
                        <div class="col-5 pt-3">
                            @can('report_access', Auth::user())
                                <span data-href="/exportCSV" id="export" class="btn btn-primary btn-sm" onclick="exportTasks(event.target); return false">Generate Member List</span>
                            @endcan

                            @if (strtolower(Auth::user()->user_type) == 'admin' || strtolower(Auth::user()->user_type) == 'administrator')
                            <a href="{{ url('admin/member?toexpire=true') }}"><button type="button" class="btn btn-primary btn-sm">Sort Soon to Expire</button></a>
                            <a href="{{ url('admin/member?expired=true') }}"><button type="button" class="btn btn-primary btn-sm">Sort Expired</button></a>
                            @endif
                        </div>
                          <div class="col-7">   
                            <div class="float-right mt-3">
                                <ul class="pagination pagination-sm">
                                    {{ $members->appends(request()->query())->links() }}           
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!--[start] Member List -->
                    <div class="row">
                        <div class="col-12 pt-3">                            
                            @include('admin.modules.member.includes.memberlist')     
                        </div>
                    </div>
                    <!--[end] Member List -->

                </div>
            </div><!--[end card]-->        
            
            <!--Member List -->
            @if ($can_member_create)
            <member-create-component                
                :memberships="{{ json_encode($memberships) }}"
                :attributes="{{ json_encode($attributes) }}"
                :shifts="{{ json_encode($shifts) }}"

                :can_member_access="{{ $can_member_access }}"
                :can_member_create="{{ $can_member_create }}"                
                :can_member_edit="{{ $can_member_edit }}"
                :can_member_delete="{{ $can_member_delete }}"
                :can_member_view="{{ $can_member_view }}"

                api_token="{{ Auth::user()->api_token }}"
                csrf_token="{{ csrf_token() }}"             
            />
            @endif

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

@section('styles')
@parent
<style>
.dataTables_filter {
    display: none;
    float: left !important;
    padding-left: 20px;
}
</style>
@endsection


@section('scripts')
@parent
<script type="text/javascript">

    function exportTasks(_this) {
        let _url = $(_this).data('href');
        window.location.href = _url;
    }

    window.addEventListener('load', function() {

        //let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        let dtButtons = $.extend(true, [], [])
        let _token = "{{ csrf_token() }}"  
        
        $.extend(true, $.fn.dataTable.defaults, {
            order: [[0, 'DES']],
            pageLength: 1000,
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false      
                },
                {
                    "targets": [ 1 ],
                    "width": "5%"
                },              
                {
                    "targets": [ 2 ],
                    "width": "5%"
                },                         
                {
                    "targets": [ 6 ],
                    "width": "10"
                },                
            ] 
        });

        
        $('#dataTable').DataTable({
            "buttons": dtButtons,
            "paging":   false,
            fixedColumns: true
        })
    });

</script>
@endsection