<!-- Simple Text [Backend] -->

<div id="{{ $id }}_field" class="card esi-card-writing mb-2 field_container">

    <div class="card-header esi-card-header-writing">

        <input type="hidden" id="id" name="id[]" value="{{ $id }}">
            HTML Block : Field ID ({{ $id }})
        
        <!--RIGHT BUTTON CONTAINER-->
        <div class="float-right">
            <a id="{{ $id }}_showFieldOptions" href="#" class="text-secondary btnShowFieldOptions"><i class="fa fa-caret-down fa-lg"></i></a>            
            <a id="{{ $id }}_hideFieldOptions" href="#" class="text-secondary btnHideFieldOptions d-none"><i class="fa fa-caret-up fa-lg"></i></a>
            <span id="{{ $id }}_removeField" class="btnRemoveField pl-2">         
                <a href="#" class="text-secondary"><small><i class="fas fa-times"></i></small></a>
            </span>
        </div>

    </div>

    <div class="card-body">    

        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="html">           
        <input type="hidden" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? '' }}">

        <!--LABEL-->
        <h5 class="card-title font-weight-bold">            
            @if(isset($display_meta['required']))
                @if ($display_meta['required'] == "true")
                    <span class="text-danger">*</span>
                @endif
            @endif
            <!--{{ $label }}-->
            HTML Block
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

        <div id="{{ $id }}_tab_container" class="tab-container esi-tab-container" style='display:none'>
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
                                <textarea id="content" name="{{ $id }}_content" class="form-control">{{ $display_meta['content'] ?? '' }}</textarea>
                            </div>
                        </div>
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


                    <!--[start] cLogic -->
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Conditional Logic</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Activate Conditional Logic?</label>  
                            <input id="{{ $id }}_activate_coditional_logic" 
                                name="{{ $id }}_activate_coditional_logic" 
                                class="activate_coditional_logic" 
                                @if (isset($display_meta['conditional_logic'] ))
                                    @if($display_meta['conditional_logic'] == "true") {{ "checked='checked" }} @endif
                                @endif
                                type="checkbox" class="pt-1" style="vertical-align:middle">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col conditional_fields">

                            @php
                                $cfLogic = new \App\Models\ConditionalFieldLogic;
                                $items = $cfLogic->where('field_id', $id)->get();
                            @endphp

                            @foreach($items as $ctr => $item) 

                                @php                                
                                    $writingFields = new \App\Models\WritingFields;
                                    $fields = $writingFields->where('id', $item->selected_option_id)->first();
                                    $fieldInfo =  json_decode($fields->display_meta, true);
                                @endphp

                                <div id="{{ $item->field_id }}_conditional_fields_{{ $ctr + 1 }}" class="row {{ $item->field_id }}_conditional_fields_{{ $ctr + 1 }} mb-2">

                                
                                    <div class="col-4">
                                        <select id="{{ $item->field_id }}_cfield_id_{{ $ctr + 1 }}" name="{{ $item->field_id }}_cfield_id[]" class="form-control form-control-sm cfield_id_select">
                                            @foreach($cfields as $key => $field) 
                                                @if ($field->type == "simpletextfield" || $field->type == "dropdownSelect")
                                                    <option value="{{ $field->id }}"  @if($field->id ==  $item->selected_option_id) {{ 'selected'}} @endif>{{ $field->name }}</option>
                                                @endif
                                            @endforeach                                            
                                        </select>
                                    </div>

                                    <div class="col-3">                                        
                                        <select id="{{ $item->field_id }}_cfield_rule_{{ $ctr + 1 }}" name="{{ $item->field_id }}_cfield_rule[]" class="form-control form-control-sm cfield_rule_select">
                                            <option value="is" @if($item->field_rule == 'is')  {{ 'selected'}} @endif>is</option>
                                            <option value="isnot" @if($item->field_rule == 'is not')  {{ 'selected'}} @endif>is not</option>
                                            <option value="contains" @if($item->field_rule == 'contains') {{ 'selected'}} @endif>contains</option>                                        
                                        </select>                                        
                                    </div>

                                    <div id="{{ $item->field_id }}_cfield_value_container_{{ $ctr + 1 }}" class="col-3">
                                        <select id="{{ $item->field_id }}_cfield_value_{{ $ctr + 1 }}" name="{{ $item->field_id }}_cfield_value[]" class="form-control form-control-sm cfield_value_select">
                                            @foreach($fieldInfo['selected_choices'] as $choice)
                                                <option value="{{ $choice }}" @if($item->field_value == $choice) {{ 'selected'}} @endif>{{ $choice }}</option>
                                            @endforeach
                                        </select>                                         
                                    </div>

                                    <div id="{{ $item->field_id }}_cfield_btn_container" class="col-md-2 pl-1">
                                        <a id="{{ $item->field_id }}_cfield_add_{{ $ctr + 1 }}" class="cfield_add"><i class="fas fa-plus-circle pt-2"></i></a> 
                                        <a id="{{ $item->field_id }}_cfield_remove_{{ $ctr + 1 }}" class="cfield_remove"><i class="fas fa-minus-circle pt-2"></i></a>
                                    </div>

                                </div>


                            @endforeach
                        </div>                        
                    </div>
                    <!--[end] cLogid -->

                    @php
                            /*
                            $ctr = 1;                          

                            <div id="{{ $id }}_conditional_fields_{{ $ctr }}" class="row {{ $id }}_conditional_fields_{{ $ctr }}">

                            
                                <div class="col-4">
                                    <select id="{{ $id }}_cfield_id_{{ $ctr }}" name="{{ $id }}_cfield_id[]" class="form-control form-control-sm cfield_id_select">
                                        @foreach($cfields as $key => $field) 
                                            @if ($field->type == "simpletextfield" || $field->type == "dropdownSelect")
                                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                                            @endif
                                        @endforeach                                            
                                    </select>
                                </div>

                                <div class="col-3">                                        
                                    <select id="{{ $id }}_cfield_rule_{{ $ctr }}" name="{{ $id }}_cfield_rule" class="form-control form-control-sm cfield_rule_select">
                                        <option value="is" selected="selected">is</option>
                                        <option value="isnot">is not</option>
                                        <option value="contains">contains</option>                                        
                                    </select>                                        
                                </div>

                                <div id="{{ $id }}_cfield_value_container_{{ $ctr }}" class="col-3">
                                    <select id="{{ $id }}_cfield_value_{{ $ctr }}" name="{{ $id }}_cfield_value[]" class="form-control form-control-sm cfield_value_select">
                                    </select>                                         
                                </div>

                                <div id="{{ $id }}_cfield_btn_container" class="col-md-2 pl-1">
                                    <a id="{{ $id }}_cfield_add_{{ $ctr }}" class="cfield_add"><i class="fas fa-plus-circle pt-2"></i></a> 
                                    <a id="{{ $id }}_cfield_add_{{ $ctr }}" class="selected_field_choice_remove"><i class="fas fa-minus-circle pt-2"></i></a>
                                </div>

                            </div>

                            */
                        @endphp

                </div>

            </div>
        </div>        
    </div>

</div>