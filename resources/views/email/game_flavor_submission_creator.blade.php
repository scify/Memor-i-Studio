@extends('layouts.email')
@section('title', 'Game approval')
@section('body')
    <p style="text-align: center">
        The game you created: '{{$gameFlavor->name}}' was just submitted for approval.

        What follows next:
        <br>
        An admin will check your game for compliance with the platform rules. Once the game is approved, you will
        be able to download it from <a href="http://staging-memori.scify.org/games"> here.</a>
        <br>
        If you have any further inquiries, please <a href="http://staging-memori.scify.org/contact"> contact us.</a>
    </p>
@stop