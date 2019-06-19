<ul class="nav nav-pills">
	<li role="presentation" class="{{ Request::is('admin/newuser') ? "active" : ""}}"><a href="{{ route('newuser.index') }}">New User Administration</a></li>
	<li role="presentation" class="{{ Request::is('admin/newuser-index-all') ? "active" : ""}}"><a href="{{ route('newuser-index-all') }}">View All New User Requests</a></li>
</ul>