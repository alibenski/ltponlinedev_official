<div class="form-group">
<ul class="nav nav-pills">
	<li role="presentation" class="{{ Request::is('home') ? "active-home" : ""}}"><a href="{{ route('home') }}">Enrolment Instructions</a></li>
	<li role="presentation" class="{{ Request::is('home-how-to-check-status') ? "active-home" : ""}}"><a href="{{ route('home-how-to-check-status') }}">How to Check Enrolment Status</a></li>
</ul>
</div>