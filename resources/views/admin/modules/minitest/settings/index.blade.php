<!--

@page : Question Index page

-->
@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">


    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left-0 border-primary text-white" href="{{ url('admin/minitest/category/type') }}">Question Category Types</a>               
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary text-white" href="{{  url('admin/minitest/categories') }}">Question Categories</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left-0 border-primary active" href="{{ url('admin/minitest/category/settings') }}">Settings</a>               
                
            </nav>
            
        </div>
    </div>

    
    <div class="esi-box">   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/admin/minitest/categories') }}">Minitest</a></li>     
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
               
            </ol>
        </nav>

        <div class="container">

            @if (session('message'))
                <div class="alert alert-success">
                    {!! session('message') !!}
                </div>
            @elseif (session('error_message'))
                <div class="alert alert-danger">
                    {!! session('error_message') !!}
                </div>
            @endif

            <div class="card esi-card mt-4">
            
               <!-- add question -->

                <form method="POST" action="{{ route('admin.minitest.settings.store') }}">
                    @csrf

                    <div class="card-header esi-card-header">Update Mini-Test Settings</div>

                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="limit" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Limit <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="limit" type="limit" class="form-control form-control-sm @error('name') is-invalid @enderror" name="limit" 
                                        value="{{ old('limit', isset($miniTestLimit) ? $miniTestLimit: '') }}" 
                                        required autocomplete="limit">
                                        @error('limit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="duration" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Duration in Days <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="duration" type="duration" class="form-control form-control-sm @error('name') is-invalid @enderror" name="duration" 
                                         value="{{ old('duration', isset($miniTestDuration) ? $miniTestDuration: '') }}" 
                                        required autocomplete="duration">
                                        @error('duration')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>                                
                            </div>
                        </div>

                      
                        <div class="row py-4">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <a href="{{ route('admin.minitest.categories.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>


                    </div>

                </form>

            </div>




        </div>
    </div>

</div>
@endsection

@section('styles')
@parent

@endsection

@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() {
      
    });

</script>
@endsection