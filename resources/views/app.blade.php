<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking API - @yield('title')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{mix('js/app.js')}}"></script>
    @stack('scripts')
</head>

<body>
<main>

    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary" style="width: 240px">
        <div>
            <div class="w-100 container">
                <img class="img-responsive w-100" src="images/reservation.png"/>
            </div>
            <div class="app-name mb-1 m-2 text-center">Booking App</div>
        </div>
        <hr>
        @include('partials.menu')
    </div>
    <div class="b-example-divider"></div>

    <div class="p-5 w-100 scrollarea bg-main">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
</main>
</body>
</html>
