
    <!--[start] EikenScoreComponent- -->
    <div id="examination-score-EIKEN" class="section examScoreHolder" style="height:420px; overflow-y: scroll; overflow-x: hidden">

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 5 Score </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_5" name="EIKEN_grade_5"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 5 Score</option>
                    @for($item = 0; $item <= 850; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 4 Score </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_4" name="EIKEN_grade_4" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 4 Score</option>
                    @for($item = 0; $item <= 1000; $item++) 
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-5">                  
                <div class="strike">
                    <span class="text-secondary small">1st Stage</span>
                </div>                
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 3 1st stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_3_1st_stage" name="EIKEN_grade_3_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 3 1st Stage Score</option>
                    @for($item = 0; $item <= 1650; $item++)                     
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>                

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade pre 2 1st Stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_pre_2_1st_stage" name="EIKEN_grade_pre_2_1st_stage" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 2 1st Stage Score</option>
                    @for($item = 0; $item <= 1800; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>        


        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 2 1st stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_2_1st_stage" name="EIKEN_grade_2_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 2 1st Stage Score</option>
                    @for($item = 0; $item <= 1950; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>           


        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade pre 1 1st Stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_pre_1_1st_stage" name="EIKEN_grade_pre_1_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 1 1st Stage Score</option>
                    @for($item = 0; $item <= 2250; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>        

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 1 1st Stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN_grade_1_1st_stage" name="EIKEN_grade_1_1st_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 1 1st Stage Score</option>
                    @for($item = 0; $item <= 2250; $item++)                    
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>     

        <div class="row pt-2">
            <div class="col-5">                  
                <div class="strike">
                    <span class="text-secondary small">2nd Stage</span>
                </div>                
            </div>
        </div>

        <!-- 2nd stage -->
        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 3 2nd stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN-grade_3_2nd_stage" name="EIKEN-grade_3_2nd_stage" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 3 2nd Stage Score</option>
                    @for($item = 0; $item <= 550; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor

                </select>
            </div>
        </div>                

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade pre 2 2nd Stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN-grade_pre_2_2nd_stage" name="EIKEN-grade_pre_2_2nd_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 2 2nd Stage Score</option>
                    @for($item = 0; $item <= 600; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0">{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>        


        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 2 2nd stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN-grade_2_2nd_stage" name="EIKEN-grade_2_2nd_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 2 2nd Stage Score</option>
                    @for($item = 0; $item <= 650; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>           


        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade pre 1 2nd Stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN-grade_pre_1_2nd_stage" name="EIKEN-grade_pre_1_2nd_stage" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade pre 1 2nd Stage Score</option>
                    @for($item = 0; $item <= 750; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>        

        <div class="row pt-2">
            <div class="col-12">                       
                <div class="field-label"> <span class="text-danger">*</span> Grade 1 2nd Stage </div>                
            </div>
            <div class="col-8">
                <select id="EIKEN-grade_1_2nd_stage" name="EIKEN-grade_1_2nd_stage"  class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Select Grade 1 2nd Stage Score</option>
                    @for($item = 0; $item <= 850; $item++)
                    <option value="{{ $item }}" class="mx-0 px-0" >{{ $item }}</option>
                    @endfor
                </select>
            </div>
        </div>             




    </div>
    <!--[end]-->
