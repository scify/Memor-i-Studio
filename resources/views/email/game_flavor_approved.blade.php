@extends('layouts.email')
@section('title', 'Congratulations!')
@section('body')
    <p style="text-align: center">
        Congratulations! Your game: '{{$gameFlavor->name}}' was just published!
        Click <a href="http://staging-memori.scify.org/games"> here</a> to see and download it.
    </p>
@stop