<!-- Simple Text [Backend] -->

<div id="{{ $id }}_field" class="card esi-card-writing mb-2 field_container">

    <div class="card-header esi-card-header-writing ">

        <input type="hidden" id="id" name="id[]" value="{{ $id }}">
        
        <span class="handle">
            Paragraph Text : Field ID ({{ $id }})
        </span>
        
        <!--RIGHT BUTTON CONTAINER-->
        <div class="float-right">
            <span class="ui-icon ui-icon-arrowthick-2-n-s text-secondary handle"></span>        
            <a id="{{ $id }}_copyField" href="#" class="text-secondary btnCopyField pr-1"><i class="fa fa-copy fa-sm"></i></a>
            <a id="{{ $id }}_showFieldOptions" href="#" class="text-secondary btnShowFieldOptions"><i class="fa fa-caret-down fa-lg"></i></a>            
            <a id="{{ $id }}_hideFieldOptions" href="#" class="text-secondary btnHideFieldOptions"><i class="fa fa-caret-up fa-lg"></i></a>
            <span id="{{ $id }}_removeField" class="btnRemoveField pl-2">         
                <a href="#" class="text-secondary"><small><i class="fas fa-times"></i></small></a>
            </span>
        </div>

    </div>

    <div class="card-body">    

        <input type="hidden" id="{{ $id }}_fieldType" name="{{ $id }}_fieldType" value="paragraphText">           
        <input type="hidden" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? '' }}">
        <input type="hidden" id="{{ $id }}_page" name="{{ $id }}_page"  class="page" value="page-{{ $page_id ?? '0' }}">


        <!--LABEL-->
        <h5 class="card-title font-weight-bold">            
            @if(isset($display_meta['required']))
                @if ($display_meta['required'] == "true")
                    <span class="text-danger">*</span>
                @endif
            @endif
            {{ $label }}
        </h5>

        <div class="card-text">
            <textarea id="label" class="form-control form-control-sm bg-white" disabled="disabled" rows="4" cols="50" ></textarea>
            <div class="small">
                {!! $display_meta['description'] ?? "" !!}
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
                                <input type="text" id="{{ $id }}_label" name="{{ $id }}_label" value="{{ $label ?? "" }}" required class="form-control pt-0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label class="form-label">Description</label>
                                <textarea id="{{ $id }}_description" name="{{ $id }}_description" class="form-control ckEditor">{{ $description ?? "" }}</textarea>
                            </div>
                        </div>
      
                       
                       <hr style="border-top:1px dashed #999">

                       <div class="row">
                            <div class="col">                                
                                <input type="checkbox" id="memberPointChecker" name="{{ $id }}_memberPointChecker" 
                                    @if(isset($display_meta['memberPointChecker']))
                                        @if ($display_meta['memberPointChecker'] == "on" || $display_meta['memberPointChecker'] == "true") {{ "checked='checked" }} @endif 
                                    @endif>
                                <label class="form-label">Enable Point Checker</label>
                            </div>
                            <div class="col-8 small">
                                Note: Notify if they need point or monthly credits
                            </div>

                        </div>
                        <hr style="border-top:1px dashed #999">


                        <div class="row">
                            <div class="col-4">                                
                                <input type="checkbox" id="enableWordLimit" name="{{ $id }}_enableWordLimit" @if($display_meta['enableWordLimit'] == "on" || $display_meta['enableWordLimit'] == "true") {{ "checked='checked" }} @endif>
                                <label class="form-label">Enable Word Limit</label>
                            </div>

                        </div>

                    


                        <div class="row">
                            <div class="col">                                
                                <input type="text" id="wordLimit" name="{{ $id }}_wordLimit"  value="{{ $display_meta['wordLimit'] ?? '' }}" class="form-control pt-0 col-md-3">
                                <label class="form-label">Word Limit</label>
                            </div>
                        </div>                       
                    
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Rules</label>
                                <br/>
                                <input id="required" name="{{ $id }}_required" type="checkbox" @if($display_meta['required'] == "true") {{ "checked='checked" }} @endif> 
                                <label class="form-label">Required</label>                                
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