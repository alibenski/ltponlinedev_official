<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
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