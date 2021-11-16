@foreach($gameFlavors as $gameFlavor)
    <div class="col-md-4">
        @include('game_flavor.single', ['gameFlavor' => $gameFlavor, 'loggedInUser' => $loggedInUser])
    </div>
@endforeach
