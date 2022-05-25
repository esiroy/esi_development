@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left-0 border-primary active" href="{{ url('admin/minitest/category/type') }}">Question Category Types</a>               
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary text-white" href="{{ url('admin/minitest/categories') }}">Question Categories</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left-0 border-primary text-white" href="{{ url('admin/minitest/category/settings') }}">Settings</a>               

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


            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Category Types
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable dataTable no-footer">
                                <thead>
                                    <tr>  
                                        <th class="small text-center">ID</th>
                                        <th class="w-25 small text-center">
                                            Name                                        
                                        </th> 
                                        <th class="w-50 small text-center">
                                            Parent Type Path
                                        </th>                                         
                                        <th class="small text-center">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($items))
                                    @foreach ($items as  $item)
                                    <tr data-entry-id="{{  $item->id }}">                                        
                                        <td class="small text-center">{{ $item->id}}</td>
                                        <td class="small text-center">
                                            {{ $item->name ?? "" }}
                                        </td>

                                        <td class="small text-center">
                                            @if (isset($item->getParent))

                                                <!--
                                                <span class="text-success">{{ $item->getParent->name }}</span>
                                                -->

                                                <div>{!! $item->getParentPath($item->id) !!}</div>

                                            @else
                                                {{ " - " }}
                                            @endif
                                        </td>                                        
                                      
                                        <td class="small text-center">

                                            <a href="{{ route('admin.minitest.category.type.edit',  $item->id) }}" class="btn btn-sm btn-info mr-1">Edit</a> 
                                            @if (Auth::user()->user_type == "ADMINISTRATOR")
                                            <form action="{{ route('admin.minitest.category.type.destroy',  $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                            </form>                                            
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                </table>

                                <div class="float-right">
                                    <ul class="pagination pagination-sm">{{ $items->appends(request()->query())->links() }}</ul>
                                </div>

                            </div>
                                                        
                        </div>
                    </div>
                </div>
            </div>
            <!--[end] card-->

            <div class="card esi-card mt-4">
            
                <form method="POST" action="{{ route('admin.minitest.category.type.store') }}">
                    @csrf

                    <div class="card-header esi-card-header">Add New Category Type</div>

                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="name" type="name" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
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
                                            <option value="">Parent</option>
                                            @foreach($items as $item)
                                                @php 
                                                    $typeOption = new \App\Models\MiniTestCategoryType();
                                                @endphp  

                                                <option value="{{$item->id}}">                                                          
                                                    {!! $typeOption->getParentNames($item->id) !!}
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
                                <button type="reset" class="btn btn-danger btn-sm">Cancel</button>
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