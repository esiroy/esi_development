<div class="container" style="border:1px solid #e9e9e9">        
    @foreach($fieldsArray as $item)        
        <div class='name-wrapper' style="margin:0px 0px 4px; padding: 5px 0px 5px; background-color: #EAF2FA">
            <div class="itemName" style="margin-left:10px; font-weight:bold; font-size: 14px;">{{ $item['name'] ?? '' }}</div>        
        </div>
        @if ($item['type'] == 'uploadfield')
            @if (isset($item['value']))
                <div class="value-wrapper">
                    <div class="itemValue" style="margin-left: 25px; min-height:15px; padding-bottom: 3px">
                        <a href="{{ url(Storage::url($item['value'])) }}" download>{{ basename($item['value']) }}</a>
                    </div>
                </div>
            @endif
        @else
            <div class="value-wrapper">
                <div class="itemValue" style="margin-left: 25px; min-height:15px; padding-bottom: 3px">{!! $item['value'] ?? '' !!}</div>
            </div>
        @endif
    @endforeach
</div>