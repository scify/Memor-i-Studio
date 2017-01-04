<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{url('home')}}">Memor-i Studio</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="{{url('home')}}"><i class="fa fa-home" aria-hidden="true"></i></a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        @if(\Illuminate\Support\Facades\Auth::check())
            <div class="pull-right">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="pull-right" href="{{ url('/logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }} </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>