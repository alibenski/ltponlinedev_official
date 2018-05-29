<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
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
        <section class="fivth-section">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-md-offset-1"> 
                <div class="left-text col-md-8">
                  <h4><em>Thank You</em><br>Your request has been sent to your manager</h4>
                  <p>Once your manager approves it, our team will process your request and send you a message with the time and date of your placement test.</p>
                </div>
                <div class="right-image col-md-4">
                  <img src="ziggy/img/right-image.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="sixth-section">
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <form id="contact" action="" method="post">
                    <div class="col-md-6">
                      <fieldset>
                        <input name="name" type="text" class="form-control" id="name" placeholder="Your name..." required="">
                      </fieldset>
                    </div>
                    <div class="col-md-6">
                      <fieldset>
                        <input name="email" type="email" class="form-control" id="email" placeholder="Your email..." required="">
                      </fieldset>
                    </div>
                    <div class="col-md-12">
                      <fieldset>
                        <textarea name="message" rows="6" class="form-control" id="message" placeholder="Your message..." required=""></textarea>
                      </fieldset>
                    </div>
                    <div class="col-md-12">
                      <fieldset>
                        <button type="submit" id="form-submit" class="btn">Send Message</button>
                      </fieldset>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-5">
                <div class="right-info">
                  <ul>
                    <li><a href="#"><i class="fa fa-envelope"></i>hello@company.com</a></li>
                    <li><a href="#"><i class="fa fa-phone"></i>050 060 0780 / 050 060 0110</a></li>
                    <li><a href="#"><i class="fa fa-map-marker"></i>1186 New Street, ST 10990</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>

        <footer>
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <ul>
                  <li><a href="https://www.facebook.com/tooplate"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                  <li><a href="#"><i class="fa fa-rss"></i></a></li>
                  <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                </ul>
                <p>Copyright &copy; 2017 Company Name 
                        
                        | Design: <a href="http://www.tooplate.com" target="_parent">Tooplate</a></p>
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
