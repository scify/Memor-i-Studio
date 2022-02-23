@extends('layouts.app')
@section('content')
    <div class="row">
        @foreach($gameVersions as $gameVersion)
            <div class="col-md-4">
                @include('game_version.single', ['gameVersion' => $gameVersion, 'user' => \Illuminate\Support\Facades\Auth::user()])
            </div>
        @endforeach
    </div>
@endsection
