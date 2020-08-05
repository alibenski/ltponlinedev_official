@extends('public')
@section('customcss')
<link href="{{ asset('css/cover.css') }}" rel="stylesheet">
@stop
@section('content')
<div>
	<img src="ziggy/img/Logo2016_transparent.png" alt="clm_logo">
</div>
<div>
	<h2 class="alert">Thank you for registering to the CLM Language Training Programme</h2>
</div>
<div class="card">
	<div class="card-body">
		<p class="text-secondary">You will soon receive an email from CLM Language with your credentials.</p>
		<p class="text-secondary">Once received, login to the <span class="text-primary"><u><a class="text-secondary" href="{{ route('login') }}">CLM Language Training Online Registration Platform</a></u></span> and follow the instructions found on the dashboard.</p> 
		<p class="text-secondary">Important Note: For security reasons, please expect a considerable amount of delay when creating and validating your credentials. Thank you for your kind understanding.</p> 
	</div>
</div>
@stop