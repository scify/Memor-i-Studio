@php use Illuminate\Support\Facades\App;use Illuminate\Support\Facades\Auth; @endphp
<nav class="navbar navbar-default navbar-fixed-top" id="app-horizontal-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse-content" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Memor-i Studio</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-content">
            <ul class="nav navbar-nav">
                <li class="{{ (Route::current()->getName() == 'showAllGameFlavors') ? 'active' : '' }}"><a
                            href="{{route('showAllGameFlavors')}}"><i class="fa fa-gamepad"
                                                                      aria-hidden="true"></i> {!! __('messages.all_games') !!}
                    </a></li>
                <li class="{{ (Route::current()->getName() == 'showGameVersionSelectionForm') ? 'active' : '' }}"><a
                            href="{{route('showGameVersionSelectionForm')}}"><i class="fa fa-lightbulb-o"
                                                                                aria-hidden="true"></i> {!! __('messages.create_new_game') !!}
                    </a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check() && Auth::user()->isAdmin())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true"
                           aria-expanded="false">Admin <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('createGameVersionIndex') }}"><i class="fa fa-plus-square"
                                                                                   aria-hidden="true"></i> {!! __('messages.create_new_base_version') !!}
                                </a></li>
                            <li><a href="{{ route('showAllGameVersions') }}"><i class="fa fa-th-list"
                                                                                aria-hidden="true"></i> {!! __('messages.all_base_versions') !!}
                                </a></li>
                            <li><a href="{{ route('showGameFlavorsSubmittedForApproval') }}"><i
                                            class="fa fa-th-list"
                                            aria-hidden="true"></i> {!! __('messages.games_submitted_for_approval') !!}
                                </a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('showAllGameFlavorReports') }}"><i class="fa fa-exclamation"
                                                                                     aria-hidden="true"></i> {!! __('messages.user_reports') !!}
                                </a></li>
                            <li><a href="{{ route('platform_statistics') }}"><i class="fa fa-sort-numeric-asc"
                                                                                aria-hidden="true"></i> {!! __('messages.platform_statistics') !!}
                                </a></li>
                        </ul>
                    </li>
                @endif
                <li class="{{ (Route::current()->getName() == 'showAboutPage') ? 'active' : '' }}"><a
                            href="{{route('showAboutPage')}}">{!! __('messages.about') !!}</a></li>
                <li class="{{ (Route::current()->getName() == 'showContactForm') ? 'active' : '' }}"><a
                            href="{{route('showContactForm')}}">{!! __('messages.contact') !!}</a></li>
                <li><a href="{{ __('messages.help_link') }}" target="_blank">{!! __('messages.help') !!}</a></li>
                <li>
                    @if(Auth::check())
                        <a class="pull-right" href="{{ url('/logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> {!! __('auth.logout') !!}</a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                              style="display: none;">{{ csrf_field() }} </form>
                    @else
                        <a class="pull-right" href="{{ url('/login') }}">
                            <i class="fa fa-sign-in" aria-hidden="true"></i> {!! __('auth.sign_in') !!}
                        </a>
                    @endif
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="lang-dropdown">
                        <img
                                loading="lazy"
                                src="{{ asset('assets/img/' . App::getLocale() . '.png') }}"
                                height="20px" width="33.3px" alt="Language">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lang-dropdown">
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'en') }}">
                                <img
                                        loading="lazy"
                                        class="mr-2"
                                        src="{{ asset('assets/img/en.png') }}"
                                        height="20px" width="33.3px" alt="English">
                                English
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'el') }}">
                                <img
                                        loading="lazy"
                                        class="mr-2"
                                        src="{{ asset('assets/img/el.png') }}"
                                        height="20px" width="33.3px" alt="Ελληνικά">
                                Ελληνικά
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'es') }}">
                                <img
                                        loading="lazy"
                                        class="mr-2"
                                        src="{{ asset('assets/img/es.png') }}"
                                        height="20px" width="33.3px" alt="Español">
                                Español
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'it') }}">
                                <img
                                        loading="lazy"
                                        class="margin-right-5"
                                        src="{{ asset('assets/img/it.png') }}"
                                        height="20px" width="33.3px" alt="Italiano">
                                Italiano
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
