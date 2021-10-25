@extends('layouts.email')
@section('title', 'Game submission')
@section('body')
    <p style="text-align: center">
        {!! __('messages.the_game_you_created') !!}: '{{$gameFlavor->name}}' {!! __('messages.was_submitted') !!}!
        <br>
        {!! __('messages.game_created_message') !!}
    </p>
@stop
