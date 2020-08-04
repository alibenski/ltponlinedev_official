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
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/cover.css') }}" rel="stylesheet">
</head>
    <body>
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

                        <div class="container">
                            <div class="row">
                                <div class="col-md m-3">
                                    <div class="card-body btn-bg-img b1">
                                    <p>You are already an UNOG-CLM Language Training Programme participant.
                                    Click “login” to enrol on the language courses.</p>
                                    <p class="lead btn-bottom">
                                    <a href="{{ url('/login') }}" class="btn btn-lg btn-success">Login</a>
                                    </p>
                                    </div>
                                </div>
    
                                <div class="col-md m-3">
                                    <div class="card-body btn-welcome-img b1">
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
                        <p>Centre for Learning and Multilingualism &trade;</p>
                    </div>

                    <!-- Footer Section -->

                    @include('partials._foot')

                </div>
            </div>
        </div>

        <!-- Modal form to show a post -->
        <div id="showModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Please answer the question below</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-form-label question" for="id">Are you a UN staff member with Umoja profile?</label>
                                <a href="{{ url('/newuser/create') }}" class="btn btn-outline-secondary">Yes</a>
                                <a href="{{ route('get-new-outside-user') }}" class="btn btn-outline-secondary">No </a>
                            </div>                                
                        </form>
                    </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div> --}}
                </div>
            </div>
        </div>

        <!-- Scripts -->
        @include('partials._js')
        <script>
            $(document).on('click', '.show-modal', function() {
                $('#showModal').modal('show');
            });
        </script>
    </body>
</html>