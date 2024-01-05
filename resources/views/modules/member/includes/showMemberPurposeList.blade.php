<div>
    @foreach ($purpose as $list)
        @php 
            $ctr = 1;
            $options = (array) json_decode($list->purpose_options, true);
            $target_score = (array) json_decode($list->target_scores, true);
        @endphp
        <div class="main_list mb-1">
            @if (strtolower($list->purpose) == "others")
                <strong>{{ $list->purpose }}</strong>
                <div class="option_value_wrapper mb-2 ml-2">
                    <span class="input_value">
                        {{ $list->purpose_options }}
                    </span>
                </div>
            @else

                
                <strong>
                    {{ $list->purpose }} 
                    @if ($list->purpose == "TOEFL") {{ " IBT" }} @endif
                </strong>

                <div class="option_value_wrapper mb-2 ml-2 mx-0 px-0">
                    @foreach ($options as $option_value) 
                        @php 
                            $key = str_replace(" ", "_", $option_value);

                            $optionIndex = strtolower($key);
                            if(isset($target_score[ $optionIndex ])) {
                                $targetScoreText = "(".$target_score[$optionIndex].")";
                            } else {
                                $targetScoreText = "";
                            }
                        @endphp
                        <span class="option_value small">
                        
                        <strong>{{ addSpaceBeforeCapitalizedLetters($option_value) }}</strong>
                        
                        {{$targetScoreText}}@if($ctr < count($options)){{","}}@endif</span>
                        @php $ctr++ @endphp
                    @endforeach 
                </div>
            @endif
        </div>
    @endforeach
</div>