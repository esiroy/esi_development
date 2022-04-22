<member-level-component 
    :memberinfo="{{  json_encode(Auth::user()->memberInfo)  }}" 
    api_token="{{ Auth::user()->api_token }}" 
    csrf_token="{{ csrf_token() }}">
</member-level-component>

