<!--[start] Checkbox Group -->
<div>
    <input type="checkbox" name="DAILY_CONVERSATION" value="DAILY CONVERSATION" class="main_option" {{ checkbox_ticker( $purpose['DAILY_CONVERSATION'] ?? old('DAILY_CONVERSATION') , 'DAILY CONVERSATION') }}> Daily Conversation
    <div class="DAILY_CONVERSATION ml-4 sub_options">
        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Basic" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Basic'] ?? old('BUSINESS_option'), 'Basic') }}> Basic <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="DAILY_CONVERSATION_Basic" name="DAILY_CONVERSATION_Basic" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Daily Conversation Basic Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['DAILY_CONVERSATION_Basic']) && $target_score['DAILY_CONVERSATION_Basic'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>             
            </div>
        </div>

        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Intermediate" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Intermediate'] ?? old('BUSINESS_option'), 'Intermediate') }}> Intermediate <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="DAILY_CONVERSATION_Intermediate" name="DAILY_CONVERSATION_Intermediate" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Daily Conversation Intermediate Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['DAILY_CONVERSATION_Intermediate']) && $target_score['DAILY_CONVERSATION_Intermediate'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>                
            </div>
        </div>

        <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Advance" {{ checkbox_ticker($purpose_option['DAILY_CONVERSATION_Advance'] ?? old('BUSINESS_option'), 'Advance') }}> Advance <br/>
        <div class="row ml-4 target_score">
            <div class="col-md-4">Target Score</div>
            <div class="col-md-8">
                <select id="DAILY_CONVERSATION_Advance" name="DAILY_CONVERSATION_Advance" class="form-control form-control-sm pl-0">
                    <option value="" class="mx-0 px-0">Target Daily Conversation Advance Score</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" class="mx-0 px-0" @if( isset($target_score['DAILY_CONVERSATION_Advance']) && $target_score['DAILY_CONVERSATION_Advance'] == $level) {{ ' selected ' }} @endif>{{ $level }}</option>
                    @endforeach; 
                </select>               
            </div>
        </div>


    </div>
</div>
<!--[end] Checkbox Group -->