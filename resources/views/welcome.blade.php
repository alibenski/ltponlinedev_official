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
                            <div class="col-md-12">
                                <img src="{{ asset('img/Logo2016_transparent.png') }}" alt="CLM_logo">
                            <h1 class="cover-heading tlt" style="margin-bottom: 50px;"> UNOG-CLM  Language Training Programme (LTP) Online Enrolment Platform</h1>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6 b1">
                                    <div class="panel panel-success">
                                      <div class="panel-body btn-bg-img b1">
                                        <p>You are already an UNOG-CLM Language Training Programme participant.
                                        Click “login” to enrol on the language courses.</p>
                                        <p class="lead btn-bottom">
                                        <a href="{{ url('/login') }}" class="btn btn-lg btn-success">Login</a>
                                        </p>
                                      </div>
                                    </div>
                                </div>

                                <div class="col-md-6 b1">
                                    <div class="panel panel-primary">
                                      <div class="panel-body btn-welcome-img b1">
                                        <p>
                                        You are a new UNOG-CLM Language Training Programme participant.
                                        Click “join” to create an account and enrol on the language courses.
                                        </p>
                                        <p class="lead btn-bottom">
                                        {{-- <a href="/newuser/create" class="btn btn-lg btn-primary">Join</a> --}}
                                        <button class="btn btn-lg btn-primary show-modal">Join</button>
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

            <!-- Modal form to show a post -->
            <div id="showModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="control-label col-sm-5 question" for="id">Are you a UN staff member with an Umoja profile?</label>
                                    <a href="{{ url('/newuser/create') }}" class="btn btn-default">Yes</a>
                                    <a href="{{ route('get-new-outside-user') }}" class="btn btn-default">No </a>
                                </div>                                
                            </form>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-warning" data-dismiss="modal">
                                    <span class='glyphicon glyphicon-remove'></span> Close
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scripts -->
            @include('partials._js')

            <script src="{{ asset('textillate/assets/jquery.fittext.js') }}"></script>
            <script src="{{ asset('textillate/assets/jquery.lettering.js') }}"></script>
            <script src="{{ asset('textillate/jquery.textillate.js') }}"></script>

            <script>
               $(document).on('click', '.show-modal', function() {
                    $('.modal-title').text('Please answer the question below');
                    $('#showModal').modal('show');
                });
            </script>
    </body>
</html>