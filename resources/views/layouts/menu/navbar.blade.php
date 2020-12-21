<!-- Right Side Of Navbar -->

<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
    <!--
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
        @if(Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
        @endif
        -->
    @else
    <!--
            <li class="nav-item dropdown">                            
            <a id="navbarDropdown" class="nav-link dropdown-toggle blue" href="#" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>

                @if(Auth::user()->first_name != '') 
                    {{ ucfirst(Auth::user()->first_name) }} 
                    <span class="caret"></span>
                @elseif (Auth::user()->tutorInfo->name_en != '') 
                    {{ ucfirst(Auth::user()->tutorInfo->name_en) }} (tutor) 
                    <span class="caret"></span>
                @elseif (Auth::user()->managerInfo->name_en != '') 
                    {{ ucfirst(Auth::user()->managerInfo->name_en) }} 
                    <span class="caret"></span>
                @elseif (Auth::user()->agentInfo->name_en != '') 
                    {{ ucfirst(Auth::user()->managerInfo->name_en) }} 
                    <span class="caret"></span>
                @endif
            </a>
            -->

    <!--
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.AdminLogout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('admin.AdminLogout') }}"
                    method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            -->

    </li>
    @endguest
</ul>