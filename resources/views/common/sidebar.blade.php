<div class="layer-container">
    <!-- BEGIN MENU LAYER -->
    <div class="menu-layer">
        <ul>
            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                <li class="{{ (Route::current()->getName() == 'createGameVersionIndex') ? 'open' : '' }}">
                    <a href="{{ route('createGameVersionIndex') }}"><i class="fa fa-plus-square" aria-hidden="true"></i> Create new version </a>
                </li>
                <li class="{{ (Route::current()->getName() == 'showAllGameVersions') ? 'open' : '' }}">
                    <a href="{{ route('showAllGameVersions') }}"><i class="fa fa-th-list" aria-hidden="true"></i> All Game Versions </a>
                </li>
            @endif
            <li class="{{ (Route::current()->getName() == 'showGameVersionSelectionForm') ? 'open' : '' }}">
                <a href="{{ route('showGameVersionSelectionForm') }}"><i class="fa fa-plus" aria-hidden="true"></i> Create new flavor </a>
            </li>
            <li class="{{ (Route::current()->getName() == 'showAllGameFlavors') ? 'open' : '' }}">
                <a href="{{ route('showAllGameFlavors') }}"><i class="fa fa-list" aria-hidden="true"></i> All Game Flavors </a>
            </li>
        </ul>
    </div>
    <!--.menu-layer-->
    <!-- END OF MENU LAYER -->
</div>
