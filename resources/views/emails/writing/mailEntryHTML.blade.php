<div class="container">        
    @foreach($fieldsArray as $item)
        
        <div class='name-wrapper'>
            <div class="itemName">{{ $item['name'] ?? '' }}</div>        
        </div>

        @if ($item['type'] == 'uploadfield')
            @if (isset($item['value']))
                <div class="value-wrapper">
                <div class="itemValue"><a href="{{ url(Storage::url($item['value'])) }}">{{ basename($item['value']) }}</a></div>
                </div>
                @php 
                    //$writingFields = new \App\Models\WritingEntries;
                    //$writingFields->generateFileAnchorLink( $item['value'] );
                @endphp
            @endif
        @else
            <div class="value-wrapper">
                <div class="itemValue">{{ $item['value'] ?? '' }}</div>
            </div>
        @endif

    @endforeach
</div>

<style>
    div {
        font-size: 13px;
    }

    .container {
        border:1px solid #e9e9e9
    }

    .name-wrapper {
        margin:0px 0px 4px;
        padding: 5px 0px 5px;
        background-color: #EAF2FA
    }    

    .itemName {
        margin-left:10px; 
        font-weight:bold;
        font-size: 14px;
    }

    .itemValue {
        margin-left: 25px;
        min-height:15px;
        padding-bottom: 3px
    }


</style>