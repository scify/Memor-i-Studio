@extends('layouts.email')
@section('title', 'Welcome to Memor-i Studio!')
@section('body')
    <p style="text-align: center">
        {!! __('messages.welcome_email_message') !!}
    </p>
@stop
