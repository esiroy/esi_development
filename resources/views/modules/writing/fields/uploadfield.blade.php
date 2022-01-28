<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">
        <label for="{{ $id ."_file" }}" class="form-label">
            {{ $label }}  : 
            @if ($display_meta['required']) 
                <span class='text-danger'>*</span>                            
            @endif
        </label>       
        <input type="file" id="{{ $id  }}" name="{{ $id ."_file" }}" class="form-control uploadfield pt-3 pb-5" @if ($display_meta['required']) {{ "required" }} @endif/>
        <div class="small ">{!!$display_meta['description'] ?? "" !!}</div>
    </div>
</div>