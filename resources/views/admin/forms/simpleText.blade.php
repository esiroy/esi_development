<!-- Simple Text [Backend] -->

<div id="field_{{ $id }}" class="card esi-card-writing mb-2 field_container">
    <div class="card-header esi-card-header-writing">
        Simple Input Text : Field ID ({{ $id }})         
        <input type="hidden" id="id" name="id[]" value="{{ $id }}"> 

        <div class="float-right">
            <a id="{{ $id }}_showFieldOptions" href="#" class="text-secondary btnShowFieldOptions"><i class="fa fa-caret-down fa-lg"></i></a>            
            <a id="{{ $id }}_hideFieldOptions" href="#" class="text-secondary btnHideFieldOptions d-none"><i class="fa fa-caret-up fa-lg"></i></a>
            <span id="{{ $id }}_removeField" class="btnRemoveField pl-2">         
                <a href="#" class="text-secondary"><small><i class="fas fa-times"></i></small></a>
            </span>
        </div>

    </div>

    <div class="card-body">    

        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="SimpleTextField">           
        <h5 class="card-title font-weight-bold">{{ $label }}</h5>    
        <div class="card-text">
            <input type="text" id="label" class="form-control form-control-sm bg-white" disabled="disabled" >
            <div class="small">{{ $description }}</div>
        </div>

        <div class="tab-container esi-tab-container d-none">
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
                                <textarea id="description" name="{{ $id }}_description" class="form-control">{{ $description ?? "" }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label class="form-label">Maximum Characters</label>
                                <input type="text" id="maximum_characters" name="{{ $id }}_maximum_characters" value="{{ $maximum_characters ?? '' }}" class="form-control col-md-2">
                            </div>
                        </div>

                        @if(isset($display_meta['required']))
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Rules</label>
                                <br/>
                                <input id="required" name="{{ $id }}_required" type="checkbox" @if($display_meta['required'] == "true") {{ "checked='checked" }} @endif> <label class="form-label">Required</label>                                
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <div id="tabs-advanced">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Default Value</label>
                            <br/>
                            <input type="text" id="default_value" name="{{ $id }}_default_value" value="{{ $display_meta['default_value'] ?? '' }}" class="form-control col-md-6">
                        </div>        
                    </div>       
                </div>
            </div>
        </div>        
    </div>

</div>
