<div id="{{ $id }}_field" class="card esi-card-writing mb-2">

    <div class="card-header esi-card-header-writing ">        
        <span>HTML Block : Field ID ({{ $id }})</span>
    </div>

    <div class="card-body">

        <input type="hidden" id="id" name="id[]" value="{{ $id }}">
        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="html">           
        <input type="hidden" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? '' }}">
        <input type="hidden" id="{{ $id }}_page" name="{{ $id }}_page"  class="page" value="page-{{ $page_id ?? '0' }}">

        <!--LABEL-->
        <h5 class="card-title font-weight-bold">            
            @if(isset($display_meta['required']))
                @if ($display_meta['required'] == "true")
                    <span class="text-danger">*</span>
                @endif
            @endif                        
            <div>{{ $label ?? 'HTML Block' }}</div>
        </h5>

        <div class="card-text">
            
            <input type="text" id="label" class="form-control form-control-sm bg-white" disabled="disabled" >
            
            <div class="esi-html-container mt-2">
                <span class="esi_blockheader">
                    <i class="fa fa-code fa-lg"></i> HTML Content
                </span>
                <span>This is a content placeholder. HTML content is not displayed in the form admin. Preview this form to view the content.</span>
            </div>

        </div>

        <div id="{{ $id }}_tab_container" class="tab-container esi-tab-container">
            <div id="tabs" class="tabs esi-tabs mt-2">
                <ul>
                    <li><a href="#tabs-general">General</a></li>                
                    <li><a href="#tabs-advanced">Advanced</a></li>
                </ul>
                <div id="tabs-general">
                    <div class="fields">
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Field Label</label>
                                <input type="text" id="label" name="{{ $id }}_label" value="{{ $label ?? "" }}" required class="form-control pt-0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label class="form-label">Content</label>
                                <textarea id="{{ $id }}_content" name="{{ $id }}_content" class="ckEditor form-control {{ $id }}_content">{{ $display_meta['content'] ?? '' }}</textarea>

                                <div id="insertImage" class="mt-2">
                                    <input type="hidden" class="addedContentFieldID" value="{{ $id }}">
                                    <input type="button" value="Insert Media File" class="btn btn-sm btn-primary insertToMediaAddedField">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="tabs-advanced">
                    @include('admin.forms.tabs.advance')
                </div>


            </div>
        </div>        
    </div>

</div>