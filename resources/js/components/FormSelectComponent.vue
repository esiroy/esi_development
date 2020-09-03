<template>

    <div class="card">
        <div class="card-header">
            Select
            <div class="float-right">
                <button class="btn btn-sm btn-link">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-caret-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                    </svg>                                            
                </button>
                <button class="btn btn-sm btn-link">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="form-group mb-4">
                <label>{{ form.values.label }}</label>

                <select name="input_4" id="input_4" class="form-control" tabindex="1" disabled="disabled" aria-invalid="false" >
                    <option value="First Choice">First Choice</option>
                    <option value="Second Choice">Second Choice</option>
                    <option value="Third Choice">Third Choice</option>
                </select>
                
                <div id="field_description" class="mt-2 mb-2">
                    {{ form.values.description }}
                </div>
            </div>                                    

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#general">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#appearance">Appearance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#advance">Advance</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content border border-top-0 pb-4">

                <!--[start] general tab pane -->
                <div id="general" class="container tab-pane active"><br>
                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Field Label</label>
                        <input type="text" class="form-control" name="field_name" v-model="form.values.label"/>
                    </div>
                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Description</label>
                        <textarea class="form-control" name="field_description" v-model="form.values.description"/>
                    </div>
                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Maximum Characters</label>
                        <input type="text" name="maximum_characters" class="form-control col-1" v-model="form.values.maximum_characters"/>
                    </div>
                    <div class="form-group">                                               
                        <label class="text-primary font-weight-bold">Rules</label>
                        <p>
                            <input type="checkbox" name="field_required" value="required" v-model="form.values.rules.required"/> <label> Required </label> <br/>
                            <input type="checkbox" name="no_duplicates" value="no_duplicates" v-model="form.values.rules.no_duplicates"> <label> No duplicates </label>
                        </p>
                    </div>                                            
                </div>


                <div id="appearance" class="container tab-pane fade"><br>

                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Placeholder</label>
                        <input type="text" class="form-control" name="field_placeholder" v-model="form.values.placeholder"/>
                    </div>

                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Custom Validation Message</label>
                        <input type="text" class="form-control" name="field_custom_validation_message" v-model="form.values.custom_validation_message"/>
                    </div>

                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Description Placement </label>
                        <select id="field_description_placement" name="field_description_placement" class="form-control">                                                
                            <option value="below">Below inputs</option>
                            <option value="above">Above inputs</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Custom CSS Class</label>
                        <input type="text" class="form-control" name="field_custom_css_class" v-model="form.values.custom_css_class"/>
                    </div>

                    <div class="form-group">
                        <label class="text-primary font-weight-bold">Custom CSS Class</label>
                        <select id="field_size" name="field_size" class="form-control">
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                </div>
                <div id="advance" class="container tab-pane fade"><br>
                    
                    <div class="form-group">                                                
                        <label class="text-primary font-weight-bold">Enable Conditional Logic</label>
                        
                        <div id="field_conditional_logic_container">
                            <select id="field_action_type" class="form-control">
                                <option value="show" selected="selected">Show</option>
                                <option value="hide">Hide</option>
                            </select> 
                            this field if 
                            <select id="field_logic_type" class="form-control">
                                <option value="all" selected="selected">All</option>
                                <option value="any">Any</option>
                            </select> 
                            
                            of the following match:

                            <div class="conditional_logic_rules_container">
                                <!--@todo (loop here) -->
                                <select id="field_rule_field_0" class="form-control col-3 d-inline">
                                    <option value="2" selected="selected">Untitled</option>
                                </select>
                                <select id="field_rule_operator_0" class="form-control col-2 d-inline">
                                    <option value="is" selected="selected">is</option>
                                    <option value="isnot">is not</option>
                                    <option value=">">greater than</option>
                                    <option value="<">less than</option>
                                    <option value="contains">contains</option>
                                    <option value="starts_with">starts with</option>
                                    <option value="ends_with">ends with</option>
                                </select>
                                
                                <select id="field_rule_value_0" name="field_rule_value_0" class="form-control col-3 d-inline">
                                    <option value="First Choice" selected="selected">First Choice</option>
                                    <option value="Second Choice">Second Choice</option>
                                    <option value="Third Choice">Third Choice</option>
                                </select>
                            
                                <a title="add another rule" class="col-3 d-inline">
                                    +
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>
                            
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                //dynamic form
                form: {
                    values: {
                        label: "Untitled",
                        input: "",
                        description: "",
                        placeholder: "",
                        description_placement: ['top', 'bottom'],
                        maximum_characters: "",
                        rules: {
                            required        : false,
                            no_duplicates   : false
                        }
                    }                    
                } 
            };
        },        
    }
</script>
