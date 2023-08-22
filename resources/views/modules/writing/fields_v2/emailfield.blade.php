<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" 
    style='@if($display_meta['conditional_logic'] == 'true') {{ "" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">
        <label for="email" class="form-label">
            {{ $label }}  : @if ($display_meta['required']) <span class='text-danger'>*</span> @endif
        </label>
        <input type="text"  id="{{ $id }}" name="{{ "email_" . $id }}" placeholder="{{ $label ?? '' }}" class="form-control emailfield bg-white" value="{{ Auth::user()->email ?? '' }}" readonly="readonly" @if ($display_meta['required']) {{ "required" }} @endif>
        <div class="small">{!! $display_meta['description'] ?? "" !!}</div>
    </div>
</div>