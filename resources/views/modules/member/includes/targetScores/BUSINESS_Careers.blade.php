<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS_CAREERS" value="BUSINESS CAREERS" class="main_option" {{ checkbox_ticker( $purpose['BUSINESS_CAREERS'] ?? old('BUSINESS_CAREERS') , 'BUSINESS CAREERS') }}> Business Careers(職業別英語）
    <div class="BUSINESS_CAREERS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Medicine" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Medicine'] ?? old('BUSINESS_CAREERS_option'), 'Medicine') }}> Medicine <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Medicine" name="BUSINESS_CAREERS_Medicine" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Medicine Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Medicine']) && $target_score['BUSINESS_CAREERS_Medicine'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>                
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Nursing" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Nursing'] ?? old('BUSINESS_CAREERS_option'), 'Nursing') }}> Nursing <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Nursing" name="BUSINESS_CAREERS_Nursing" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Nursing Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Nursing']) && $target_score['BUSINESS_CAREERS_Nursing'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Pharmaceutical" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Pharmaceutical'] ?? old('BUSINESS_CAREERS_option'), 'Pharmaceutical') }}> Pharmaceutical <br/>        
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Pharmaceutical" name="BUSINESS_CAREERS_Pharmaceutical" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Pharmaceutical Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Pharmaceutical']) && $target_score['BUSINESS_CAREERS_Pharmaceutical'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Accounting" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Accounting'] ?? old('BUSINESS_CAREERS_option'), 'Accounting') }}> Accounting <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_Accounting" name="BUSINESS_CAREERS_Accounting" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Accounting Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Accounting']) && $target_score['BUSINESS_CAREERS_Accounting'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>    

            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Legal Professionals" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Legal_Professionals'] ?? old('BUSINESS_CAREERS_option'), 'Legal Professionals') }}> Legal Professionals <br/>        
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Legal_Professionals" name="BUSINESS_CAREERS_Legal_Professionals" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Legal Professionals Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Legal_Professionals']) && $target_score['BUSINESS_CAREERS_Legal_Professionals'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;  
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Finance" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Finance'] ?? old('BUSINESS_CAREERS_option'), 'Finance') }}> Finance <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Finance" name="BUSINESS_CAREERS_Finance" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Finance Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Finance']) && $target_score['BUSINESS_CAREERS_Finance'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Technology" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Technology'] ?? old('BUSINESS_CAREERS_option'), 'Technology') }}> Technology <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Technology" name="BUSINESS_CAREERS_Technology" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Technology Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Technology']) && $target_score['BUSINESS_CAREERS_Technology'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Commerce" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Commerce'] ?? old('BUSINESS_CAREERS_option'), 'Commerce') }}> Commerce <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Commerce" name="BUSINESS_CAREERS_Commerce" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Commerce Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Commerce']) && $target_score['BUSINESS_CAREERS_Commerce'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Tourism" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Tourism'] ?? old('BUSINESS_CAREERS_option'), 'Tourism') }}> Tourism <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Tourism" name="BUSINESS_CAREERS_Tourism" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Tourism Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Tourism']) && $target_score['BUSINESS_CAREERS_Tourism'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>             
            </div>
        </div>
                        
        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Cabin Crew" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Cabin_Crew'] ?? old('BUSINESS_CAREERS_option'), 'Cabin Crew') }}> Cabin Crew <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Cabin_Crew" name="BUSINESS_CAREERS_Cabin_Crew" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Cabin Crew  Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Cabin_Crew']) && $target_score['BUSINESS_CAREERS_Cabin_Crew'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Marketing and Advertising" {{ checkbox_ticker( $purpose_option['BUSINESS_CAREERS_Marketing_and_Advertising'] ?? old('BUSINESS_CAREERS_option'), 'Marketing and Advertising') }}> Marketing and Advertising <br/>                                
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_CAREERS_Marketing_and_Advertising" name="BUSINESS_CAREERS_Marketing_and_Advertising" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Marketing and Advertising Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_CAREERS_Marketing_and_Advertising']) && $target_score['BUSINESS_CAREERS_Marketing_and_Advertising'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>             
            </div>
        </div>

    </div>
</div>
<!--[end] Checkbox Group -->
