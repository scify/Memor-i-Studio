<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Memor-i Studio</title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-touch-fullscreen" content="yes">
    <link rel="shortcut icon" href="{{asset("/assets/img/favicon.ico")}}" type="image/x-icon">
    <link rel="icon" href="{{asset("/assets/img/favicon.ico")}}" type="image/x-icon">

    <!--The elixir function takes as parameter a versioned file relative to the public folder-->
    <link rel="stylesheet" href="{{mix('css/vendors.css')}}">
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <link rel="manifest" href="{{ asset('mix-manifest.json') }}">
    @if(config('app.google_analytics_id'))
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', '{{ config('app.google_analytics_id') }}', 'auto');
            ga('send', 'pageview');

        </script>
    @endif
</head>
