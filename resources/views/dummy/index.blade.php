@extends('layouts.admin')

@section('content')

    @php 

        $category_id = 1;

    @endphp


    <questions-component 
        :memberinfo="{{  json_encode(Auth::user()->memberInfo) }}" 
        :category_id="{{ $category_id }}"
        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"
    >
    </questions-component>

@endsection

@section('scripts')
<script>    

    window.addEventListener('load', function () 
    {
        
    });
</script>
@endsection