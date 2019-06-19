@extends('public')

@section('customcss')
<link href="{{ asset('css/cover.css') }}" rel="stylesheet">
<style>
html {
	height: 100%;
	background-color: #fff;
}

body {
	height: 100%;
	background-color: #fff;
	background: url("/img/chain-297842_960_720.png") 100%;
	background-size: cover;
}

hr {
    clear: both;
    visibility: hidden;
}

.cover {
	background-color: rgba(108, 122, 137, 50%);
	padding: 10px;
}

.mastfoot p {
	color: rgba(108, 122, 137, 100%);
}

</style>

@stop

@section('content')
<div class="inner cover">
	<span><h1><i class="fa fa-lg fa-chain-broken btn-space cover-heading" aria-hidden="true"></i><strong>Oops! </strong>The link is no longer valid.</h1></span> 
	<h2>Possible reasons:</h2> 
	<h3>
		<ul class="fa-ul">
			<li class="list-margin"><i class="fa-li fa fa-chevron-right"></i> The link has already reached its 24-hour expiration limit.</li>
			<li class="list-margin"><i class="fa-li fa fa-chevron-right"></i> The link has already been used for e-mail confirmation update.</li>
			<li class="list-margin"><i class="fa-li fa fa-chevron-right"></i> There is a much newer link sent to your inbox than the one you clicked.</li>
		</ul>
	</h3>
</div>

@stop