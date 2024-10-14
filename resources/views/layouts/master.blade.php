@guest
    @include('layouts.public')
@else

    @if (Auth::user()->user_type == "ADMINISTRATOR")
        @include('layouts.admin')
    @else
        @include('layouts.esi-app')

    @endif

@endguest

