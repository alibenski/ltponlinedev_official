@extends('public')

@section('customcss')
<link href="{{ asset('css/cover.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="inner cover">
	<span><h1><i class="fa fa-lg fa-warning btn-space"></i><strong>Oops: </strong></h1></span><h2 class="cover-heading text-center">The link is no longer valid.</h2> <h2>There are 2 possibilities:</h2> 
	<h3>
		<ul class="text-left fa-ul">
			<li><i class="fa-li fa fa-spinner fa-spin"></i> The link has already reached its 24-hour expiration limit</li>
			<li><i class="fa-li fa fa-spinner fa-spin"></i> The link has already been used before</li>
		</ul>
	</h3>
	
	
</div>

@stop