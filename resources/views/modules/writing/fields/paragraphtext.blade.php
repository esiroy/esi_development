<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-12 {{ $id }}_field_content">
        <label for="{{ $id .'_textfield' }}" class="form-label">
            {{ $label }}  : 
            @if ($display_meta['required']) 
                <span class='text-danger'>*</span>                            
            @endif
        </label>

        <textarea      
                id="{{ $id }}" 
                name="{{ $id .'_paragraphTextfield' }}" 
                placeholder="{{ $label ?? '' }}" 
                class="form-control paragraphText"
                @if ($display_meta['required']) {{ "required" }} @endif
            ></textarea>

          <div class="small">{{ $display_meta['description'] ?? "" }}</div>

          <div class="{{ $id }}_total_word_count small">
            Total Word Count : <span id="{{ $id }}_total_word_count"></span>
         </div>

        <input type="hidden" id="{{ $id .'_enableWordLimit' }}" name="{{ $id .'_enableWordLimit' }}" value="{{ $display_meta['enableWordLimit'] ?? "" }}">
        <input type="hidden" id="{{ $id .'_wordLimit' }}" name="{{ $id .'_wordLimit' }}" value="{{ $display_meta['wordLimit'] ?? "" }}">


            
    </div>
</div>

