@if (Session::has('success')) 
<!--success variable contains the message coming from PostController class Session::flash-->
	<div class="alert alert-success alert-block" role="alert">
		<strong>Success: </strong> {{ Session::get('success') }}
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