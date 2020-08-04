<!doctype html>
<html class="no-js" lang="">
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170278635-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-170278635-1');
        </script>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>404 Error Page</title>
        <meta name="description" content="Saleh Riaz - UI/UX Engineer. Designer. Computer Scientist">
        <meta name="keywords" content="ui engineer, ux, saleh, riaz, qureshi, website, softwares, salehriaz, salehriazq, computer scientist, design, visual design, saleh riaz qureshi"/>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <link rel="stylesheet" href="css/main.css"> --}}
        <link rel="stylesheet" href="{{ asset('css/404-main.css') }}">

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    </head>

    <body class="bg-purple">
        
        <div class="stars">
            <div class="custom-navbar">
                <div class="brand-logo">
                    {{-- <img src="http://salehriaz.com/404Page/img/logo.svg" width="80px"> --}}
                    <img src="{{ asset('img/logo.png') }}" width="80px">
                </div>
                {{-- <div class="navbar-links">
                    <ul>
                      <li><a href="http://salehriaz.com/404Page/404.html" target="_blank">Home</a></li>
                      <li><a href="http://salehriaz.com/404Page/404.html" target="_blank">About</a></li>
                      <li><a href="http://salehriaz.com/404Page/404.html" target="_blank">Features</a></li>
                      <li><a href="http://salehriaz.com/404Page/404.html" class="btn-request" target="_blank">Request A Demo</a></li>
                    </ul>
                </div> --}}
            </div>
            <div class="central-body">
                <img class="image-404" src="{{ asset('img/404.svg') }}" width="300px">
                {{-- <a href="http://salehriaz.com/404Page/404.html" class="btn-go-home" target="_blank">GO BACK HOME</a> --}}
                <a href="{{ route('teacher-dashboard') }}" class="btn-go-home">GO BACK HOME</a>
            </div>
            <div class="objects">
                <img class="object_rocket" src="{{ asset('img/rocket.svg') }}" width="40px">
                <div class="earth-moon">
                    <img class="object_earth" src="{{ asset('img/earth.svg') }}" width="100px">
                    <img class="object_moon" src="{{ asset('img/moon.svg') }}" width="80px">
                </div>
                <div class="box_astronaut">
                    <img class="object_astronaut" src="{{ asset('img/astronaut.svg') }}" width="140px">
                </div>
            </div>
            <div class="glowing_stars">
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>

            </div>

        </div>

        <div class="btn-credit">
            Design by <a href="http://salehriaz.com/" target="_blank">Saleh Riaz</a>
        </div>

    </body>
</html>