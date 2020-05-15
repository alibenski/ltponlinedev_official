@extends('layouts.errors')

@section('content')
	<div class="container">
	    <div class='col-md-10 col-md-offset-1'>
	        <h2 class="text-center">{{ $exception->getMessage() }}</h2>
	    </div>
	</div>

@endsection