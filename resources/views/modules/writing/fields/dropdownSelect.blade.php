<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">

    
        <label for="course" class="form-label">
            {{ $label }}  : 
            @if ($display_meta['required']) 
                <span class='text-danger'>*</span>                            
            @endif
        </label>

        <select id="{{ $id }}" 
                name="{{ $id ."_" }}{{  str_replace(' ', '_', strtolower($label) ) }}"  
                class="form-control" @if ($display_meta['required']) {{ "required" }} @endif>
            <option value="">Please Select</option>
            @foreach($display_meta['selected_choices'] as $choice)
                <option value="{{ $choice }}">{{ $choice }}</option>
            @endforeach                        
        </select>

        <div class="small">{{ $display_meta['description'] ?? "" }}</div>


    </div>
</div>






