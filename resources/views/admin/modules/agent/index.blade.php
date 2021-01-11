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
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Agent List
                </div>
                <div class="card-body">
                    <div class="row">

                        <!--search-->
                        <form class="form-inline" style="width:100%" method="GET">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="username" class="small col-4">Username:</label>
                                    <input id="username" name="username" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="small col-sm-9 col-md-2">Name:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-8 col-md-10" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email" class="small col-sm-9 col-md-2">Email:</label>
                                    <input id="searchEmail" name="email" type="text" class="form-control form-control-sm  col-xs-3 col-sm-2 col-md-10" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-primary btn-sm col-2" value="Go"></button>        
                            </div>
                        </form>
                        <!--[end] search-->

                    </div>

                    <!--start agent list -->
                    <div class="row">
                        <div class="col-12 pt-3">
                             @include('admin.modules.agent.includes.agentlist')     
                        </div>
                    </div>
                </div>
            </div>
            <!--[end] card-->

            <div class="card esi-card mt-4">
                <div class="card-header esi-card-header">Agent Form</div>
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
                                        <option value="{{$industry['value']}}" @if (old('industry_type')==$industry['value']) {{ 'selected' }} @endif >{{$industry['name'] }}</option>
                                    @endforeach;
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
        
        $.extend(true, $.fn.dataTable.defaults, {
            order: [[0, 'DES']],
            pageLength: 25,
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }]
        });

        $('#dataTable').DataTable({
            buttons: dtButtons,
            "paging":   true         
        })
    });

</script>
@endsection
