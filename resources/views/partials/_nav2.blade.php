<div class="container">
<ul class="nav nav-tabs">
  <li role="presentation" class="{{ Request::is('home') ? "active" : ""}}"><a href="/home">Home</a></li>
  <li role="presentation" class="{{ Request::is('*/create') ? "active" : ""}}"><a href="{{ route('myform.create') }}">Enrolment Form</a></li>
  <li role="presentation" class="{{ Request::is('/') ? "active" : ""}}"><a href="#">Tab C</a></li>
</ul>