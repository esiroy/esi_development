@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Agent</li>
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
                    Agent List
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-sm-9 col-md-2">Name:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-8 col-md-10" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="small col-sm-9 col-md-2">Email:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm  col-xs-3 col-sm-2 col-md-10" value="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-sm ml-0">Go</button>
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
                                            <th class="small text-center">Agent Name</th>
                                            <th class="small text-center">ID</th>
                                            <th class="small text-center">Member<br />List</th>
                                            <th class="small text-center">First Date of<br />Purchase</th>
                                            <th class="small text-center">Point Purchase<br />History</th>
                                            <th class="small text-center">Point<br />Balance</th>
                                            <th class="small text-center">Expire<br />Data</th>
                                            <th class="small text-center">Purchase<br />Amount</th>
                                            <th class="small text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($agents))
                                        @foreach ($agents as $agent)
                                        <tr data-entry-id="{{ $agent->id }}">
                                            <td class="small text-center">&nbsp;</td>
                                            <td class="small text-center">{{$agent->name_en}}</td>
                                            <td class="small text-center">{{$agent->id}}</td>
                                            <td class="small text-center"><img src="/images/iMemberList.jpg"></td>
                                            <td class="small text-center">{{$agent->initial_date_of_purchase}}</td>
                                            <td class="small text-center"><img src="/images/iHistory.jpg"></td>
                                            <td class="small text-center">{{$agent->credits}}</td>
                                            <td class="small text-center">{{$agent->credits_expiration}}</td>
                                            <td class="small text-center">{{$agent->purchased_amount}}</td>
                                            <td class="small text-center">
                                                <a href="{{ route('admin.agent.account', $agent->id) }}"  class="btn btn-sm btn-info">Account</a>                                                
                                                <a href="{{ route('admin.agent.edit', $agent->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('admin.agent.destroy', $agent->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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
                <div class="card-header">Agent Form</div>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.agent.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> 業種
                            </div>
                            <div class="col-3">
                                <select name="industry_type" class="form-control form-control-sm @error('industry_type') is-invalid @enderror" value="{{ old('industry_type') }}" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach ($industries as $industry)
                                    <option value="{{$industry->id}}" @if (old('industry_type')==$industry->id) {{ 'selected' }} @endif >{{$industry->name }}</option>
                                    @endforeach;
                                    <!--
                                    <option value="PRIVATE_SCHOOL">Private School</option>
                                    <option value="PUBLIC_SCHOOL">Public School</option>
                                    <option value="COMPANY">Company</option>
                                    <option value="INDIVIDUAL">Individual</option>
                                    -->
                                </select>
                                @error('industry_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Email
                            </div>
                            <div class="col-3">
                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Password
                            </div>
                            <div class="col-3">
                                <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Name (English)
                            </div>
                            <div class="col-3">
                                <input id="name_en" type="name_en" class="form-control form-control-sm @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en') }}" required autocomplete="name_en">
                                @error('name_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Name (Japanese)
                            </div>
                            <div class="col-3">
                                <input id="name_jp" type="name_jp" class="form-control form-control-sm @error('name_jp') is-invalid @enderror" name="name_jp" value="{{ old('name_jp') }}" required autocomplete="name_jp">
                                @error('name_jp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> ID
                            </div>
                            <div class="col-3">
                                <input id="id" type="id" class="form-control form-control-sm @error('id') is-invalid @enderror" name="id" value="{{ old('id') }}" required autocomplete="id">
                                @error('id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> 担当者
                            </div>
                            <div class="col-3">
                                <input id="representative" type="representative" class="form-control form-control-sm @error('representative') is-invalid @enderror" name="representative" value="{{ old('representative') }}" required autocomplete="representative">
                                @error('representative')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                ひらがな
                            </div>
                            <div class="col-3">
                                <input type="text" name="hiragana" class="form-control form-control-sm" placeholder="ひらがな Agent Hiragana" value="{{ old('hiragana') }}">
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                Address
                            </div>
                            <div class="col-3">
                                <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                ふりがな
                            </div>
                            <div class="col-3">
                                <input type="text" name="inclination" class="form-control form-control-sm" placeholder="ふりがな Agent Inclination" value="{{ old('inclination') }}">
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span>
                                ポイント購入日
                            </div>
                            <div class="col-3">
                                <input type="date" name="contract_date" class="datepicker form-control form-control-sm @error('contract_date') is-invalid @enderror" required value="{{ old('contract_date') }}">
                                @error('contract_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                備考
                            </div>
                            <div class="col-3">
                                <textarea name="remark" class="form-control" placeholder="備考 Agent Remark">{{ old('remark') }}</textarea>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <button type="clear" class="btn btn-primary btn-sm">Cancel</button>
                            </div>
                        </div>

                    </form>
                </div>
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

        /*@can('tutor_delete')*/

        let deleteButtonTrans = '{{ trans('
        global.datatables.delete ') }}';
        let deleteButton = {
            text: deleteButtonTrans
            , url: "{{ route('admin.agent.massDestroy') }}"
            , className: 'btn-danger'
            , action: function(e, dt, node, config) {
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
        /* @endcan */

        $.extend(true, $.fn.dataTable.defaults, {
            order: [
                [1, 'desc']
            ]
            , pageLength: 100
        , });
        $('#dataTable').DataTable({
            buttons: dtButtons
        })
    });

</script>
@endsection
