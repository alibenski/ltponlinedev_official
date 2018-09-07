@extends('layouts.errors')

@section('content')
    <div class='col-md-10 col-md-offset-4'>
    	<h1>Error occured:</h1>
        <h2>{{ $exception->getMessage() }}</h2>
    </div>

@endsection