@extends('public')

@section('customcss')
<link href="{{ asset('css/cover.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="inner cover">
	<span><h1><i class="fa fa-lg fa-warning btn-space"></i><strong>Warning: </strong></h1></span><h2 class="cover-heading text-center">The link has already been used for e-mail confirmation update.</h2>
	
</div>

@stop