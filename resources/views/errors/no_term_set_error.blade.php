@extends('layouts.errors')

@section('content')
    <div class="col-lg-4 offset-lg-4" style="margin-top: 150px;">
        <h1 class="text-danger"><center>Error: New Term Not Set</center></h1>
        <h5 class="text-center">Today's date has passed the end date of the previous Term.<br /> 
            Please create a new term.</h5>
            <div class="text-center" style="margin: 30px;">
                <a href="/admin" class="btn btn-lg btn-danger">Go Back to Admin page</a>
            </div>
    </div>
@endsection
