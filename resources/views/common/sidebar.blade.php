<div class="layer-container">
    <!-- BEGIN MENU LAYER -->
    <div class="menu-layer">
        <ul>
            <li class="{{ (Route::current()->getName() == 'createGameFlavorIndex') ? 'open' : '' }}">
                <a href="{{ route('createGameFlavorIndex') }}"><i class="fa fa-plus" aria-hidden="true"></i> Create new version </a>
            </li>
        </ul>
    </div>
    <!--.menu-layer-->
    <!-- END OF MENU LAYER -->
</div>
