@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">
    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/admin/minitest/categories') }}">Minitest</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.minitest.questions.index', ['category_id'=> $category_id]) }}">Questions</a></li>

               
                <li class="breadcrumb-item active"> <a href="{{ route('admin.minitest.questions.edit', ['category_id'=> $category_id, 'question'=> $item ]) }}">{{ ucwords($item->question) }} </a></li>
                


                <li class="breadcrumb-item active" aria-current="page">Choices</li>
               
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
                    Questions
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable dataTable no-footer">
                                <thead>
                                    <tr>  
                                        <th class="small text-center">ID</th>                                   
                                        <th class="small text-center">Choice</th> 
                                        <th class="small text-center">Correct Choice</th> 
                                        <th class="small text-center">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($items))

                                    @foreach ($items as  $item)
                                    <tr data-entry-id="{{  $item->id }}" class="@if ($added_choice_id == $item->id || $updated_choice_id == $item->id) {{ 'table-success' }} @endif">
                                        
                                        <td class="small text-center">{{ $item->id }}</td>
                                        <td class="small text-center">{{ $item->choice ?? "" }}</td>                                           
                                        <td class="small text-center">

                                            @if (isset($item->isCorrect->choice_id))

                                                @if ($item->id == $item->isCorrect->choice_id)
                                                    <div class="text-center text-success p-2">Correct </div>
                                                @else
                                                    <div class="text-center text-danger p-2">incorrect</div>
                                                @endif

                                            @else 
                                                <div class="text-center text-danger p-2">Incorrect</div>

                                            @endif

                                        </td> 
                                        <td class="small text-center">
                                            <!-- edit choice -->
                                            <a href="{{ route('admin.minitest.choices.edit', ['question_id'=> $question_id, 'category_id'=> $category_id, 'choice'=> $item] ) }}" class="btn btn-sm btn-info mr-1">Edit </a>                                           
                                            
                                            <!-- delete question -->
                                            @if (Auth::user()->user_type == "ADMINISTRATOR")
                                            <form action="{{ route('admin.minitest.choices.destroy',  ['question_id'=> $question_id, 'category_id'=> $category_id, 'choice'=> $item] ) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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
            
               <!-- add question -->

                <form method="POST" action="{{ route('admin.minitest.choices.store',  ['question_id'=> $question_id, 'category_id'=> $category_id] ) }}">
                    @csrf

                    <div class="card-header esi-card-header">Add New Choice</div>

                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">

                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="choice" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Choice <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="choice" type="choice" class="form-control form-control-sm @error('name') is-invalid @enderror" name="choice" value="{{ old('name') }}" required autocomplete="choice">
                                        @error('choice')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="choice" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Mark Correct Answer <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6 pt-2">
                                        <input id="correct" type="checkbox" class="" name="correct" value="true">                                    
                                    </div>
                                </div>


                            </div>
                        </div>
                      
                        <div class="row py-4">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <a href="{{ route('admin.minitest.questions.index',  ['category_id'=> $category_id]) }}" class="btn btn-danger btn-sm">Cancel</a>
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