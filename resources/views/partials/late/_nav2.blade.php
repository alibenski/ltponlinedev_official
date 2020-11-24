<div class="container">
    <div class="row">
        <div class="col-md-12 alert">
        	@if(is_null($term)) @else
        		@if($term->Enrol_Date_Begin <= \Carbon\Carbon::now() && \Carbon\Carbon::now() <= $term->Enrol_Date_End) <h4>Current Enrolment for: <strong> {{ $term->Term_Name }} {{ $term->Comments }} Term</strong></h4>  @else <div class="alert alert-danger"><h5 class="text-center"><strong>Enrolment Closed. You are accessing this page due to special permissions.</strong></h5></div> @endif
        	@endif
        </div>
	</div>
	
	<ul class="nav nav-pills ml-auto nav-fill">
		<li role="presentation" class="nav-item"><a class="nav-link {{ Request::is('home*') ? "active" : ""}}" href="/home">Home</a></li>

		<li role="presentation" class="nav-item justify-content-end">@if(is_null($term)) @else 
			@if($term->Enrol_Date_Begin <= \Carbon\Carbon::now() && \Carbon\Carbon::now() <= $term->Enrol_Date_End) <a class="nav-link {{ Request::is('whatorg') ? "active" : ""}}"  href="/whatorg "><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Register/Enrol Here</strong></a></li>  
			@else 
			<a href="#" class="text-danger"><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Enrolment Closed</strong></a>
			@endif
		@endif
	</ul>
