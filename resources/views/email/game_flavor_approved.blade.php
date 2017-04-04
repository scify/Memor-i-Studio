@extends('layouts.email')
@section('title', 'Congratulations!')
@section('body')
    <p style="text-align: center">
        Congratulations! Your game: '{{$gameFlavor->name}}' was just built!
        <br>
        Remember, that in order for the game to be publicly available for download,
        You need to click the "Make public" at the game box area in the homepage.
        <br>
        Until you make your game public, only you can download and play it.
        <br>
        Click <a href="http://memoristudio.scify.org/games"> here</a> to see, download and make it public!
    </p>
@stop