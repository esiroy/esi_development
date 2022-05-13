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



                 <li class="breadcrumb-item">
                    <a href="{{ route('admin.minitest.questions.index', ['category_id' => $category_id ]) }}">Questions</a>
                </li>


                 <li class="breadcrumb-item active" aria-current="page">{{  $item->question }}</li>
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
                    Minitest Question Edit
                </div>
                <div class="card-body">
                
                    <form method="POST" action="{{ route('admin.minitest.questions.update', ['category_id'=> $item->category_id, 'question' => $item ]) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="question" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Name<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="question" type="question" class="form-control form-control-sm @error('name') is-invalid @enderror" name="question" 
                                        value="{{ old('question', isset($item->question) ? $item->question: '') }}" 
                                        required autocomplete="question"
                                        >
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
                                <button type="submit" class="btn btn-primary btn-sm">Update </button>
                                <a href="{{ route('admin.minitest.questions.index', ['category_id' => $category_id ]) }}" class="btn btn-danger btn-sm">Cancel</a>
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