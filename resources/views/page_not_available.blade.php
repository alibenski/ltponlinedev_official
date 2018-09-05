<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
    @include('partials._head')
	<link rel="stylesheet" href="{{ asset('css/page-not-available.css') }}">
    </head>

	<body>
	  <section id="not-found">
	    <div id="title">404 Error Page</div>
	    <div class="circles">
	      <p>404<br>
	       <small>ACCOUNT CREATION CLOSED</small>
	      </p>
	      <span class="circle big"></span>
	      <span class="circle med"></span>
	      <span class="circle small"></span>
	    </div>
	  </section>
	  <h2 class="text-center" style="color: #fff"><strong>The Registration Form to create new user accounts is currently closed.</strong></h2>
	</body>
</html>