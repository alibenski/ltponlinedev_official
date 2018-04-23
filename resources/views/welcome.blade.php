<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials._head')
<!-- custom css only for welcome page -->
        <link href="{{ asset('css/cover.css') }}" rel="stylesheet">
        <link href="{{ asset('textillate/assets/animate.css') }}" rel="stylesheet">
        {{-- <link href="{{ asset('textillate/assets/style.css') }}" rel="stylesheet"> --}}
</head>
    <body>
{{--         <nav class="navbar navbar-default navbar-static-top">
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
        </nav> --}}
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
                            <h1 class="cover-heading tlt" style="margin-bottom: 50px;">Welcome to the UNOG CLM Online Enrolment Website</h1>
                            <div class="col-md-12">
                                <div class="col-md-6 b1">
                                    <div class="panel panel-primary">
                                      <div class="panel-heading"></div>
                                      <div class="panel-body btn-welcome-img b1">
                                        <p>If you have not received an e-mail concerning your credentials from the CLM Language Secretariat, please click the sign-up button to register.</p>
                                        <p class="lead btn-bottom">
                                        <a href="/register" class="btn btn-lg btn-primary">Join</a>
                                        </p>
                                      </div>
                                    </div>
                                </div>

                                <div class="col-md-6 b1">
                                    <div class="panel panel-success">
                                      <div class="panel-heading"></div>
                                      <div class="panel-body btn-bg-img b1">
                                        <p>If you received an e-mail concerning your credentials from the CLM Language Secretariat, please click the log-in button to access the platform.</p>
                                        <p class="lead btn-bottom">
                                        <a href="{{ url('/login') }}" class="btn btn-lg btn-success">Log-in</a>
                                        </p>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <p class="lead">If you received an e-mail concerning your credentials from CLM Language Secretariat, please click the log-in button to access the platform.</p> --}}
                            <p>Centre for Learning and Multilingualism &trade;</p>
                            {{-- <p class="lead">If you received the e-mail concerning your credentials from CLM Language Secretariat, please click the log-in button to access the platform.</p>
                            <p class="lead">
                            <a href="{{ url('/login') }}" class="btn btn-lg btn-default">Log-in</a>
                            </p> --}}
                        </div>

                        <!-- Footer Section -->

                        @include('partials._foot')

                    </div>
                </div>
            </div>
                        <!-- Scripts -->
                        @include('partials._js')

            <script src="{{ asset('textillate/assets/jquery.fittext.js') }}"></script>
            <script src="{{ asset('textillate/assets/jquery.lettering.js') }}"></script>
            <script src="{{ asset('textillate/jquery.textillate.js') }}"></script>

            <script>
                // $(function () {
                //     $('.tlt').textillate({ 
                //         in: { effect: 'fadeInUp' }, 
                //         out: { effect: 'swing', shuffle: true},
                //         loop: true
                //     });
                // })
            </script>
    </body>
</html>