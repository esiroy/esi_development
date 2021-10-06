<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">
        @php 
        
        /*{!! $display_meta['content'] !!} */

        @endphp


        Retrieving Content, Please wait...
    </div>
</div>