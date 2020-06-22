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
    </head>
    <body>
            <!-- navBar Section -->
            @include('partials._navNoRegister')
            <div class="site-wrapper">
                <div class="site-wrapper-inner">
                    <div class="cover-container">
                        @include('partials._messages')
                        @yield('content')
        
                        <!-- Footer Section -->
                        @include('partials._foot')
                    </div>
                </div>
            </div>
            <!-- Scripts -->
                @yield('scripts_link')
                @include('partials._js')
                @yield('scripts_code')
    

    </body>
</html>