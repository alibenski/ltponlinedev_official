<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170278635-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-170278635-1');
    </script>
    @include('partials._head')
    @include('partials._js')
    </head>
    <body>
        <div id="app">
            @include('partials._nav')
            @include('partials._messages')
            @yield('content')
        
            <!-- Footer Section -->
                @include('partials._foot')
            <!-- Scripts -->
                @yield('scripts_link')<!-- use only for override -->
                @yield('scripts_code')
    

    </body>
</html>