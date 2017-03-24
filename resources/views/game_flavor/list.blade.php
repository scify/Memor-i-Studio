@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
        @foreach($gameFlavors as $gameFlavor)
                <div class="col-md-4">
                    @include('game_flavor.single', ['gameFlavor' => $gameFlavor, 'user' => \Illuminate\Support\Facades\Auth::user()])
                </div>
        @endforeach
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