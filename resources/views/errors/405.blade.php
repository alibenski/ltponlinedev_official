@extends('layouts.errors')

@section('content')
    <div class='col-lg-4 offset-lg-4'>
		<h2 class="text-center">@if($exception->getMessage()) {{ $exception->getMessage() }} @else Error 405: Method Not Allowed @endif</h2>
	</div>
@endsection