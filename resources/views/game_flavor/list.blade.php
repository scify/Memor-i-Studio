@extends('common.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
        @foreach($gameVersions as $gameVersion)
                <div class="col-md-3">
                    @include('game_flavor.single', ['gameVersion' => $gameVersion, 'user' => \Illuminate\Support\Facades\Auth::user()])
                </div>
        @endforeach
        </div>
    </div>
@endsection