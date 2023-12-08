@php
/*
@note: the pagination from laravel is not allowing this component to fetch the members
@todo: make a blade memberlist instead
<member-list-component                               
:members="{{ json_encode($members) }}"
api_token="{{ Auth::user()->api_token }}"
csrf_token="{{ csrf_token() }}"
:can_member_access="{{ $can_member_access }}"                
:can_member_edit="{{ $can_member_edit }}"
:can_member_delete="{{ $can_member_delete }}"
:can_member_view="{{ $can_member_view }}"
/>
*/
@endphp