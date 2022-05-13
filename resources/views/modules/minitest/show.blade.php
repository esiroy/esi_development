@extends('layouts.esi-app')


@section('content')

    <questions-component 
        :multiple="{{ ($category->show_multiple == true) ? 'true': 'false' }}"
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