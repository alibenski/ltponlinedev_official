@extends('admin.no_sidebar_admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
Modifications
Change field data in the form
Convert from regular to payment-based
Convert from payment-based to regular
Regular to placement

Fields:
Language 
Course
Schedule
Organization
Supervisor's email
Supervisor first name
Supervisor last name
Supervisor approval
HR approval

@stop