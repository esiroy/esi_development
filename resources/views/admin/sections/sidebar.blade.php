<div id="sidebar-wrapper" class="bg-light border-right">
    <div class="list-group list-group-flush">
        <a href="{{ route('admin.dashboard.index') }}" class="list-group-item list-group-item-action bg-light">Dashboard</a>

        <a href="#submenu-modules" class="list-group-item list-group-item-action bg-light" data-toggle="collapse"
            aria-expanded="false" class="dropdown-toggle">Modules</a>

        <ul class="list-unstyled collapse" id="submenu-modules">
            <a href="{{ route('admin.formbuilder.index') }}" class="list-group-item list-group-item-action bg-light small">Form Builder</a>
            <a href="#" class="list-group-item list-group-item-action bg-light small">File Manager</a>
            <a href="#" class="list-group-item list-group-item-action bg-light small">Profile</a>
            <a href="#" class="list-group-item list-group-item-action bg-light small">Settings</a>
        </ul>
    </div>
</div>
