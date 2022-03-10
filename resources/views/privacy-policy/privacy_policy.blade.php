@extends('layouts.app')

@section('content')
    @include('privacy-policy.content-' . \Illuminate\Support\Facades\App::getLocale())
@endsection
