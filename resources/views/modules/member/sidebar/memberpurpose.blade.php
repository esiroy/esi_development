@php  

    $purposeModel = new \App\Models\Purpose();        
    $purpose = $purposeModel->getMemberPurpose( $memberInfo->user_id );
@endphp

<member-purpose-component 
        :memberinfo="{{ json_encode($memberInfo) }}" 
        :purpose="{{ json_encode($purpose) }}" 
        api_token="{{ Auth::user()->api_token }}" 
        csrf_token="{{ csrf_token() }}"></member-purpose-component>