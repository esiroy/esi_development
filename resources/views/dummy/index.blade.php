
@extends('layouts.app')

@section('content')

<!--
<context-menu-component/>
-->


<sample-vue-tree/>

<h1>Sortable</h1>




@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://rawgithub.com/briangonzalez/jquery.pep.js/master/src/jquery.pep.js"></script>

<script>
    $('.vtl-node').pep();
    window.addEventListener('load', function () 
    {
        console.log('script yielded');
        
    });
</script>
@endsection