<!DOCTYPE html>
<html lang="en">

<head>
    <title>MONA :)</title>
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">

        <nav class="navbar navbar-light bg-faded">
            <a class="navbar-brand" href="/">Petitions</a>
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                    <div class="dropdown-menu" aria-labelledby="supportedContentDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline float-xs-right">
                <input class="form-control" type="text" placeholder="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </nav>

        <div class="col-md-3">

            <div class="graphContainer noPadding">
                <canvas class="graph" data-data="[
                    @foreach ($weeklyStats as $key => $weeklyStat)
                        {{ $weeklyStat->count }} @if (!$loop->last) , @endif
                    @endforeach
                ]" data-labels='[
                    @foreach ($weeklyStats as $key => $weeklyStat)
                        "{{ date(' D, d.M H\h ', $weeklyStat->label) }}" @if (!$loop->last) , @endif
                    @endforeach
                ]'></canvas>
            </div>

            <div class="stats grayOut">
                <div class="col-xs-12 dailyContainer">
                    <div class="daily">
                        {{ $stats['day'] }}
                    </div>
                    <p class="label right grayOut">/24 heures</p>
                </div>
                <div class="col-xs-6">
                    <div class="weekly">
                        {{ $stats['week'] }}
                    </div>
                    <p class="label grayOut">/semaine</p>
                </div>
                <div class="col-xs-6">
                    <div class="monthly right">
                        {{ $stats['month'] }}
                    </div>
                    <p class="label right grayOut">/mois</p>
                </div>
                <div class="col-xs-12">
                    <div class="total">
                        {{ $stats['total'] }}
                    </div>
                    <p class="label grayOut">Total</p>
                </div>
            </div>

        </div>

        <div class="col-md-9">
            @yield('content')
        </div>
    </div>

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
