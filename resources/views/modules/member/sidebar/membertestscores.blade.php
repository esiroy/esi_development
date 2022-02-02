@php
    $memberInfo = \App\Models\Member::where('user_id', Auth::user()->id)->first();
@endphp

<member-score-commponent :memberinfo="{{ json_encode($memberInfo) }}" api_token="{{ Auth::user()->api_token }}" csrf_token="{{ csrf_token() }}"></member-score-commponent>