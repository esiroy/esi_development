@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left-0 border-primary active" href="{{ url('admin/minitest/category/type') }}">Question Category Types</a>               
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary text-white" href="{{ url('admin/minitest/categories') }}">Question Categories</a>
            </nav>
        </div>
    </div>


    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Minitest</li>                
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


 

            <div class="card esi-card mt-4">
            

                <form method="POST" action="{{ route('admin.minitest.category.type.update', ['type'=> $item ]) }}">
                    @csrf
                    @method('PATCH')

                    <div class="card-header esi-card-header">Updated New Category Type</div>

                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="name" type="name" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" 
                                        value="{{ old('name', isset($item->name) ? $item->name: '') }}" 
                                        required autocomplete="name">
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
                                        <label for="parent_id" class="px-0 col-md-12 col-form-label">                                        
                                        Parent Category<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                       
                                        <select name="parent_id" required class="form-control form-control-sm">
                                            <option value="">-- Select --</option>

                                            <option value="" @if ($item->parent_id == null) {{ ' selected ' }} @endif )>Parent</option>

                                            @foreach($types as $type)
                                                @php 
                                                    $typeOption = new \App\Models\MiniTestCategoryType();
                                                @endphp  

                                                <option value="{{$type->id}}"  
                                                    @if($type->id == $item->id) {{ " disabled "}} @endif
                                                    @if($type->id == $item->parent_id) {{ " selected = selected "}} @endif
                                                >
                                                    {!! $typeOption->getParentNames($type->id) !!}
                                                </option>


                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row py-4">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>

                                <a href="{{ route('admin.minitest.category.type.index') }}" class="btn btn-danger btn-sm">Cancel</a>

                                
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