@extends('admin.no_sidebar_admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header">Original Fields</div>
            <div class="box-body">
                <ul>
                <li>Language</li> 
                <li>Course</li>
                <li>Schedule</li>
                <li>Organization</li>
                <li>Supervisor's email</li>
                <li>Supervisor first name</li>
                <li>Supervisor last name</li>
                <li>Supervisor approval</li>
                <li>HR approval </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-success">
            <div class="box-header">Fields</div>
            <div class="box-body">
                <ul>
                <li>Language</li> 
                <li>Course</li>
                <li>Schedule</li>
                <li>Organization</li>
                <li>Supervisor's email</li>
                <li>Supervisor first name</li>
                <li>Supervisor last name</li>
                <li>Supervisor approval</li>
                <li>HR approval </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop