@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">
    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/admin/minitest/categories') }}">Minitest</a></li>

                 <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('admin.minitest.questions.index', ['category_id' => $category_id ]) }}"> {{ $category->name ?? '' }} </a>
                </li>



                <li class="breadcrumb-item"><a href="{{ route('admin.minitest.questions.index', ['category_id'=> $category_id]) }}">Questions</a></li>
                <li class="breadcrumb-item active">
                     <a href="{{ route('admin.minitest.choices.index',  ['question_id'=> $question_id, 'category_id'=> $category_id]) }}" >{!! ucwords($question->question) ?? '' !!}</a>     
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('admin.minitest.choices.index',  ['question_id'=> $question_id, 'category_id'=> $category_id]) }}" >Choices</a>               
                </li>

                <li class="breadcrumb-item active" aria-current="page"> {{  $item->choice ?? '' }} </li>


 
               
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
            
               <!-- add question -->

                <form method="POST" action="{{ route('admin.minitest.choices.update',  ['question_id'=> $question_id, 'category_id'=> $category_id, 'choice' => $item] ) }}">
                    @csrf
                    @method('PATCH')

                    <div class="card-header esi-card-header">Add New Choice</div>

                    <div class="card-body">

                        <div class="row pt-2">
                            <div class="col-6">

                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="choice" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Choice <div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="choice" type="choice" required class="form-control form-control-sm @error('choice') is-invalid @enderror" name="choice" 
                                         value="{{ old('choice', isset($item->choice) ? $item->choice: '') }}"
                                        >

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
                                        @if (isset($item->isCorrect->choice_id) && $item->id == $item->isCorrect->choice_id )
                                            <input id="correct" type="checkbox" checked name="correct" value="true">                                    
                                        @else 
                                            <input id="correct" type="checkbox" name="correct" value="true">                                    
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                      
                        <div class="row py-4">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <a href="{{ route('admin.minitest.choices.index',  ['question_id'=> $question_id, 'category_id'=> $category_id]) }}" class="btn btn-danger btn-sm">Cancel</a>
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