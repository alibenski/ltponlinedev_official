<div class="container">
    <div class="row">
        <div class="col-md-12 alert">
        	@if(is_null($term)) @else
        		@if($term->Enrol_Date_Begin <= \Carbon\Carbon::now() && \Carbon\Carbon::now() <= $term->Enrol_Date_End) <h4>Current Enrolment Season: <strong> {{ $term->Term_Code }} {{ $term->Comments }} {{ $term->Term_Name }} </strong></h4>  @else <h5><strong><span class="alert alert-danger">Enrolment Closed</span></strong></h5> @endif
        	@endif
        </div>
    </div>
<ul class="nav nav-pills">
	<li role="presentation" class="{{ Request::is('home') ? "active" : ""}}"><a href="/home">Home</a></li>
	<li role="presentation" class="{{ Request::is('students') ? "active" : ""}}"><a href="{{ route('students.index') }}">Your Profile</a></li>
	{{-- <li role="presentation" class="{{ Request::is('submitted') ? "active" : ""}}"><a href="/submitted">Current Submitted Forms</a></li> --}}
	<li role="presentation" class="{{ Request::is('previous-submitted') ? "active" : ""}}"><a href="/previous-submitted">Submitted Forms</a></li>
	<li role="presentation" class="{{ Request::is('history') ? "active" : ""}}"><a href="/history">History</a></li>

	<li role="presentation" class="pull-right {{ Request::is('whatorg') ? "active" : ""}}"><a href="/whatorg"><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Register/Enrol Here</strong></a></li>
	<!--<li class="dropdown">
		<button type="button" href="#" id="BtnDropdown" class="btn btn-info dropdown-toggle" data-toggle="dropdown"> Enrolment Forms <b class="caret"></b></button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="BtnDropdown">
				<li role="menu" class="{{-- Request::is('myform/create') ? "active" : ""--}} {{-- Request::is('noform/create') ? "active" : ""--}}"><a href="{{-- route('myform.create') --}}"  tabindex="-1">UN Staff Enrolment Form</a></li>
				<li role="menu" class="{{-- Request::is('selfpayform/*') ? "active" : ""--}}"><a href="{{-- route('selfpayform.create') --}}"  tabindex="-1">Self-Paying Enrolment Form</a></li>
		</ul>
	</li>-->
</ul>
