<div class="container">
<ul class="nav nav-tabs">
	<li role="presentation" class="{{ Request::is('home') ? "active" : ""}}"><a href="/home">Home</a></li>
	<li role="presentation" class="{{ Request::is('students') ? "active" : ""}}"><a href="{{ route('students.index') }}">Your Profile</a></li>
	<li role="presentation" class="{{ Request::is('submitted') ? "active" : ""}}"><a href="/submitted">Current Submitted Forms</a></li>
	<li role="presentation" class="{{ Request::is('history') ? "active" : ""}}"><a href="/history">History</a></li>
	<li role="presentation" class="{{ Request::is('whatorg') ? "active" : ""}}"><a href="/whatorg">Enrolment Forms</a></li>
	<!--<li class="dropdown">
		<button type="button" href="#" id="BtnDropdown" class="btn btn-info dropdown-toggle" data-toggle="dropdown"> Enrolment Forms <b class="caret"></b></button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="BtnDropdown">
				<li role="menu" class="{{-- Request::is('myform/create') ? "active" : ""--}} {{-- Request::is('noform/create') ? "active" : ""--}}"><a href="{{-- route('myform.create') --}}"  tabindex="-1">UN Staff Enrolment Form</a></li>
				<li role="menu" class="{{-- Request::is('selfpayform/*') ? "active" : ""--}}"><a href="{{-- route('selfpayform.create') --}}"  tabindex="-1">Self-Paying Enrolment Form</a></li>
		</ul>
	</li>-->
</ul>