<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="BUSINESS" value="BUSINESS" class="main_option" {{ checkbox_ticker( $purpose['BUSINESS'] ?? old('BUSINESS') , 'BUSINESS') }}> Business
    <div class="BUSINESS ml-4 sub_options">
        <input type="checkbox" name="BUSINESS_option[]" value="Basic" {{ checkbox_ticker($purpose_option['BUSINESS_Basic'] ?? old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_BasicScore" name="BUSINESS_Basic" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Basic Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_Basic']) && $target_score['BUSINESS_Basic'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>            
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_option[]" value="Intermediate" {{ checkbox_ticker($purpose_option['BUSINESS_Intermediate'] ?? old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_IntermediateScore" name="BUSINESS_Intermediate" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Intermediate Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_Intermediate']) && $target_score['BUSINESS_Intermediate'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach;
                </select>             
            </div>
        </div>

        <input type="checkbox" name="BUSINESS_option[]" value="Advance" {{ checkbox_ticker($purpose_option['BUSINESS_Advance'] ?? old('BUSINESS_option'), 'Advance') }}> Advance <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="BUSINESS_advanceScore" name="BUSINESS_Advance" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Business Advance Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['BUSINESS_Advance']) && $target_score['BUSINESS_Advance'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>              
            </div>
        </div>


    </div>
</div>
<!--[end] Checkbox Group -->