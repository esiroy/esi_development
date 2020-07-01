<div id="sidebar-wrapper" class="bg-light border-right">

    <div class="list-group list-group-flush {{ (Request::segment(2)=='module')? 'dropup' : '' }}">
       <a href="{{ route('admin.dashboard.index') }}" class="list-group-item list-group-item-action bg-light">Dashboard</a>

    

        <!-- /* Modules Start */ -->
        @can('module_access')
        <a href="#submenu-modules" class="list-group-item list-group-item-action dropdown-toggle bg-light"
            data-toggle="collapse" aria-expanded="false">Modules</a>

        <ul id="submenu-modules"
            class="list-group-flush list-unstyled my-0 border-bottom collapse {{ (Request::segment(2)=='module')? 'show' : 'hide' }}">

            @can('formbuilder_access')
                <li class="list-group-item">
                    <svg class="bi bi-card-text" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                        <path fill-rule="evenodd" d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                    <a href="{{ route('admin.module.formbuilder.index') }}" class="small">Form Builder</a>
                </li>
            @endcan

            @can('filemanager_access')
            <li class="list-group-item">
                <svg class="bi bi-folder" width="
                1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z"/>
                    <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z"/>
                </svg>

                <a href="{{ route('admin.module.filemanager.index') }}" class="small">File Manager</a>
            </li>
            @endcan

        </ul>
        @endcan
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
                        <svg class="bi bi-lock" width="1.1em" height="1.1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1zm-7-1a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7zm0-3a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                        </svg>
                        <a href="{{ route('admin.permissions.index') }}" class="small">Permissions</a>
                    </li>
                @endcan


                @can('role_access')
                <li class="list-group-item">
                    <svg class="bi bi-briefcase" width="1.1em" height="1.1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-6h-1v6a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-6H0v6z"/>
                        <path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5v2.384l-7.614 2.03a1.5 1.5 0 0 1-.772 0L0 6.884V4.5zM1.5 4a.5.5 0 0 0-.5.5v1.616l6.871 1.832a.5.5 0 0 0 .258 0L15 6.116V4.5a.5.5 0 0 0-.5-.5h-13zM5 2.5A1.5 1.5 0 0 1 6.5 1h3A1.5 1.5 0 0 1 11 2.5V3h-1v-.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V3H5v-.5z"/>
                    </svg>
                    <a href="{{ route('admin.roles.index') }}" class="small">Roles</a>
                </li>
                @endcan

                @can('user_access')
                <li class="list-group-item">
                    <svg class="bi bi-people" width="1.1em" height="1.1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.995-.944v-.002.002zM7.022 13h7.956a.274.274 0 0 0 .014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C13.688 10.629 12.718 10 11 10c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 0 0 .022.004zm7.973.056v-.002.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10c-1.668.02-2.615.64-3.16 1.276C1.163 11.97 1 12.739 1 13h3c0-1.045.323-2.086.92-3zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                    </svg>
                    <a href="{{ route('admin.users.index') }}" class="small">Users</a>
                </li>
                @endcan

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
