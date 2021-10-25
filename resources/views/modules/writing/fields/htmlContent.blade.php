<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>

    <div class="HTMLDataContent col-md-12 mb-2">
        <input type="hidden" id="{{ $id }}" class="form-control" name="{{ $id ."_" }}{{  str_replace(' ', '_', strtolower($label) ) }}" value="{{ $display_meta['content'] }}">
    </div>

    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">
        @if($display_meta['conditional_logic'] !== 'true')         
            {!! $display_meta['content'] !!} 
        @endif
    </div>
</div>