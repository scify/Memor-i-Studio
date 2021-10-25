@extends('layouts.email')
@section('title', 'Congratulations!')
@section('body')
    <p style="text-align: center">
        {!! __('messages.congratulations_your_game') !!}: '{{$gameFlavor->name}}' {!! __('messages.is_ready') !!}!
        <br>
        {!! __('messages.congratulations_email_message') !!}
    </p>
@stop
