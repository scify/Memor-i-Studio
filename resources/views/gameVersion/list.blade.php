@extends('common.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
        @foreach($gameVersions as $gameVersion)
                <div class="col-md-3">
                    @include('gameVersion.single', ['gameVersion' => $gameVersion])
                </div>
        @endforeach
        </div>
    </div>
@endsection