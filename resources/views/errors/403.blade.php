@extends('layouts.errors')

@section('content')
    <div class='col-md-10 col-md-offset-3'>
        <h2>{{ $exception->getMessage() }}</h2>
    </div>

@endsection