<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials._head')
<!-- custom css only for welcome page -->
        <link href="{{ asset('css/cover.css') }}" rel="stylesheet">
</head>
    <body>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Online Enrolment
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="http://learning.unog.ch" target="_blank">CLM Website</a></li>
                    </ul>
                </div>
            </div>
        </nav>
            <div class="site-wrapper">
                <div class="site-wrapper-inner">
                    <div class="cover-container">
                        <div class="inner cover">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger alert-block" role="alert">
                                    <strong>Errors:</strong>
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <h1 class="cover-heading">Welcome to the UNOG CLM Online Enrolment Website</h1>
                            <p class="lead">If you are an existing student, please click the log-in button to enrol.</p>
                            <p class="lead">
                            <a href="{{ url('/login') }}" class="btn btn-lg btn-default">Log-in</a>
                            </p>
                        </div>
                        <!-- Footer Section -->
                        @include('partials._foot')
                    </div>
                </div>
            </div>
                        <!-- Scripts -->
                        @include('partials._js')
    </body>
</html>