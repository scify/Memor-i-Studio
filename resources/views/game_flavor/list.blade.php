@extends('common.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
        @foreach($gameFlavors as $gameFlavor)
                <div class="col-md-3">
                    @include('game_flavor.single', ['gameFlavor' => $gameFlavor, 'user' => \Illuminate\Support\Facades\Auth::user()])
                </div>
        @endforeach
        </div>
    </div>
@endsection