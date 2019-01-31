@extends('public')

@section('customcss')
<link href="{{ asset('css/cover.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="inner cover">
	<span><h1><i class="fa fa-lg fa-warning btn-space cover-heading"></i><strong>Oops: </strong>The link is no longer valid.</h1></span> 
	<h2>There are 2 possibilities:</h2> 
	<h3>
		<ul class="fa-ul">
			<li class="list-margin"><i class="fa-li fa fa-star"></i> The link has already reached its 24-hour expiration limit.</li>
			<li class="list-margin"><i class="fa-li fa fa-star"></i> The link has already been used.</li>
		</ul>
	</h3>
	
	
</div>

@stop