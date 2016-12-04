<!DOCTYPE html>
<html lang="en">

<head>
    <title>MONA :)</title>
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
</head>

<body class="@yield('bodyClass')">

    @include('navigation')

    <div class="container-fluid">
        @yield('content')
    </div>

    @include('footer')

    <script src="{{ elixir('js/app.js') }}"></script>
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', '{{ env('
            GOOGLE_ANALYTICS ') }}', 'auto');
        ga('send', 'pageview');
    </script>
</body>

</html>
