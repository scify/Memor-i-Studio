<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{url('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Memor-i Studio</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="{{ (Route::current()->getName() == 'showAllGameFlavors') ? 'active' : '' }}"><a href="{{route('showAllGameFlavors')}}"><i class="fa fa-gamepad" aria-hidden="true"></i> All Games</a></li>
            <li class="{{ (Route::current()->getName() == 'showAboutPage') ? 'active' : '' }}"><a href="{{route('showAboutPage')}}">About</a></li>
            <li class="{{ (Route::current()->getName() == 'showContactForm') ? 'active' : '' }}"><a href="{{route('showContactForm')}}">Contact</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown">Help
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="https://docs.google.com/document/d/1irOmObgn9ZwEqmwn3uXNvQTJmgt1YiMqFx5Tzp-l_Oo/edit#">English</a></li>
                    <li><a href="https://docs.google.com/document/d/1whAITueSBuaX9Gd0VY85X0cv5CWQ6CwkjbZULoBxugs/edit#">Greek</a></li>
                </ul>
            </li>
        </ul>
        <div class="pull-right">
            <ul class="nav navbar-nav">
                <li>
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <a class="pull-right" href="{{ url('/logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                              style="display: none;">{{ csrf_field() }} </form>
                    @else
                        <a class="pull-right" href="{{ url('/login') }}">
                            <i class="fa fa-sign-in" aria-hidden="true"></i> Sign in
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>