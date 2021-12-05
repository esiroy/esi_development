<div>
    @foreach ($purpose as $list)

        @php 
            $ctr = 1;
            $options = (array) json_decode($list->purpose_options, true);
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
                <strong>{{ $list->purpose }}</strong>

                <div class="option_value_wrapper mb-2 ml-2">
                    @foreach ($options as $option_value) 
                        <span class="option_value">
                            {{ $option_value }}@if ($ctr < count($options)){{ "," }} @endif                        
                            @php $ctr++ @endphp
                        </span>
                    @endforeach 
                </div>
            @endif

        </div>

    @endforeach
</div>