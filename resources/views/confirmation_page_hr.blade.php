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
<!-- custom css only for welcome page -->
        <link href="{{ asset('css/cover.css') }}" rel="stylesheet">
</head>
    <body>
        <nav class="navbar navbar-light bg-light">
            <div class="container d-flex">
                <span class="navbar-brand mb-0 h1 justify-content-start">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        UNOG CLM LTP Online Enrolment
                    </a>
                </span>

                <span class="navbar-brand mb-0 h1 justify-content-end">
                    <a class="navbar-brand" href="https://learning.unog.ch" target="_blank">
                        CLM Website
                    </a>
                </span>
            </div>
        </nav>
        
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div class="cover-container">
                        @include('partials._messages')
                    <div class="inner cover">
                        <img src="ziggy/img/Logo2016_transparent.png" alt="clm-logo" class="mb-4">

                        <h1 class="cover-heading">Thank you from CLM UNOG</h1>
                        <p class="lead">You are brought to this page either because... <br>
                            <ol>
                                <h3>
                                    <p><i class="fa fa-star"></i> You are <strong>the HR Learning Partner of UNOG CLM</strong> and have made a decision to approve or disapprove an enrolment course of a student under your organization</p>
                                </h3>
                                <br />
                                <h3>
                                    <p><i class="fa fa-star"></i> You have already made a decision and unfortunately, you cannot change it anymore </p>
                                </h3>
                                <br />
                                <h3>
                                    <p><i class="fa fa-star"></i> The student has cancelled the enrolment form before you could make a decision </p>
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
    </body>
</html>

