<div id="{{ $id }}_field" class="card esi-card-writing mb-2 field_container">
    <div class="card-header esi-card-header-writing ">
        <span class="ui-icon ui-icon-arrowthick-2-n-s text-secondary handle"></span>
        <span>Dropdown Teacher Select : Field ID ({{ $id }})</span>
        <div class="float-right">            
            <a id="{{ $id }}_copyField" href="#" class="text-secondary btnCopyField pr-1"><i class="fa fa-copy fa-sm"></i></a> | 
            <a href='#' onclick="return editFormField('{{$id}}')">edit</a></span> | 
            <span><a href='#' id="{{ $id }}_removeField" class="btnRemoveField">delete</a></span>
        </div>
    </div>
    <div class="card-body">
        <input type="hidden" id="id" name="id[]" value="{{ $id }}">
        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="dropdownTeacherSelect">
        <input type="hidden" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? '' }}">
        <input type="hidden" id="{{ $id }}_page" name="{{ $id }}_page"  class="page" value="page-{{ $page_id ?? '0' }}">
        <!--LABEL-->        
        <h5 class="card-title font-weight-bold">            
            @if(isset($display_meta['required'])) @if ($display_meta['required'] == "true") <span class="text-danger">*</span> @endif @endif {{ $label }}
        </h5>
        <div class="card-text">
            <select id="teacher" class="form-control " disabled="disabled"><option value="">Select Teacher</option></select>            
            <div class="small">{!! $display_meta['description'] ?? "" !!}</div>
        </div>      
    </div>
</div>