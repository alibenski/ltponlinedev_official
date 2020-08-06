<div class="container">
    <div class="row">
        <div class="col-md-12 alert">
        	@if(is_null($term)) @else
        		@if($term->Enrol_Date_Begin <= \Carbon\Carbon::now() && \Carbon\Carbon::now() <= $term->Enrol_Date_End) <h4>Current Enrolment for: <strong> {{ $term->Term_Name }} {{ $term->Comments }} Term</strong></h4>  @else <h5><strong><span class="alert alert-danger">Enrolment Closed</span></strong></h5> @endif
        	@endif
        </div>
	</div>
	
	<ul class="nav nav-pills ml-auto nav-fill">
		<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('home*') ? "active" : ""}}" href="/home">Home</a></li>
		<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('students') ? "active" : ""}}" href="{{ route('students.index') }}">My Profile</a></li>
		<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('previous-submitted') ? "active" : ""}}" href="/previous-submitted">Submitted Forms</a></li>
		<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('history') ? "active" : ""}}" href="/history">History</a></li>

		<li role="presentation" class="nav-item justify-content-end">@if(is_null($term)) @else 
			@if($term->Enrol_Date_Begin <= \Carbon\Carbon::now() && \Carbon\Carbon::now() <= $term->Enrol_Date_End) <a class="nav-link {{ Request::is('whatorg') ? "active" : ""}}"  href="/whatorg "><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Register/Enrol Here</strong></a></li>  
			@else 
			<a href="#" class="text-danger"><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Enrolment Closed</strong></a>
			@endif
		@endif
	</ul>
