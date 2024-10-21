@extends('layouts.esi-app')


@section('content')


<div class="container">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/minitest') }}">Mini Test</a>
                </li>
                {!! $breadcrumbs !!}

                <li class="breadcrumb-item active"> 
                    {{ $category->name }}
                </li>
            </ol>
        </nav>

        <div class="container">

            <div class="row">

                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->

                <div class="col-md-9">
                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            Mini Test
                            <span class="text-secondary small float-right">
                                @if($is_netenglish == true)                                        
                                    <a href="{{ url('/pages/mini-test') }}" class="esiModal">Mini Test  について</a>
                                @else                                 
                                    <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0607200521.html','Mini Test',900,820)">Mini Test  について</a> 
                                @endif                                                                
                            </span>                            
                        </div>

                        <div class="card-body">
                            @if ($category->multiple_correct_answers == true)
                                <questions-multi-answers-component 
                                    :is_member_credit_expired="{{  json_encode($isMemberCreditExpired) }}"
                                    :multiple="{{ ($category->show_multiple == true) ? 'true': 'false' }}"
                                    :multiple_correct_answer="{{ ($category->multiple_correct_answers == true) ? 'true': 'false' }}"
                                    :memberinfo="{{  json_encode(Auth::user()->memberInfo) }}"                                     
                                    :category="{{ $category }}"
                                    api_token="{{ Auth::user()->api_token }}" 
                                    csrf_token="{{ csrf_token() }}"
                                >
                                </questions-multi-answers-component >                                 
                            @else
                                <questions-component 
                                    :is_member_credit_expired="{{  json_encode($isMemberCreditExpired) }}"
                                    :multiple="{{ ($category->show_multiple == true) ? 'true': 'false' }}"
                                    :multiple_correct_answer="{{ ($category->multiple_correct_answers == true) ? 'true': 'false' }}"
                                    :memberinfo="{{  json_encode(Auth::user()->memberInfo) }}" 
                                    :category="{{ $category }}"
                                    api_token="{{ Auth::user()->api_token }}" 
                                    csrf_token="{{ csrf_token() }}"
                                >
                                </questions-component>  
                            @endif
                        </div>

                    </div>
            
                </div>

            </div>
        </div>


    </div>


</div>

@endsection

@section('scripts')
<script>    

    window.addEventListener('load', function () 
    {
        
    });
</script>
@endsection