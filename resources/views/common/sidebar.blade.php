<div class="layer-container">
    <!-- BEGIN MENU LAYER -->
    <div class="menu-layer">
        <ul>
            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                <li class="{{ (Route::current()->getName() == 'createGameVersionIndex') ? 'open' : '' }}">
                    <a href="{{ route('createGameVersionIndex') }}"><i class="fa fa-plus-square"
                                                                       aria-hidden="true"></i> {!! __('messages.create_new_base_version') !!}
                    </a>
                </li>
                <li class="{{ (Route::current()->getName() == 'showAllGameVersions') ? 'open' : '' }}">
                    <a href="{{ route('showAllGameVersions') }}"><i class="fa fa-th-list"
                                                                    aria-hidden="true"></i> {!! __('messages.all_base_versions') !!}
                    </a>
                </li>
                <li class="{{ (Route::current()->getName() == 'showGameFlavorsSubmittedForApproval') ? 'open' : '' }}">
                    <a href="{{ route('showGameFlavorsSubmittedForApproval') }}"><i class="fa fa-th-list"
                                                                                    aria-hidden="true"></i> {!! __('messages.games_submitted_for_approval') !!}
                    </a>
                </li>
            @endif
            <li class="{{ (Route::current()->getName() == 'showGameVersionSelectionForm') ? 'open' : '' }}">
                <a href="{{ route('showGameVersionSelectionForm') }}"><i class="fa fa-plus"
                                                                         aria-hidden="true"></i> {!! __('messages.create_new_game') !!}
                </a>
            </li>
            <li class="{{ (Route::current()->getName() == 'showAllGameFlavors') ? 'open' : '' }}">
                <a href="{{ route('showAllGameFlavors') }}"><i class="fa fa-list"
                                                               aria-hidden="true"></i> {!! __('messages.all_games') !!}
                </a>
            </li>
            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                <li class="{{ (Route::current()->getName() == 'showAllGameFlavorReports') ? 'open' : '' }}">
                    <a href="{{ route('showAllGameFlavorReports') }}"><i class="fa fa-exclamation"
                                                                         aria-hidden="true"></i> {!! __('messages.user_reports') !!}
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!--.menu-layer-->
    <!-- END OF MENU LAYER -->
</div>
