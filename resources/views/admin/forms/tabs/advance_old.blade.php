                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label">Default Value</label>
                            <br />
                            <input type="text" id="default_value" name="{{ $id }}_default_value"value="{{ $display_meta['default_value'] ?? '' }}" class="form-control form-control-sm col-md-6">
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

                                    if (isset($fields->display_meta)) {
                                        $fieldInfo =  json_decode($fields->display_meta, true);
                                    } else {
                                        $fieldInfo = null;
                                    }
                                    
                                @endphp

                                <div id="{{ $item->field_id }}_conditional_fields_{{ $ctr + 1 }}" class="row {{ $item->field_id }}_conditional_fields_{{ $ctr + 1 }} mb-2">

                                
                                    <div class="col-4">
                                        <select id="{{ $item->field_id }}_cfield_id_{{ $ctr + 1 }}" name="{{ $item->field_id }}_cfield_id[]" class="form-control form-control-sm cfield_id_select">
                                            @foreach($cfields as $key => $field) 
                                                @if ($field->type == "simpletextfield" || 
                                                     $field->type == "dropdownSelect"  ||
                                                     $field->type == "emailfield"  ||
                                                     $field->type == "firstnamefield"  ||
                                                     $field->type == "lastnamefield" 
                                                     )
                                                    <option value="{{ $field->id }}"  @if($field->id ==  $item->selected_option_id) {{ 'selected'}} @endif>{{ $field->name }}</option>
                                                @endif
                                            @endforeach                                            
                                        </select>
                                    </div>

                                    <div class="col-3">                                        
                                        <select id="{{ $item->field_id }}_cfield_rule_{{ $ctr + 1 }}" name="{{ $item->field_id }}_cfield_rule[]" class="form-control form-control-sm cfield_rule_select">
                                            <option value="is" @if($item->field_rule == 'is')  {{ 'selected'}} @endif>is</option>
                                            <option value="isnot" @if($item->field_rule == 'isnot')  {{ 'selected'}} @endif>is not</option>
                                            <option value="contains" @if($item->field_rule == 'contains') {{ 'selected'}} @endif>contains</option>                                        
                                        </select>                                        
                                    </div>

                                    <div id="{{ $item->field_id }}_cfield_value_container_{{ $ctr + 1 }}" class="col-4">
                                        
                                        @if(isset($fields->field_rule) && $item->field_rule == 'contains')
                                            <input id="{{ $item->field_id }}_cfield_value_{{ $ctr + 1 }}" type="text" name="{{ $item->field_id }}_cfield_value[]" value="{{ $item->field_value }}" class="form-control form-control-sm cfield_value_select">
                                        @elseif (isset($fields->type) && $fields->type == "dropdownSelect")
                                            <select id="{{ $item->field_id }}_cfield_value_{{ $ctr + 1 }}" name="{{ $item->field_id }}_cfield_value[]" class="form-control form-control-sm cfield_value_select">
                                                @if (isset($fieldInfo['selected_choices']))
                                                    @foreach($fieldInfo['selected_choices'] as $choice)
                                                        <option value="{{ $choice }}" @if($item->field_value == $choice) {{ 'selected'}} @endif>{{ $choice }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        @else                                            
                                            <input id="{{ $item->field_id }}_cfield_value_{{ $ctr + 1 }}" type="text" name="{{ $item->field_id }}_cfield_value[]" value="{{ $item->field_value }}" class="form-control form-control-sm cfield_value_select">
                                        @endif
                                    </div>

                                    <div id="{{ $item->field_id }}_cfield_btn_container" class="col-md-1 pl-1">
                                        <a id="{{ $item->field_id }}_cfield_add_{{ $ctr + 1 }}" class="cfield_add"><i class="fas fa-plus-circle pt-2"></i></a> 
                                        <a id="{{ $item->field_id }}_cfield_remove_{{ $ctr + 1 }}" class="cfield_remove"><i class="fas fa-minus-circle pt-2"></i></a>
                                    </div>

                                </div>
                            @endforeach
                        </div>                        
                    </div>
                    <!--[end] cLogid -->
