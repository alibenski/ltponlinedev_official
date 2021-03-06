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

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="referrer" content="origin-when-cross-origin">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} @yield('tabtitle')</title>

        <!-- Bootstrap -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
        {{-- <script src="https://use.fontawesome.com/e401b0faaf.js"></script> --}}
        <!-- Custom styles for this template -->
		<link rel="stylesheet" href="ziggy/css/tooplate-style.css">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
		<script src="ziggy/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>
	@include('partials._messages')
        <section class="fivth-section">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-md-offset-1"> 
                <div class="left-text col-md-8">
                  <h4><em>Thank You <strong>{{ Auth::user()->name }}</strong></em><br>Your placement test request has been submitted </h4>
                  <p>At anytime, you can go to the tab “Submitted Forms” to check the status of your enrolment. Please note that <u>your enrolment will only be confirmed after approval from your learning focal point (if applicable)</u>. Once all is confirmed, you will receive an email with an indication of the date and time of your class.</p>
	                <div>
	                	<a href="/home" class="btn btn-success">Back to Home Page</a>
	                </div>
                </div>
                <div class="right-image col-md-4">
                  <img src="ziggy/img/Logo2016_transparent.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </section>

         <footer>
          <div class="container">
            <div class="row">
              <div class="col-md-12">

                <p class="text-center"><strong>Copyright &copy; {{date("Y")}} <a href="https://learning.unog.ch">UNOG CLM</a></strong> All rights reserved.</p>

              </div>
            </div>
          </div>
        </footer>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="ziggy/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="ziggy/js/vendor/bootstrap.min.js"></script>

        <script src="ziggy/js/plugins.js"></script>
        <script src="ziggy/js/main.js"></script>
	
</body>
</html>
