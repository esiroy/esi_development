<!--

@page : Question Index page

-->
@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">
    <div class="esi-box">   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/admin/minitest/categories') }}">Minitest</a></li>                
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name ?? '' }} </li>                 
                <li class="breadcrumb-item active" aria-current="page">Questions</li>
               
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
                                        <th class="w-25 small text-center">Question</th> 
                                        <th class="small text-center">Choices</th> 
                                        <th class="small text-center">Correct Choice</th> 
                                        <th class="w-25 small text-center">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($items))
                                    @foreach ($items as  $item)
                                    <tr data-entry-id="{{  $item->id }}" class="@if ($added_question_id == $item->id || $updated_question_id == $item->id) {{ 'table-success' }} @endif">
                                        
                                        <td class="small text-center">{{ $item->id }}</td>
                                        <td class="small text-center">{{ $item->question ?? "" }}</td>                                           
                                        <td class="small text-left">                                        
                                            @if (count($item->choices) >= 1)
                                                <ol>
                                                @foreach($item->choices as $choice)
                                                    <li>{{ $choice->choice }}</li>
                                                @endforeach               
                                                </ol>
                                            @else
                                                <div class="text-center text-danger p-2">Warning: No Choices Added</div>
                                            @endif
                                        </td>    

                                        <td class="small text-center">
                                            @if (isset($item->answer->choice_id))                                                 
                                                {{  $item->answerText($item->answer->choice_id) }}
                                            @else 
                                                <div class="text-center text-danger p-2">Warning: No Choice selected or Added</div>
                                           @endif
                                        </td>
                                                    
                                        <td class="small text-center">

                                            <!-- show choices -->
                                            <a href="{{ route('admin.minitest.choices.index', [ 'category_id'=> $item->category_id, 'question_id' => $item->id ]) }}" class="btn btn-sm btn-info mr-1">Choices</a> 
                                            
                                            <!-- edit question -->
                                            <a href="{{ route('admin.minitest.questions.edit', ['question'=> $item->id, 'category_id'=> $item->category_id] ) }}" class="btn btn-sm btn-info mr-1">Edit </a>                                           
                                            
                                            <!-- delete question -->
                                            @if (Auth::user()->user_type == "ADMINISTRATOR")
                                            <form action="{{ route('admin.minitest.questions.destroy',  ['question'=> $item->id, 'category_id'=> $item->category_id] ) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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

                <form method="POST" action="{{ route('admin.minitest.questions.store', ['category_id'=> $id]) }}">
                    @csrf

                    <div class="card-header esi-card-header">Add New Question</div>

                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="question" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Question <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="question" type="question" class="form-control form-control-sm @error('name') is-invalid @enderror" name="question" value="{{ old('question') }}" required autocomplete="question">
                                        @error('question')
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