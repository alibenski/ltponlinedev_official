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
                        CLM Online Enrolment
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
                            <img src="ziggy/img/Logo2016_transparent.png" alt="clm-logo">

                            @include('partials._messages')

                            <h1 class="cover-heading">Thank you very much</h1>
                            <p class="lead">You are brought to this page either because... <br>
                                <ol>
                                    <h3>
                                        <p><i class="fa fa-star"></i> You are <strong>the HR Learning Partner of CLM UNOG</strong> and have made a decision to approve or disapprove an enrolment course of your staff member or,</p>
                                    </h3>
                                    <h3>
                                        <p><i class="fa fa-star"></i> You have already made a decision and unfortunately, you cannot change it anymore.</p>
                                    </h3>
                                    <br>
                                </ol>
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

