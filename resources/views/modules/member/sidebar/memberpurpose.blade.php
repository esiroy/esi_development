@php
    $memberInfo = \App\Models\Member::where('user_id', Auth::user()->id)->first();
    $purposeModel = new \App\Models\Purpose();        
    $purpose = $purposeModel->getMemberPurpose(Auth::user()->id);
@endphp

<member-purpose-component 
        :memberinfo="{{ json_encode($memberInfo) }}" 
        :purpose="{{ json_encode($purpose) }}" 
        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"></member-purpose-component>