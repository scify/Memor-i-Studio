@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 centerMessage" style="text-align: center">
            {!! __('messages.download_games') !!} <i class="fa fa-download" aria-hidden="true"></i>
            <i class="fa fa-arrow-right" aria-hidden="true"></i> {!! __('messages.put_your_headphones_on') !!} <i
                    class="fa fa-headphones" aria-hidden="true"></i>
            <i class="fa fa-arrow-right" aria-hidden="true"></i> {!! __('messages.play') !!}! <i class="fa fa-gamepad"
                                                                                                 aria-hidden="true"></i>
        </div>
        <div class="col-md-12">
            @foreach($gameFlavors as $gameFlavor)
                <div class="col-md-4">
                    @include('game_flavor.single', ['gameFlavor' => $gameFlavor, 'loggedInUser' => $loggedInUser])
                </div>
            @endforeach
            <div class="col-md-4 text-align-center memoriActionBtns margin-bottom-50">
                <a href="https://www.gamesfortheblind.org/" target="_blank"
                   class="btn btn-success btn-ripple width-percent-100">
                    <h1><i class="fa fa-gamepad" aria-hidden="true"></i> {!! __('messages.more_games') !!}</h1>
                </a>
            </div><!--.col-md-6-->
        </div>

    </div>
    @include('game_flavor_report.modals')
@endsection
@section('additionalFooter')
    <script>
        $(function () {
            var gameFlavorController = new GameFlavorsController();
            gameFlavorController.init();
        });
    </script>
@endsection
