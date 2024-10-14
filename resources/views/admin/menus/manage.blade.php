<div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary {{ Request::is('admin/questionnaires*') ? 'active text-dark' : '' }}" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary {{ Request::is('admin/announcement*') ? 'active text-dark' : '' }}" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary {{ Request::is('admin/pages*') ? 'active text-dark' : '' }}" href="{{ url('admin/pages') }}">Pages</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary {{ Request::is('admin/accounts*') ? 'active text-dark' : '' }}" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary {{ Request::is('admin/course*') ? 'active text-dark' : '' }}" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary {{ Request::is('admin/company*') ? 'active text-dark' : '' }}" href="{{ url('admin/company') }}">Company</a>
            </nav>
        </div>
    </div>    