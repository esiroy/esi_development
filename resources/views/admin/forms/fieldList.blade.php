<div id="{{ $id }}_field" class="card esi-card-writing mb-2 field_container">
    <div class="card-header esi-card-header-writing">
        <span class="ui-icon ui-icon-arrowthick-2-n-s text-secondary handle"></span>
        <span>{{ $texttype }} : Field ID ({{ $id }})</span>
        <div class="float-right">            
            <a href='#' onclick="return editFormField('{{$id}}')">edit</a></span> | 
            <span><a href='#' id="{{ $id }}_removeField" class="btnRemoveField">delete</a></span>
        </div>      
    </div>
    <div class="card-body">

        <input type="hidden" id="id" name="id[]" value="{{ $id }}">
        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="SimpleTextField">           
        <input type="hidden" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? '' }}">
        <input type="hidden" id="{{ $id }}_page" name="{{ $id }}_page"  class="page" value="page-{{ $page_id ?? '0' }}">


        @if ($content)                       
            <div class="esi-html-container mt-2">
                <span class="esi_blockheader"><i class="fa fa-code fa-lg"></i> HTML Content</span>
                <span>This is a content placeholder. HTML content is not displayed in the form admin. Preview this form to view the content.</span>
            </div>
        @else         
           {{ $label }} {!! $description !!}
        @endif    
    </div>
</div>