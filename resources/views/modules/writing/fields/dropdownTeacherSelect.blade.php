<div id="{{ $id }}_field_row" class="row @if($display_meta['conditional_logic'] == 'true') {{ "cfLogic" }} @endif" style='@if($display_meta['conditional_logic'] == 'true') {{ "display:none" }} @endif'>
    <div class="mb-3 text-left col-md-6 {{ $id }}_field_content">    
        <label for="course" class="form-label">
            {{ $label }}  : @if ($display_meta['required']) <span class='text-danger'>*</span>@endif
        </label>
        <select id="{{ $id }}" name="{{ $id ."_" }}{{  str_replace(' ', '_', strtolower($label) ) }}"  class="form-control" @if ($display_meta['required']) {{ "required" }} @endif>
            @php
                $tutor = new App\Models\Tutor();
                $tutors = $tutor->getTutors();                                            
            @endphp        
            <option value="">Please Select</option>
            @foreach($tutors as $tutor) 
                @if ($tutor->user_id == 16800)
                    <!-- Lei (not selectable)
                        <option value="{{ $tutor->user_id }}" class="memberTeacherList" >{{ $tutor->firstname }}</option> 
                     -->
                @else 
                    <option value="{{ $tutor->user_id }}" class="memberTeacherList" >{{ $tutor->firstname }}</option> 
                @endif
            @endforeach
        <select>
        <input name="appoint_teacher_field_id" type="hidden" value="{{ $id  }}" class="form-control">
        <div class="small">{!! $display_meta['description'] ?? "" !!}</div>
    </div>
</div>