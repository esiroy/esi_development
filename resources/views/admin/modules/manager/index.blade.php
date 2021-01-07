@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link tfont-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manager</li>
            </ol>
        </nav>

        <div class="container">
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @elseif (session('error_message'))
                <div class="alert alert-danger">
                    {{ session('error_message') }}
                </div>
            @endif


            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Manager List
                </div>
                <div class="card-body">
                    <div class="row">
                        <form class="form-inline" style="width:100%">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Username:</label>
                                    <input id="nickname" name="nickname" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Name:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-5" value="">
                                    <button type="button" class="btn btn-primary btn-sm col-1 ml-1">Go</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="table-responsive">
                                <table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="small text-center">&nbsp;</th>                     
                                        <th class="small text-center">ID</th>
                                        <th class="small text-center">Name</th>                    
                                        <th class="small text-center">Username</th>
                                        <th class="small text-center">E-Mail</th>
                                        <th class="small text-center">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($managers))
                                    @foreach ($managers as $manager)
                                    <tr data-entry-id="{{ $manager->id }}">
                                        <td class="small text-center">&nbsp;</td>   
                                        <td class="small text-center">{{$manager->id}}</td>
                                        <td class="small text-center">{{$manager->firstname ?? "" }} {{$manager->lastname ?? "" }}</td>
                                        <td class="small text-center">{{$manager->username}}</td>
                                        <td class="small text-center">{{$manager->email}}</td>            
                                        <td class="small text-center">                                                 
                                            <a href="{{ route('admin.manager.edit', $manager->id) }}" class="btn btn-sm btn-info">Edit</a>  
                                            <form action="{{ route('admin.manager.destroy', $manager->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                </table>
                            </div>
                                                        
                        </div>
                    </div>
                </div>
            </div>
            <!--[end] card-->

            <div class="card mt-4">
                <form method="POST" action="{{ route('admin.manager.store') }}">

                @csrf

                <div class="card-header">Manager Form</div>

                <div class="card-body">
                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> E-Mail<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_en" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name (English)<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input id="name_en" type="name_en" class="form-control form-control-sm @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en') }}" required autocomplete="name_en">
                                    @error('name_en')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_jp" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name (Japanese)<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input id="name_jp" type="name_jp" class="form-control form-control-sm @error('name_jp') is-invalid @enderror" name="name_jp" 
                                        value="{{ old('name_jp') }}" required autocomplete="name_jp">
                                    @error('name_jp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="name_jp" class="px-0 col-md-12 col-form-label">&nbsp; Is Japanese?<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="checkbox" name="is_japanese" class="mt-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row py-4">
                        <div class="col-2"></div>
                        <div class="col-3 text-left">
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            <button type="clear" class="btn btn-primary btn-sm">Cancel</button>
                        </div>
                    </div>


                </div>

                </form>

            </div>




        </div>
    </div>

</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        let _token = "{{ csrf_token() }}"

        /*@can('manager_delete')*/

        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = { 
            text: deleteButtonTrans, 
            url: "{{ route('admin.manager.massDestroy') }}",
            className: 'btn-danger',
            action: function(e, dt, node, config) {
                var ids = $.map(dt.rows({
                    selected: true
                }).nodes(), function(entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure ') }}')) 
                {
                    $.ajax({
                        headers: {'x-csrf-token': _token}, method: 'POST', url: config.url, 
                        data: {
                            ids: ids,
                            _method: 'DELETE'
                        }
                    }).done(function() {
                        location.reload()
                    })
                }
            }
        }
        dtButtons.push(deleteButton)
        /* @endcan */

        $.extend(true, $.fn.dataTable.defaults, {order: [ [1, 'desc']], pageLength: 100,});
        $('#dataTable').DataTable({
            buttons: dtButtons
        })
    });

</script>
@endsection