@extends('layouts.email')
@section('title', 'Game submission')
@section('body')
    <p style="text-align: center">
        The game you created: '{{$gameFlavor->name}}' was just submitted. Thank you!
        <br>
        What follows next:
        <br>
        An admin will prepare your game for downloading. Once the game is ready, you will be notified vie e-mail and
        be able to download it from <a href="http://memoristudio.scify.org/games"> here.</a>
        <br>
        If you have any further inquiries, please <a href="http://memoristudio.scify.org/contact"> contact us.</a>
    </p>
@stop