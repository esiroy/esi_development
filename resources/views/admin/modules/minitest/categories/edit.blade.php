@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">



    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                 <li class="breadcrumb-item"><a href="{{ url('/admin/minitest/categories') }}">Minitest</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit - {{ $item->name }}</li>
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
                    Minitest Category Edit
                </div>
                <div class="card-body">
            
                    <form method="POST" action="{{ route('admin.minitest.categories.update', $item->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="name" type="name" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" 
                                        value="{{ old('name', isset($item->name ) ? $item->name : '') }}" required autocomplete="name">
                                        @error('name')
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
                                        <label for="instructions" class="px-0 col-md-12 col-form-label">                                        
                                        Instructions<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="instructions" type="text" class="form-control form-control-sm @error('instructions') is-invalid @enderror" name="instructions" 
                                        value="{{ old('instructions', isset($item->instructions ) ? $item->instructions : '') }}"
                                        >
                                        @error('instructions')
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
                                        <label for="time_limit" class="px-0 col-md-12 col-form-label">                                        
                                        Time Limit <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-2 pr-0 mr-0">
                                        <input id="time_limit" required type="text" class="form-control form-control-sm @error('time_limit') is-invalid @enderror" name="time_limit"                                         
                                            value="{{ old('time_limit', isset($item->time_limit ) ? $item->time_limit : '') }}"
                                        >
                                        @error('time_limit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-3 small text-danger font-weight-bold pt-2">
                                        Note: In Minutes
                                    </div>

                                </div>
                            </div>
                        </div>                        
                        

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="time_limit" class="px-0 col-md-12 col-form-label">                                        
                                        Show Multiple Questions? <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-2 pt-2 mr-0">                                       
                                        <input type="checkbox" name="show_multiple" @if($item->show_multiple == true) {{ 'checked' }} @endif  value="true">
                                    </div>
                                </div>
                            </div>
                        </div>                            

                        <div class="row py-4">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Update </button>
                                <a href="{{ route('admin.minitest.categories.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>

                    </form>                    
                    
                </div>
            </div>
            <!--[end] card-->

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