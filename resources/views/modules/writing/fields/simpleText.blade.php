<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">

        <label for="firstname" class="form-label">
           {{ $label ?? '' }}*
        </label> 
        <input type="text" id="firstname" placeholder="{{ $label ?? '' }}" class="form-control">
    </div>
</div>
