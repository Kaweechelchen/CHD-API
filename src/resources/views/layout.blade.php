<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MONA :)</title>
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            @yield('content')
        </div>

        <script src="{{ elixir('js/app.js') }}"></script>
    </body>
</html>
