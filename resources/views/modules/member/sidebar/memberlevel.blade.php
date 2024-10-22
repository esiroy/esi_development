<member-level-component 
    :memberinfo="{{  json_encode($memberInfo )  }}" 
    api_token="{{ Auth::user()->api_token }}" 
    csrf_token="{{ csrf_token() }}"
    is_netenglish="{{ $is_netenglish }}"
>
</member-level-component>

