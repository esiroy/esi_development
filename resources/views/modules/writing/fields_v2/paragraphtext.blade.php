<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" 
    style='@if($display_meta['conditional_logic'] == 'true') {{ "" }} @endif'>
    <div class="mb-3 text-left col-md-12 {{ $id }}_field_content">
        <label for="{{ $id .'_textfield' }}" class="form-label">
            {{ $label }}  : 
            @if ($display_meta['required']) 
                <span class='text-danger'>*</span>                            
            @endif
        </label>
        <textarea id="{{ $id }}" name="{{  'paragraphTextfield_' . $id }}" placeholder="{{ $label ?? '' }}" class="form-control paragraphText ckEditor" @if ($display_meta['required']) {{ "required" }} @endif></textarea>
        <div class="small">{!! $display_meta['description'] ?? "" !!}</div>

        @if ($display_meta['enableWordLimit'] == 'true')
         <div class="{{ $id }}_total_word_count small float-left">
            Total Words : <span id="{{ $id }}_total_word_count">0</span>
         </div>
         <div class="small float-right">
            Max : {{ $display_meta['wordLimit'] ?? "" }}
         </div>
         @endif
        <input type="hidden" id="{{ $id .'_memberPointChecker' }}" name="{{ $id .'_memberPointChecker' }}" value="{{ $display_meta['memberPointChecker'] ?? "" }}">
        <input type="hidden" id="{{ $id .'_enableWordLimit' }}" name="{{ $id .'_enableWordLimit' }}" value="{{ $display_meta['enableWordLimit'] ?? "" }}">
        <input type="hidden" id="{{ $id .'_wordLimit' }}" name="{{ $id .'_wordLimit' }}" value="{{ $display_meta['wordLimit'] ?? "" }}">            
        <div class="clearfix"></div>
    </div>
</div>