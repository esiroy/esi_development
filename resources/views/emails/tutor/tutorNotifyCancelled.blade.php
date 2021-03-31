Hi {{ $tutor->user->firstname }}

<div style="margin-top:20px; font-size: 14px">
    Good Day!
</div>

<div style="margin-top:20px; font-size: 14px">
    Cancelled lesson Schedule for {{ date("F j, Y, H:i", strtotime($scheduleItem->lesson_time)) }}
</div>

<div style="margin-top:20px; font-size: 14px">
    Member Name : {{ $member->user->firstname }} {{ $member->user->lastname ?? ''}}
</div>

<div style="margin-top:20px; font-size: 14px">
    @if (strtolower($member->communication_app) == 'skype') {
        Member Skype : {{ $member->skype_account ?? ''}}
    @elseif (strtolower($member->communication_app) == 'zoom')
        Member Zoom : {{ $member->zoom_account ?? '' }}
    @else 
        @if (isset($member->skype_account)) {
            Member Skype : {{ $member->skype_account ?? ''}}
        @else 
            Member Zoom : {{ $member->zoom_account ?? ''}}
        @endif
    @endif
</div>

<div style="margin-top:20px; font-size: 14px">
    Tutor Name : {{ $tutor->user->firstname ?? '' }}
</div>

<div style="margin-top:20px; font-size: 14px">
    Tutor Skype : {{ $tutor->skype_id ?? '' }}
</div>

<div style="margin-top:20px; font-size: 14px">
    Login URL : {{ url("/admin/login") }} using your My Tutor username and
</div>

<div style="margin-top:20px; font-size: 14px">
    password to check the details.
</div>

<div style="margin-top:20px; font-size: 14px">
    <div>
        Regards,
    </div>
    <div>
        My Tutor Team
    </div>
</div>