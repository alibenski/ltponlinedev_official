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
                        <div class="inner cover">
                        <img src="ziggy/img/Logo2016_transparent.png" alt="clm-logo" class="mb-4">

                            @include('partials._messages')
                            
                            <h1 class="cover-heading">Thank you from CLM UNOG</h1>
                            <p class="lead">You are brought to this page either because... 
                                <br>(1) you are a <strong>manager</strong> and have made a decision to approve or disapprove an enrolment course, if that is the case, thank you for your decision and have a great day,</p>
                            <p class="lead">or (2) you have already made a decision and unfortunately, you cannot change it anymore.</p>
                        </div>
                        <!-- Footer Section -->
                        @include('partials._foot')
                    </div>
                </div>
            </div>
    </body>
</html>

