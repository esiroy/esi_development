<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">
        <label for="firstname" class="form-label">
            {{ $label }}  : 
            @if ($display_meta['required']) 
                <span class='text-danger'>*</span>                            
            @endif
        </label>
        <input type="text" id="{{ $id }}" name="{{ $id ."_firstname" }}" placeholder="{{ $label ?? '' }}" class="form-control firstnamefield bg-white" value="{{ Auth::user()->firstname ?? '' }}" readonly="readonly" @if ($display_meta['required']) {{ "required" }} @endif>
        <div class="small">{!! $display_meta['description'] ?? "" !!}</div>
    </div>
</div>