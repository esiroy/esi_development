@php
    $memberLevel = \App\Models\Member::where('user_id', Auth::user()->id)->first();
@endphp

<member-level-commponent 
        :memberinfo="{{ json_encode($memberLevel) }}" 
        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"></member-level-commponent>