@extends('layouts.email')
@section('title', 'New Contact form message')
@section('body')
    <p style="text-align: center">
        Ο χρήστης με email {{$senderEmail}} σας έστειλε το ακόλουθο μήνυμα:
        {{$senderMailBody}}
    </p>
@stop