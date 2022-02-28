<div id="{{ $id }}_field" class="card esi-card-writing mb-2">
    <div class="card-header esi-card-header-writing">         
        <span>E-Mail Field : Field ID ({{ $id }})</span>
    </div>

    <div class="card-body">
    
        <input type="hidden" id="id" name="id[]" value="{{ $id }}">     
        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="emailfield">           
        <input type="hidden" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? '' }}">
        <input type="hidden" id="{{ $id }}_page" name="{{ $id }}_page"  class="page" value="page-{{ $page_id ?? '0' }}">

        <!--LABEL-->
        <h5 class="card-title font-weight-bold">            
            @if(isset($display_meta['required'])) @if ($display_meta['required'] == "true") <span class="text-danger">*</span>@endif @endif
            {{ $label }}
        </h5>

        <div class="card-text">
            <input type="text" id="label" class="form-control form-control-sm bg-white" value="E-Mail Adress" disabled="disabled" >
            <div class="small"> {!! $display_meta['description'] ?? "" !!} </div>
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
                                <label class="form-label">Description</label>
                                <textarea id="description" name="{{ $id }}_description" class="form-control ckEditor">{{ $description ?? "" }}</textarea>
                            </div>
                        </div>

                        
                       
                        <div class="row">
                            <div class="col">                                                                   
                                <label class="form-label">Maximum Characters</label>
                                <input type="text" id="maximum_characters" name="{{ $id }}_maximum_characters" value="{{ $display_meta['maximum_characters'] ?? '' }}" class="form-control col-md-2">
                            </div>
                        </div>
                       
                       
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Rules</label>
                                <br/>
                                <input id="required" name="{{ $id }}_required" type="checkbox" @if($display_meta['required'] == "true") {{ "checked='checked" }} @endif> <label class="form-label">Required</label>                                
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
