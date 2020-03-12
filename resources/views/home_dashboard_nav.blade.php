<div class="form-group">
<ul class="nav nav-pills">
	<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('home') ? "active-home" : ""}}" href="{{ route('home') }}">Enrolment Instructions</a></li>
	<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('home-how-to-check-status') ? "active-home" : ""}}" href="{{ route('home-how-to-check-status') }}">How to Check Enrolment Status</a></li>
</ul>
</div>