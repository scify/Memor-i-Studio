@if($gameFlavors->isEmpty())
    <div class="col-md-12 text-center margin-bottom-20">
        <h4 class="games-message">{!! __('messages.no_games_found') !!}</h4>
    </div>
@else
    <div class="col-md-12 text-center margin-bottom-20">
        <h4 class="games-message">{!! __('messages.found') !!} <span
                    class="games-num">{{ $gameFlavors->count() }}</span> {!! __('messages.games') !!}:</h4>
    </div>
@endif
@foreach($gameFlavors as $gameFlavor)
    <div class="col-md-4">
        @include('game_flavor.single', ['gameFlavor' => $gameFlavor, 'loggedInUser' => $loggedInUser])
    </div>
@endforeach
