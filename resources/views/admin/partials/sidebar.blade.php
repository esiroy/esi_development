<div id="sidebar-wrapper" class="bg-light border-right">

    <div class="list-group list-group-flush {{ (Request::segment(2)=='module')? 'dropup' : '' }}">
        <a href="{{ route('admin.dashboard.index') }}"
            class="list-group-item list-group-item-action bg-light">Dashboard</a>

        <!-- /* Modules Start */ -->
        <a href="#submenu-modules" class="list-group-item list-group-item-action dropdown-toggle bg-light"
            data-toggle="collapse" aria-expanded="false">Modules</a>

        <ul id="submenu-modules"
            class="list-group-flush list-unstyled my-0 border-bottom collapse {{ (Request::segment(2)=='module')? 'show' : 'hide' }}">

            <!--
                <li class="list-group-item">
                    <a href="{{ route('admin.formbuilder.index') }}" class="small">Form Builder</a>
                </li>
            -->

            <li class="list-group-item">
                <a href="#" class="small">File Manager</a>
            </li>
        </ul>
        <!--/* Modules End */ -->
    </div>

    <div class="list-group list-group-flush {{ (Request::segment(2)=='user-management')? 'dropup' : '' }} ">
        @can('user_management_access')
            <a href="#submenu-user-management" class="list-group-item list-group-item-action dropdown-toggle bg-light"
                data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">User Management</a>
            <ul id="submenu-user-management"
                class="list-group-flush list-unstyled my-0 border-bottom collapse {{ (Request::segment(2)=='user-management')? 'show' : 'hide' }}">

                @can('permission_access')
                    <li class="list-group-item">
                        <a href="{{ route('admin.permissions.index') }}" class="small">Permissions</a>
                    </li>
                @endcan


                <li class="list-group-item">
                    <a href="{{ route('admin.roles.index') }}" class="small">Roles</a>
                </li>

                <li class="list-group-item">
                    <a href="{{ route('admin.users.index') }}" class="small">Users</a>
                </li>
            </ul>
        @endcan
    </div>

    <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action bg-light" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}
        </a>

        <form id="logout" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>


</div>
