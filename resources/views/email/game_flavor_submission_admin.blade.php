@extends('layouts.email')
@section('title', 'Game approval')
@section('body')
    <p style="text-align: center">
        The creator of the game: '{{$gameFlavor->name}}' just submitted it for approval (game flavor
        id: {{$gameFlavor->id}}).
        <br>
        <br>
        Creator details:
        <br>
        id: {{$gameFlavor->creator->id}}
        <br>
        email: {{$gameFlavor->creator->email}}
        <br>
        name: {{$gameFlavor->creator->name}}
        <br>
        <br>
        Click <a href="https://memoristudio.scify.org/games"> here</a> to check and publish this game flavor.
    </p>
@stop
