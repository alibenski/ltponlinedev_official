@if (Session::has('success')) 
	<div class="mt-4 alert alert-success alert-block text-center" role="alert">
		<strong>Message: </strong> {{ Session::get('success') }}
	</div>

@endif

@if (Session::has('error')) 
    <div class="mt-4 alert alert-danger" role="alert">
        <strong>Error: </strong> {{ Session::get('error') }}
    </div>
@endif

@if (Session::has('warning')) 
	<div class="mt-4 alert alert-warning alert-block" role="alert">
		<strong>Message: </strong> {{ Session::get('warning') }}
	</div>

@endif

@if (Session::has('org_change_success')) 
	<div class="mt-4 alert alert-success alert-block" role="alert">
		<strong>Message: </strong> {{ Session::get('org_change_success') }}
	</div>

@endif

@if (Session::has('cancel_success')) 
	<div class="mt-4 alert alert-warning alert-block" role="alert">
		<strong>Cancelled: </strong> {{ Session::get('cancel_success') }}
	</div>

@endif

@if (Session::has('redirect_back_to_own_profile')) 
	<div class="mt-4 alert alert-danger alert-block" role="alert">
		<span><i class="fa fa-lg fa-warning btn-space"></i><strong>Warning: </strong></span> {{ Session::get('redirect_back_to_own_profile') }}
	</div>

@endif

@if (Session::has('overlimit')) 
    <div class="mt-4 alert alert-danger" role="alert">
        <strong>Sorry: </strong> {{ Session::get('overlimit') }}
    </div>
@endif

@if (Session::has('enrolment_closed')) 
    <div class="mt-4 alert alert-danger" role="alert">
        <strong>Sorry: </strong> {{ Session::get('enrolment_closed') }}
    </div>
@endif

@if (Session::has('interdire-msg')) 
	<div class="mt-4 alert alert-danger alert-block" role="alert">
		<span><i class="fa fa-lg fa-warning btn-space"></i><strong> Sorry: </strong></span> {{ Session::get('interdire-msg') }}
	</div>

@endif

@if (count($errors) > 0)
	<div class="mt-4 alert alert-danger alert-block" role="alert">
		<strong>Errors:</strong>
		<ul>
		@foreach ($errors->all() as $error)
			
			<li>{{ $error }}</li>
			
		@endforeach
		</ul>
	</div>

@endif