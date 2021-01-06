 <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
            
                <a class="flex-sm text-sm-center text-white nav-link font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>

                 @can('tutor_access', Auth::user())
                 <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/tutor') }}">Tutor</a>
                 @endcan

                @can('manager_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                @endcan

                @can('agent_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
                @endcan               
            </nav>
        </div>
    </div>