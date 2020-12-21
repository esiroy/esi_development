@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/company') }}">Company</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>


        <div class="container">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Company
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Company Email </th>
                                            <th class="small text-center bg-light text-dark font-weight-bold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($companies as $company)
                                        <tr>
                                            <th class="small text-center">{{ $company->email }}</th>

                                            <th class="small text-center">
                                                <a href="{{ route('admin.company.destroy', ['company' => $company]) }}" 
                                                    class="red"
                                                    onclick="event.preventDefault();document.getElementById('delete-form-{{ $company->id }}').submit();">Delete
                                                </a>
                                                <form id="delete-form-{{ $company->id }}" action="{{ route('admin.company.destroy', ['company' => $company]) }}" method="POST" style="display: none;">
                                                    @method("DELETE")
                                                    @csrf
                                                </form>

                                            </th>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="card esi-card">
                                <div class="card-header esi-card-header">
                                  
                                    Add Company
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.company.store') }}">
                                        @csrf
                                        <input required id="email"  name="email"  type="email" class="form-control col-md-4 form-control-sm @error('company') 
                                        is-invalid @enderror"value="{{ old('company') }}" required autocomplete="company">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!--[end] card-->


    </div>




</div>
</div>

</div>
@endsection
