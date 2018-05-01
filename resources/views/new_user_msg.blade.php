@extends('public')

@section('customcss')
<link href="{{ asset('css/cover.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="inner cover">
	<h2></h2>
	<h3 class="alert alert-primary">You will receive an email from CLM Language with your credentials.</h3> 
	<h3>Then go to the <span class="text text-primary"><u><a href="https://ltponlinedev.unog.ch">CLM Language Training Online Registration Platform</a></u></span> and use the Login option. After logging in, please follow the instructions found on the dashboard.</h3> 
</div>

@stop