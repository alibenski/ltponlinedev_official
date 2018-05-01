@extends('public')

@section('customcss')
{{-- <link href="{{ asset('css/cover.css') }}" rel="stylesheet"> --}}
@stop

@section('content')
<div class="inner cover">
	<h2 class="alert alert-warning">Thank you for registering to the CLM Language Training Programme</h2>
	<h3>You will soon receive an email from CLM Language with your credentials.</h3> 
	<h4>Once received, go to the <span class="text text-primary"><u><a href="https://ltponlinedev.unog.ch">CLM Language Training Online Registration Platform</a></u></span> and use the Login option. After logging in, please follow the instructions found on the dashboard.</h4> 
</div>

@stop