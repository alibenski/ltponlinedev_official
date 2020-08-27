@extends('layouts.errors')

@section('content')
	<div class="container">
	    <div class='col-lg-4 offset-lg-4'>
	        <h2 class="text-center">@if($exception->getMessage()) {{ $exception->getMessage() }} @else Error 403: Forbidden @endif</h2>
	    </div>
	</div>

@endsection