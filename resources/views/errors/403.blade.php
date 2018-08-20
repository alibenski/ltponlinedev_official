@extends('layouts.errors')

@section('content')
    <div class='col-lg-8 col-lg-offset-4'>
        <h2>{{ $exception->getMessage() }}</h2>
    </div>

@endsection