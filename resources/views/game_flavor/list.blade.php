@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach($gameFlavors as $gameFlavor)
                <div class="col-md-4">
                    @include('game_flavor.single', ['gameFlavor' => $gameFlavor, 'user' => \Illuminate\Support\Facades\Auth::user()])
                </div>
            @endforeach
            <div class="col-md-4 text-align-center memoriActionBtns margin-bottom-50">
                <a href="http://www.gamesfortheblind.org/" target="_blank" class="btn btn-success btn-ripple width-percent-100">
                    <h1><i class="fa fa-gamepad" aria-hidden="true"></i> More games</h1>
                </a>
            </div><!--.col-md-6-->
        </div>

    </div>
@endsection
@section('additionalFooter')
    <script>
        $(function() {
            $("[id^=tooltipWindows-]").tooltip();
        });
    </script>
@endsection