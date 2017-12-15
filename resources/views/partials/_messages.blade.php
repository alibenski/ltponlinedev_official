@if (Session::has('success')) 
	<div class="alert alert-success alert-block" role="alert">
		<strong>Success: </strong> {{ Session::get('success') }}
	</div>

@endif

@if (Session::has('org_change_success')) 
	<div class="alert alert-success alert-block" role="alert">
		<strong>Success: </strong> {{ Session::get('org_change_success') }}
	</div>

@endif

@if (count($errors) > 0)
	<div class="alert alert-danger alert-block" role="alert">
		<strong>Errors:</strong>
		<ul>
		@foreach ($errors->all() as $error)
			
			<li>{{ $error }}</li>
			
		@endforeach
		</ul>
	</div>

@endif