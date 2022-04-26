

<member-time-manager-component 
    :memberinfo="{{  json_encode($memberInfo )  }}" 
    api_token="{{ Auth::user()->api_token }}" 
    csrf_token="{{ csrf_token() }}">
</member-time-manager-component>