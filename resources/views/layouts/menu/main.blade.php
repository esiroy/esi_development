        <div class="bg-lightblue">
          <div class="container px-0">
            <nav class="nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/lesson') }}">My Page</a>

                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/member') }}">User</a>

                @can('manage_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('admin/questionnaires') }}">Manage</a>
                @endcan



                @can('manage_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/minitest/categories') }}">
                  Mini Test
                </a>
                @endcan

                
                @can('report_access', Auth::user())
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/lessons') }}">Report</a>
                @endcan





                @if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER' || Auth::user()->user_type == 'TUTOR' )
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/writing/entries/1') }}">
                  Writing
                </a>
                @endif

                
                @if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER')
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/customerchatsupport') }}">
                  Customer Chat Support
                </a>
                @endif




            </nav>
          </div>
        </div>
