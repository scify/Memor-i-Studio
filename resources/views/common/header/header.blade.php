<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Memor-i Studio</title>
    <meta name="description" content="{{ __('messages.meta_tags_description') }}">
    <meta name="keywords" content="memori studio, blind games, games for the blind, memori game, memory game">
    <meta name="og:title" property="og:title" content="{{ config('app.name') }}">
    <meta name="og:type" property="og:type" content="website">
    <meta name="og:url" property="og:url" content="{{ url()->current() }}">
    <meta name="og:image" property="og:image" content="{{ asset('/assets/img/memori.png') }}">
    <meta name="og:description" property="og:description" content="{{ __('messages.meta_tags_description') }}">
    <meta name="og:site_name" property="og:site_name" content="Memor-i Studio">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Memor-i Studio"/>
    <meta name="twitter:description" content="{{ __('messages.meta_tags_description') }}"/>
    <meta name="twitter:image" content="{{ asset('/assets/img/memori.png') }}"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=5" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-touch-fullscreen" content="yes">
    <link rel="shortcut icon" href="{{asset("/assets/img/favicon.ico")}}" type="image/x-icon">
    <link rel="icon" href="{{asset("/assets/img/favicon.ico")}}" type="image/x-icon">

    <!--The elixir function takes as parameter a versioned file relative to the public folder-->
    <link rel="stylesheet" href="{{mix('css/vendors.css')}}">
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <link rel="manifest" href="{{ asset('mix-manifest.json') }}">
</head>
