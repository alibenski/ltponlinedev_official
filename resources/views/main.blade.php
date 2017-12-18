<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
    @include('partials._head')
    </head>
    <body>
        <div id="app">
            @include('partials._nav')
            @include('partials._messages')
            @include('partials._nav2')
            @yield('content')
        
            <!-- Footer Section -->
                @include('partials._foot')
            <!-- Scripts -->
                @yield('scripts_link')<!-- use only for override -->
                @include('partials._js')
                @yield('scripts_code')
    

    </body>
</html>