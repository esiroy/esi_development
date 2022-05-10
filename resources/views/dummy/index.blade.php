@extends('layouts.esi-app')


@section('content')

    @php
        $multiple = 'false';
       
    @endphp


    <questions-component 
        :multiple="{{ $multiple }}"
        :memberinfo="{{  json_encode(Auth::user()->memberInfo) }}" 
        :category="{{ $category }}"
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